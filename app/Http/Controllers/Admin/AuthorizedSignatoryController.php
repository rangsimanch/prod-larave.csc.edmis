<?php

namespace App\Http\Controllers\Admin;

use App\AuthorizedSignatory;
use App\AuthorizedSignatoryJobtitle;
use App\ConstructionContract;
use App\Http\Controllers\Controller;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorizedSignatoryController extends Controller
{
    public function store(Request $request, ConstructionContract $constructionContract)
    {
        abort_if(Gate::denies('construction_contract_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $this->validateSignatory($request);

        $signatory = $constructionContract->authorizedSignatories()->create($data);

        $this->syncDefault($signatory);

        return redirect()->route('admin.construction-contracts.edit', $constructionContract->id)
            ->with('success', 'Authorized signatory added.');
    }

    public function importSuggested(ConstructionContract $constructionContract)
    {
        abort_if(Gate::denies('construction_contract_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $suggested = config('authorized_signatories.' . $constructionContract->code, []);

        if (empty($suggested)) {
            return redirect()->route('admin.construction-contracts.edit', $constructionContract->id)
                ->with('success', 'No suggested signatories for contract ' . $constructionContract->code . '.');
        }

        $signatoriesCreated = 0;
        $signatoriesSkipped = 0;
        $jobtitlesCreated   = 0;
        $jobtitlesSkipped   = 0;

        foreach ($suggested as $sort => $item) {
            $signatory = AuthorizedSignatory::where('construction_contract_id', $constructionContract->id)
                ->where('name', $item['name'])
                ->first();

            if ($signatory) {
                $signatoriesSkipped++;
            }
            else {
                $signatory = $constructionContract->authorizedSignatories()->create([
                    'name'             => $item['name'],
                    'name_rfi'         => $item['name_rfi'] ?? null,
                    'signature_path'   => $item['signature'] ?? null,
                    'signature_size_h' => $item['size'][0] ?? null,
                    'signature_size_w' => $item['size'][1] ?? null,
                    'is_default'       => $item['default'] ?? false,
                    'is_active'        => $item['active'] ?? true,
                    'sort_order'       => $sort,
                ]);
                $signatoriesCreated++;
            }

            foreach ($item['jobtitles'] as $jtSort => $jt) {
                $exists = $signatory->jobtitles()->where('jobtitle', $jt['jobtitle'])->exists();
                if ($exists) {
                    $jobtitlesSkipped++;
                    continue;
                }
                $signatory->jobtitles()->create([
                    'jobtitle'                => $jt['jobtitle'],
                    'signature_position_top'  => $jt['pos'][0] ?? null,
                    'signature_position_left' => $jt['pos'][1] ?? null,
                    'jobtitle_position_lf'    => $jt['jt_lf'] ?? null,
                    'issue_position_lf'       => $jt['issue'][0] ?? null,
                    'issue_position_lf_sub'   => $jt['issue'][1] ?? null,
                    'is_default'              => $jt['default'] ?? false,
                    'is_active'               => $jt['active'] ?? true,
                    'sort_order'              => $jtSort,
                ]);
                $jobtitlesCreated++;
            }
        }

        return redirect()->route('admin.construction-contracts.edit', $constructionContract->id)
            ->with('success', "Imported {$signatoriesCreated} signatories, {$jobtitlesCreated} job titles. Skipped {$signatoriesSkipped} signatories, {$jobtitlesSkipped} job titles (already exist).");
    }

    public function update(Request $request, AuthorizedSignatory $authorizedSignatory)
    {
        abort_if(Gate::denies('construction_contract_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $this->validateSignatory($request);

        $authorizedSignatory->update($data);

        $this->syncDefault($authorizedSignatory);

        return redirect()->route('admin.construction-contracts.edit', $authorizedSignatory->construction_contract_id)
            ->with('success', 'Authorized signatory updated.');
    }

    public function destroy(AuthorizedSignatory $authorizedSignatory)
    {
        abort_if(Gate::denies('construction_contract_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $contractId = $authorizedSignatory->construction_contract_id;
        $authorizedSignatory->delete();

        return redirect()->route('admin.construction-contracts.edit', $contractId)
            ->with('success', 'Authorized signatory deleted.');
    }

    public function storeJobtitle(Request $request, AuthorizedSignatory $authorizedSignatory)
    {
        abort_if(Gate::denies('construction_contract_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $this->validateJobtitle($request);

        $jobtitle = $authorizedSignatory->jobtitles()->create($data);
        $this->syncDefaultJobtitle($jobtitle);

        return redirect()->route('admin.construction-contracts.edit', $authorizedSignatory->construction_contract_id)
            ->with('success', 'Job title added.');
    }

    public function updateJobtitle(Request $request, AuthorizedSignatoryJobtitle $jobtitle)
    {
        abort_if(Gate::denies('construction_contract_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $this->validateJobtitle($request);
        $jobtitle->update($data);
        $this->syncDefaultJobtitle($jobtitle);

        return redirect()->route('admin.construction-contracts.edit', $jobtitle->signatory->construction_contract_id)
            ->with('success', 'Job title updated.');
    }

    public function destroyJobtitle(AuthorizedSignatoryJobtitle $jobtitle)
    {
        abort_if(Gate::denies('construction_contract_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $contractId = $jobtitle->signatory->construction_contract_id;
        $jobtitle->delete();

        return redirect()->route('admin.construction-contracts.edit', $contractId)
            ->with('success', 'Job title deleted.');
    }

    private function validateSignatory(Request $request)
    {
        $data = $request->validate([
            'name'                    => ['required', 'string', 'max:255'],
            'name_rfi'                => ['nullable', 'string', 'max:255'],
            'signature_file'          => ['nullable', 'image', 'max:2048'],
            'signature_path'          => ['nullable', 'string', 'max:255', function ($attribute, $value, $fail) {
                if ($value && ($value !== basename($value) || !file_exists(public_path('png-asset/' . $value)))) {
                    $fail('Invalid signature file.');
                }
            }],
            'signature_size_h'        => ['nullable', 'integer'],
            'signature_size_w'        => ['nullable', 'integer'],
            'is_default'              => ['nullable', 'boolean'],
            'is_active'               => ['nullable', 'boolean'],
            'sort_order'              => ['nullable', 'integer'],
        ]);

        $data['is_default'] = $request->boolean('is_default');
        $data['is_active']  = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        unset($data['signature_file']);
        if ($request->hasFile('signature_file')) {
            $data['signature_path'] = $this->storeSignatureFile($request->file('signature_file'));
        }

        return $data;
    }

    private function validateJobtitle(Request $request)
    {
        $data = $request->validate([
            'jobtitle'                => ['required', 'string', 'max:255'],
            'signature_position_top'  => ['nullable', 'integer'],
            'signature_position_left' => ['nullable', 'integer'],
            'jobtitle_position_lf'    => ['nullable', 'integer'],
            'issue_position_lf'       => ['nullable', 'integer'],
            'issue_position_lf_sub'   => ['nullable', 'integer'],
            'is_default'              => ['nullable', 'boolean'],
            'is_active'               => ['nullable', 'boolean'],
            'sort_order'              => ['nullable', 'integer'],
        ]);

        $data['is_default'] = $request->boolean('is_default');
        $data['is_active']  = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }

    private function storeSignatureFile($file)
    {
        $base = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $base = preg_replace('/[^A-Za-z0-9_\-.]/', '_', $base) ?: 'signature';
        $ext  = $file->extension() ?: 'png';
        $dir  = public_path('png-asset');
        $name = $base . '.' . $ext;
        $i = 1;
        while (file_exists($dir . '/' . $name)) {
            $name = $base . '-' . $i++ . '.' . $ext;
        }
        $file->move($dir, $name);

        return $name;
    }

    private function syncDefault(AuthorizedSignatory $signatory)
    {
        if ($signatory->is_default) {
            AuthorizedSignatory::where('construction_contract_id', $signatory->construction_contract_id)
                ->where('id', '!=', $signatory->id)
                ->update(['is_default' => false]);
        }
    }

    private function syncDefaultJobtitle(AuthorizedSignatoryJobtitle $jobtitle)
    {
        if ($jobtitle->is_default) {
            AuthorizedSignatoryJobtitle::where('authorized_signatory_id', $jobtitle->authorized_signatory_id)
                ->where('id', '!=', $jobtitle->id)
                ->update(['is_default' => false]);
        }
    }
}
