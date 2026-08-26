<?php

namespace App\Http\Controllers\Admin;

use App\AuditLog;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class MediaOrphanCleanupController extends Controller
{
    /**
     * Status constants used across controller + views.
     */
    const STATUS_ACTIVE       = 'active';
    const STATUS_SOFT_DELETED = 'soft_deleted';
    const STATUS_MISSING      = 'missing';
    const STATUS_STALE_CLASS  = 'stale_class';

    /**
     * Maximum result rows allowed without date/size filter (R1 mitigation).
     */
    const MAX_RESULT_WITHOUT_FILTER = 5000;

    /**
     * Lockout threshold for failed password confirmations (R8 mitigation).
     */
    const PASSWORD_FAIL_THRESHOLD = 3;
    const PASSWORD_FAIL_TTL       = 300; // 5 minutes

    public function __construct()
    {
        // access = view index/show/preview; force_delete = perform deletion
        $this->middleware('can:media_orphan_cleanup_access');
        $this->middleware('can:media_orphan_cleanup_force_delete')->only(['destroy']);
        $this->middleware('throttle:10,1')->only(['destroy', 'preview']);
    }

    /**
     * Auto-discover model classes that implement HasMedia (R2 mitigation).
     * Cached for 1 hour to avoid scanning app/ on every request.
     *
     * @return array  ['App\Rfa' => 'rfas', ...]  class => table
     */
    protected function validModelTypes(): array
    {
        return Cache::remember('media_orphan_cleanup.valid_model_types', 3600, function () {
            $types = [];
            foreach (glob(app_path('*.php')) as $file) {
                $class = 'App\\' . basename($file, '.php');
                if (!class_exists($class)) {
                    continue;
                }
                try {
                    $reflection = new \ReflectionClass($class);
                    if (!$reflection->isSubclassOf(HasMedia::class)) {
                        continue;
                    }
                    // Instantiate to read $table property (avoid static cache issues)
                    $instance = new $class;
                    $types[$class] = $instance->getTable();
                } catch (\Throwable $e) {
                    continue;
                }
            }
            return $types;
        });
    }

    /**
     * Resolve status of a media row given parent lookup result.
     *
     * @param  \stdClass|null  $parent
     * @param  string          $modelType
     * @return string
     */
    protected function resolveStatus($parent, string $modelType): string
    {
        if (!class_exists($modelType)) {
            return self::STATUS_STALE_CLASS;
        }
        if (is_null($parent)) {
            return self::STATUS_MISSING;
        }
        if (property_exists($parent, 'deleted_at') && !is_null($parent->deleted_at)) {
            return self::STATUS_SOFT_DELETED;
        }
        return self::STATUS_ACTIVE;
    }

    /**
     * Check whether the file for a media row still exists on disk.
     */
    protected function fileExistsOnDisk(Media $media): bool
    {
        try {
            return Storage::disk($media->disk)->exists($media->getPathRelativeToRoot());
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Filter out sensitive columns from parent model attributes (G1 mitigation).
     * Prevents exposing password hashes, tokens, etc. in the detail view.
     */
    protected function filterSensitiveAttributes(array $attributes): array
    {
        $sensitiveKeys = [
            'password', 'password_hash', 'remember_token', 'api_token',
            'two_factor_secret', 'two_factor_recovery_codes',
            'stripe_id', 'card_brand', 'card_last_four', 'pm_type', 'pm_last_four',
            'trial_ends_at', 'secret', 'token', 'private_key',
        ];

        foreach ($sensitiveKeys as $key) {
            if (array_key_exists($key, $attributes)) {
                $attributes[$key] = '[HIDDEN]';
            }
        }

        return $attributes;
    }

    /**
     * Format bytes as human-readable string.
     */
    protected function humanFileSize(int $bytes): string
    {
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        }
        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' B';
    }

    /**
     * Index page: filter-first dashboard.
     * Default shows no results until admin selects filters and submits.
     */
    public function index(Request $request)
    {
        $validModelTypes = $this->validModelTypes();
        $modelTypeOptions = array_keys($validModelTypes);
        sort($modelTypeOptions);

        $input = $request->only([
            'submitted', 'model_type', 'status', 'deleted_from', 'deleted_to',
            'min_size', 'max_size', 'sort',
        ]);

        $results = null;
        $totalResults = 0;
        $warning = null;
        $needsMoreFilter = false;

        // Only query when admin explicitly submitted the filter form
        if ($request->filled('submitted')) {
            $status = $input['status'] ?? null;
            $modelType = $input['model_type'] ?? null;

            // stale_class does not require model_type (queries across all rows)
            $requiresModelType = $status !== self::STATUS_STALE_CLASS;
            if ($requiresModelType && empty($modelType)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['model_type' => 'ต้องเลือก model_type ก่อน (ยกเว้นกรณีดู stale_class)']);
            }

            $query = DB::table('media');

            // model_type filter
            if ($status === self::STATUS_STALE_CLASS) {
                // stale_class: model_type NOT IN valid classes
                if (!empty($validModelTypes)) {
                    $query->whereNotIn('model_type', array_keys($validModelTypes));
                }
                // optional model_type filter still allowed (e.g. specific stale class)
                if (!empty($modelType)) {
                    $query->where('model_type', $modelType);
                }
            } else {
                $query->where('model_type', $modelType);
                $parentTable = $validModelTypes[$modelType] ?? null;
                if ($parentTable) {
                    $query->leftJoin($parentTable, function ($join) use ($parentTable, $modelType) {
                        $join->on($parentTable . '.id', '=', 'media.model_id');
                    });

                    switch ($status) {
                        case self::STATUS_ACTIVE:
                            $query->whereNull($parentTable . '.deleted_at');
                            break;
                        case self::STATUS_SOFT_DELETED:
                            $query->whereNotNull($parentTable . '.deleted_at');
                            break;
                        case self::STATUS_MISSING:
                            $query->whereNull($parentTable . '.id');
                            break;
                        case 'orphan':
                            $query->where(function ($q) use ($parentTable) {
                                $q->whereNull($parentTable . '.id')
                                  ->orWhereNotNull($parentTable . '.deleted_at');
                            });
                            break;
                    }

                    // date range filter on parent.deleted_at
                    if (!empty($input['deleted_from'])) {
                        $query->where($parentTable . '.deleted_at', '>=', $input['deleted_from'] . ' 00:00:00');
                    }
                    if (!empty($input['deleted_to'])) {
                        $query->where($parentTable . '.deleted_at', '<=', $input['deleted_to'] . ' 23:59:59');
                    }
                }
            }

            // size range filter
            if (!empty($input['min_size'])) {
                $query->where('media.size', '>=', (int) $input['min_size']);
            }
            if (!empty($input['max_size'])) {
                $query->where('media.size', '<=', (int) $input['max_size']);
            }

            // Pre-count to enforce max-result-without-filter guard (R1)
            $totalResults = $query->count();
            $hasSizeOrDateFilter = !empty($input['min_size']) || !empty($input['max_size'])
                || !empty($input['deleted_from']) || !empty($input['deleted_to']);
            if (!$hasSizeOrDateFilter && $totalResults > self::MAX_RESULT_WITHOUT_FILTER) {
                $needsMoreFilter = true;
                $warning = 'ผลลัพธ์มากเกินไป (' . number_format($totalResults) . ' rows) '
                    . '— กรุณาเพิ่ม filter ช่วงวันที่หรือขนาดไฟล์เพื่อลด scope';
            } else {
                // sort: missing/stale_class pinned on top via raw expression
                $sort = $input['sort'] ?? 'id_desc';
                switch ($sort) {
                    case 'size_desc':
                        $query->orderByDesc('media.size');
                        break;
                    case 'size_asc':
                        $query->orderBy('media.size');
                        break;
                    case 'days_desc':
                        // requires parent.deleted_at — fallback to id_desc if not joined
                        if ($status !== self::STATUS_STALE_CLASS && !empty($modelType)) {
                            $query->orderByDesc($validModelTypes[$modelType] . '.deleted_at');
                        } else {
                            $query->orderByDesc('media.id');
                        }
                        break;
                    case 'days_asc':
                        if ($status !== self::STATUS_STALE_CLASS && !empty($modelType)) {
                            $query->orderBy($validModelTypes[$modelType] . '.deleted_at');
                        } else {
                            $query->orderByDesc('media.id');
                        }
                        break;
                    default:
                        $query->orderByDesc('media.id');
                        break;
                }

                $rows = $query->select('media.*')->simplePaginate(50);
                $results = $rows;

                if ($totalResults > 1000) {
                    $warning = 'ผลลัพธ์มี ' . number_format($totalResults) . ' rows '
                        . '— แนะนำให้ใช้ filter เพิ่มเพื่อลด scope';
                }
            }
        }

        return view('admin.mediaOrphanCleanup.index', [
            'modelTypeOptions' => $modelTypeOptions,
            'input'            => $input,
            'results'          => $results,
            'totalResults'     => $totalResults,
            'warning'          => $warning,
            'needsMoreFilter'  => $needsMoreFilter,
            'validModelTypes'  => $validModelTypes,
        ]);
    }

    /**
     * Show detail page for a single media row.
     */
    public function show($id)
    {
        $media = Media::findOrFail($id);
        $validModelTypes = $this->validModelTypes();

        $parent = null;
        $parentAttributes = null;
        $status = $this->resolveStatus(null, $media->model_type);

        if ($status !== self::STATUS_STALE_CLASS) {
            $class = $media->model_type;
            if (class_exists($class)) {
                try {
                    $instance = new $class;
                    // Guard: not all models use SoftDeletes — use withTrashed only if available
                    if (method_exists($instance, 'withTrashed')) {
                        $parent = $instance::withTrashed()->find($media->model_id);
                    } else {
                        $parent = $instance::find($media->model_id);
                    }
                    if ($parent) {
                        $status = $this->resolveStatus(
                            (object) ['deleted_at' => $parent->deleted_at ?? null],
                            $media->model_type
                        );
                        // G1: hide sensitive columns from display
                        $parentAttributes = $this->filterSensitiveAttributes($parent->getAttributes());
                    } else {
                        $status = self::STATUS_MISSING;
                    }
                } catch (\Throwable $e) {
                    $status = self::STATUS_MISSING;
                }
            }
        }

        $fileOnDisk = $this->fileExistsOnDisk($media);
        $daysSinceDelete = null;
        if ($status === self::STATUS_SOFT_DELETED && $parent && !empty($parent->deleted_at)) {
            $daysSinceDelete = Carbon::parse($parent->deleted_at)->diffInDays(now());
        }

        // Signed URL for the delete form action (anti-script, expires in 30 minutes)
        // 30 min gives admin time to read details + enter password without re-opening the page
        $deleteAction = URL::temporarySignedRoute('admin.media-orphan-cleanup.destroy', now()->addMinutes(30), ['id' => $media->id]);

        return view('admin.mediaOrphanCleanup.show', [
            'media'            => $media,
            'parent'           => $parent,
            'parentAttributes' => $parentAttributes,
            'status'           => $status,
            'fileOnDisk'       => $fileOnDisk,
            'daysSinceDelete'  => $daysSinceDelete,
            'deleteAction'     => $deleteAction,
            'humanSize'        => $this->humanFileSize($media->size),
            'canDelete'        => in_array($status, [
                self::STATUS_SOFT_DELETED,
                self::STATUS_MISSING,
                self::STATUS_STALE_CLASS,
            ]),
        ]);
    }

    /**
     * Preview file content through controller (auth-gated, not public URL).
     */
    public function preview($id)
    {
        $media = Media::findOrFail($id);

        if (!$this->fileExistsOnDisk($media)) {
            return response()->view('admin.mediaOrphanCleanup.preview-missing', [
                'media' => $media,
            ], 200);
        }

        try {
            $path = Storage::disk($media->disk)->path($media->getPathRelativeToRoot());

            return response()->file($path, [
                'Content-Type'        => $media->mime_type ?: 'application/octet-stream',
                'Content-Disposition' => 'inline; filename="' . $media->file_name . '"',
            ]);
        } catch (\Throwable $e) {
            // File disappeared between exists-check and response (race)
            return response()->view('admin.mediaOrphanCleanup.preview-missing', [
                'media' => $media,
            ], 200);
        }
    }

    /**
     * Hard-delete a single media row with multi-step confirmation.
     */
    public function destroy($id, Request $request)
    {
        // 0. Defense-in-depth: explicit force_delete gate (in case middleware bypass)
        if (Gate::denies('media_orphan_cleanup_force_delete')) {
            abort(403, 'Force delete permission required.');
        }

        // 1. Signed route check (anti-script) — use relative URL to survive proxy scheme changes
        if (!URL::hasValidSignature($request, false)) {
            abort(403, 'Invalid request signature.');
        }

        // 2. Validate
        $request->validate([
            'confirm_password' => 'required|string',
            'acknowledged'     => 'accepted',
            'reason'           => 'nullable|string|max:500',
        ]);

        // 3. Lockout check (R8)
        $this->assertNotLockedOut();

        // 4. Password re-confirmation
        if (!Hash::check($request->input('confirm_password'), auth()->user()->password)) {
            $this->recordPasswordFailure();
            $this->audit(
                'orphan_media_delete_password_failed',
                $id,
                ['reason' => 'password mismatch']
            );
            abort(403, 'Password confirmation failed.');
        }

        $media = Media::findOrFail($id);

        // 5. Re-resolve parent at delete time (race guard, R4)
        $parentStatus = $this->resolveStatusAtNow($media);

        if ($parentStatus === self::STATUS_ACTIVE) {
            $this->audit(
                'orphan_media_delete_blocked_active',
                $media->id,
                $this->buildAuditProps($media, $parentStatus, $request->input('reason'))
            );
            return redirect()->route('admin.media-orphan-cleanup.show', $media->id)
                ->with('error', 'ไม่สามารถลบได้: model ยัง active อยู่ (อาจถูก restore ระหว่างนั้น) — กรุณา refresh หน้านี้');
        }

        // 6. Stale class safety net: re-check class_exists at delete time (R2)
        if ($parentStatus === self::STATUS_STALE_CLASS && class_exists($media->model_type)) {
            $this->audit(
                'orphan_media_delete_blocked_class_now_exists',
                $media->id,
                $this->buildAuditProps($media, $parentStatus, $request->input('reason'))
            );
            return redirect()->route('admin.media-orphan-cleanup.show', $media->id)
                ->with('error', 'ไม่สามารถลบได้: model_type นี้มีอยู่ในโค้ดแล้ว (อาจเพิ่งเพิ่ม model) — กรุณา refresh หน้านี้');
        }

        // 7. Stale class requires reason (R3)
        if ($parentStatus === self::STATUS_STALE_CLASS && empty($request->input('reason'))) {
            return redirect()->route('admin.media-orphan-cleanup.show', $media->id)
                ->with('error', 'กรณี stale_class ต้องกรอกเหตุผลก่อนลบ');
        }

        // 8. Delete: handle missing file on disk (R5)
        $fileWasMissing = !$this->fileExistsOnDisk($media);
        try {
            if ($fileWasMissing) {
                // Force-delete media row directly, skip Spatie's file deletion
                DB::table('media')->where('id', $media->id)->delete();
            } else {
                $media->delete();
            }
        } catch (\Throwable $e) {
            // Fallback: force-delete row if Spatie throws
            DB::table('media')->where('id', $media->id)->delete();
        }

        // 9. Audit log
        $this->audit(
            'orphan_media_deleted',
            $media->id,
            $this->buildAuditProps($media, $parentStatus, $request->input('reason'), $fileWasMissing)
        );

        // 10. Reset lockout counter on success
        $this->clearLockout();

        return redirect()->route('admin.media-orphan-cleanup.index', [
            'submitted'    => 1,
            'model_type'   => $media->model_type,
            'status'       => 'orphan',
        ])->with('success', 'ลบ media id ' . $media->id . ' เรียบร้อยแล้ว');
    }

    /**
     * Resolve parent status at the current moment (race-safe).
     */
    protected function resolveStatusAtNow(Media $media): string
    {
        if (!class_exists($media->model_type)) {
            return self::STATUS_STALE_CLASS;
        }
        $class = $media->model_type;
        $instance = new $class;
        $parent = $instance::withTrashed()->find($media->model_id);
        if (!$parent) {
            return self::STATUS_MISSING;
        }
        if (!empty($parent->deleted_at)) {
            return self::STATUS_SOFT_DELETED;
        }
        return self::STATUS_ACTIVE;
    }

    /**
     * Build audit log properties payload.
     */
    protected function buildAuditProps(
        Media $media,
        string $parentStatus,
        ?string $reason = null,
        bool $fileWasMissing = false
    ): array {
        return [
            'media_id'             => $media->id,
            'file_name'            => $media->file_name,
            'collection_name'      => $media->collection_name,
            'size'                 => $media->size,
            'disk'                 => $media->disk,
            'model_type'           => $media->model_type,
            'model_id'             => $media->model_id,
            'parent_status'        => $parentStatus,
            'file_missing_on_disk' => $fileWasMissing,
            'reason'               => $reason,
        ];
    }

    /**
     * Write an audit log entry.
     */
    protected function audit(string $description, $subjectId, array $properties = []): void
    {
        AuditLog::create([
            'description'  => $description,
            'subject_id'   => $subjectId,
            'subject_type' => Media::class,
            'user_id'      => auth()->id(),
            'properties'   => json_encode($properties),
            'host'         => request()->ip(),
        ]);
    }

    /**
     * Lockout helpers (R8 mitigation).
     */
    protected function lockoutKey(): string
    {
        return 'media_cleanup_fail:' . auth()->id();
    }

    protected function assertNotLockedOut(): void
    {
        $fails = (int) Cache::get($this->lockoutKey(), 0);
        if ($fails >= self::PASSWORD_FAIL_THRESHOLD) {
            abort(429, 'Too many failed password confirmations. Try again later.');
        }
    }

    protected function recordPasswordFailure(): void
    {
        $fails = (int) Cache::get($this->lockoutKey(), 0) + 1;
        Cache::put($this->lockoutKey(), $fails, self::PASSWORD_FAIL_TTL);
    }

    protected function clearLockout(): void
    {
        Cache::forget($this->lockoutKey());
    }
}
