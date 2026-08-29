<?php

namespace App\Http\Controllers\Admin;

use App\AuditLog;
use App\Http\Controllers\Controller;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class AuditLogsController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('audit_log_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'created_from' => ['nullable', 'date_format:Y-m-d'],
                'created_to'   => ['nullable', 'date_format:Y-m-d'],
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => 'Invalid date filter.'], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $query = AuditLog::query()
                ->leftJoin('users', 'audit_logs.user_id', '=', 'users.id')
                ->select([
                    'audit_logs.id',
                    'audit_logs.description',
                    'audit_logs.subject_id',
                    'audit_logs.subject_type',
                    'audit_logs.user_id',
                    'audit_logs.host',
                    'audit_logs.created_at',
                    'users.name as user_name',
                    'users.email as user_email',
                ]);
            $canShow = !Gate::denies('audit_log_show');

            if ($request->filled('created_from')) {
                $query->where('audit_logs.created_at', '>=', $request->input('created_from') . ' 00:00:00');
            }

            if ($request->filled('created_to')) {
                $query->where('audit_logs.created_at', '<=', $request->input('created_to') . ' 23:59:59');
            }

            $table = DataTables::of($query);
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('user_name', function ($row) {
                return $row->user_name;
            });
            $table->addColumn('user_email', function ($row) {
                return $row->user_email;
            });
            $table->addColumn('actions', function ($row) use ($canShow) {
                if (!$canShow) {
                    return '';
                }

                return '<a class="btn btn-xs btn-primary" href="' . route('admin.audit-logs.show', $row->id) . '">' . trans('global.view') . '</a>';
            });

            $table->filterColumn('user_name', function ($query, $keyword) {
                $query->where('users.name', 'like', '%' . $keyword . '%');
            });
            $table->filterColumn('user_email', function ($query, $keyword) {
                $query->where('users.email', 'like', '%' . $keyword . '%');
            });

            return $table->rawColumns(['placeholder', 'actions'])->make(true);
        }

        return view('admin.auditLogs.index');
    }

    protected function preparePropertyChanges(AuditLog $auditLog): array
    {
        $properties = $auditLog->properties;
        if ($properties instanceof \Illuminate\Support\Collection) {
            $properties = $properties->toArray();
        }

        if (!is_array($properties)) {
            return [];
        }

        $hasSnapshots = array_key_exists('old', $properties) || array_key_exists('new', $properties) || array_key_exists('changes', $properties);
        if (!$hasSnapshots) {
            if ($auditLog->description === 'deleted') {
                $old = $properties;
                $new = [];
                $changes = [];
            } elseif ($auditLog->description === 'updated') {
                $old = $this->getPreviousAuditValues($auditLog);
                $new = $properties;
                $changes = $properties;
            } else {
                $old = [];
                $new = $properties;
                $changes = $properties;
            }
        } else {
            $old = is_array($properties['old'] ?? null) ? $properties['old'] : [];
            $new = is_array($properties['new'] ?? null) ? $properties['new'] : [];
            $changes = is_array($properties['changes'] ?? null) ? $properties['changes'] : [];
        }

        $fields = array_unique(array_merge(array_keys($old), array_keys($new), array_keys($changes)));
        $propertyChanges = [];

        foreach ($fields as $field) {
            if ($this->isSensitiveAuditField($field)) {
                continue;
            }

            $hasOldValue = array_key_exists($field, $old);
            $hasNewValue = array_key_exists($field, $new);
            $hasChangedValue = array_key_exists($field, $changes);
            $oldValue = $hasOldValue ? $this->sanitizeAuditValue($old[$field]) : null;
            $newValue = $hasNewValue
                ? $this->sanitizeAuditValue($new[$field])
                : ($hasChangedValue ? $this->sanitizeAuditValue($changes[$field]) : null);

            if ($hasSnapshots && $hasOldValue && $hasNewValue && $oldValue === $newValue && !$hasChangedValue) {
                continue;
            }

            $propertyChanges[] = [
                'field' => $field,
                'old'   => $oldValue,
                'new'   => $newValue,
            ];
        }

        return $propertyChanges;
    }

    protected function getPreviousAuditValues(AuditLog $auditLog): array
    {
        $previousAuditLog = AuditLog::query()
            ->where('subject_type', $auditLog->subject_type)
            ->where('subject_id', $auditLog->subject_id)
            ->where('id', '<', $auditLog->id)
            ->latest('id')
            ->first();

        if (!$previousAuditLog) {
            return [];
        }

        $properties = $previousAuditLog->properties;
        if ($properties instanceof \Illuminate\Support\Collection) {
            $properties = $properties->toArray();
        }

        if (!is_array($properties)) {
            return [];
        }

        if (is_array($properties['new'] ?? null)) {
            return $properties['new'];
        }

        if (is_array($properties['old'] ?? null)) {
            return $properties['old'];
        }

        return $properties;
    }

    protected function sanitizeAuditValue($value)
    {
        if ($value instanceof \Illuminate\Support\Collection) {
            $value = $value->toArray();
        }

        if (is_array($value)) {
            $sanitized = [];
            foreach ($value as $key => $nestedValue) {
                if (!$this->isSensitiveAuditField($key)) {
                    $sanitized[$key] = $this->sanitizeAuditValue($nestedValue);
                }
            }

            return $sanitized;
        }

        return $value;
    }

    protected function isSensitiveAuditField($field): bool
    {
        return preg_match('/password|token|secret|api[_-]?key|private[_-]?key/i', (string) $field) === 1;
    }

    public function show(AuditLog $auditLog)
    {
        abort_if(Gate::denies('audit_log_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $auditLog->load('user');
        $modelName = $auditLog->subject_type ? class_basename($auditLog->subject_type) : '-';
        $propertyChanges = $this->preparePropertyChanges($auditLog);

        return view('admin.auditLogs.show', compact('auditLog', 'modelName', 'propertyChanges'));
    }
}
