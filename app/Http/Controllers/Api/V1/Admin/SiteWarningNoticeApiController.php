<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreSiteWarningNoticeRequest;
use App\Http\Requests\UpdateSiteWarningNoticeRequest;
use App\Http\Resources\Admin\SiteWarningNoticeResource;
use App\SiteWarningNotice;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SiteWarningNoticeApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('site_warning_notice_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SiteWarningNoticeResource(SiteWarningNotice::with(['to_team', 'construction_contract', 'issue_by', 'reviewed_by', 'containment_responsible', 'corrective_responsible', 'section_2_reviewed_by', 'section_2_approved_by', 'csc_issuer', 'csc_qa', 'csc_pm', 'team'])->get());
    }

    public function store(StoreSiteWarningNoticeRequest $request)
    {
        $siteWarningNotice = SiteWarningNotice::create($request->all());

        if ($request->input('attachment', false)) {
            $siteWarningNotice->addMedia(storage_path('tmp/uploads/' . $request->input('attachment')))->toMediaCollection('attachment');
        }

        if ($request->input('file_upload', false)) {
            $siteWarningNotice->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
        }

        return (new SiteWarningNoticeResource($siteWarningNotice))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(SiteWarningNotice $siteWarningNotice)
    {
        abort_if(Gate::denies('site_warning_notice_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SiteWarningNoticeResource($siteWarningNotice->load(['to_team', 'construction_contract', 'issue_by', 'reviewed_by', 'containment_responsible', 'corrective_responsible', 'section_2_reviewed_by', 'section_2_approved_by', 'csc_issuer', 'csc_qa', 'csc_pm', 'team']));
    }

    public function update(UpdateSiteWarningNoticeRequest $request, SiteWarningNotice $siteWarningNotice)
    {
        $siteWarningNotice->update($request->all());

        if ($request->input('attachment', false)) {
            if (!$siteWarningNotice->attachment || $request->input('attachment') !== $siteWarningNotice->attachment->file_name) {
                $siteWarningNotice->addMedia(storage_path('tmp/uploads/' . $request->input('attachment')))->toMediaCollection('attachment');
            }
        } elseif ($siteWarningNotice->attachment) {
            $siteWarningNotice->attachment->delete();
        }

        if ($request->input('file_upload', false)) {
            if (!$siteWarningNotice->file_upload || $request->input('file_upload') !== $siteWarningNotice->file_upload->file_name) {
                $siteWarningNotice->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
            }
        } elseif ($siteWarningNotice->file_upload) {
            $siteWarningNotice->file_upload->delete();
        }

        return (new SiteWarningNoticeResource($siteWarningNotice))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(SiteWarningNotice $siteWarningNotice)
    {
        abort_if(Gate::denies('site_warning_notice_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $siteWarningNotice->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
