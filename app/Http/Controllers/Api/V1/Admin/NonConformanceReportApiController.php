<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreNonConformanceReportRequest;
use App\Http\Requests\UpdateNonConformanceReportRequest;
use App\Http\Resources\Admin\NonConformanceReportResource;
use App\NonConformanceReport;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NonConformanceReportApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('non_conformance_report_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new NonConformanceReportResource(NonConformanceReport::with(['ncn_ref', 'prepared_by', 'contractors_project', 'approved_by', 'team'])->get());
    }

    public function store(StoreNonConformanceReportRequest $request)
    {
        $nonConformanceReport = NonConformanceReport::create($request->all());

        if ($request->input('attachment', false)) {
            $nonConformanceReport->addMedia(storage_path('tmp/uploads/' . $request->input('attachment')))->toMediaCollection('attachment');
        }

        if ($request->input('file_upload', false)) {
            $nonConformanceReport->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
        }

        return (new NonConformanceReportResource($nonConformanceReport))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(NonConformanceReport $nonConformanceReport)
    {
        abort_if(Gate::denies('non_conformance_report_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new NonConformanceReportResource($nonConformanceReport->load(['ncn_ref', 'prepared_by', 'contractors_project', 'approved_by', 'team']));
    }

    public function update(UpdateNonConformanceReportRequest $request, NonConformanceReport $nonConformanceReport)
    {
        $nonConformanceReport->update($request->all());

        if ($request->input('attachment', false)) {
            if (!$nonConformanceReport->attachment || $request->input('attachment') !== $nonConformanceReport->attachment->file_name) {
                $nonConformanceReport->addMedia(storage_path('tmp/uploads/' . $request->input('attachment')))->toMediaCollection('attachment');
            }
        } elseif ($nonConformanceReport->attachment) {
            $nonConformanceReport->attachment->delete();
        }

        if ($request->input('file_upload', false)) {
            if (!$nonConformanceReport->file_upload || $request->input('file_upload') !== $nonConformanceReport->file_upload->file_name) {
                $nonConformanceReport->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
            }
        } elseif ($nonConformanceReport->file_upload) {
            $nonConformanceReport->file_upload->delete();
        }

        return (new NonConformanceReportResource($nonConformanceReport))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(NonConformanceReport $nonConformanceReport)
    {
        abort_if(Gate::denies('non_conformance_report_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $nonConformanceReport->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
