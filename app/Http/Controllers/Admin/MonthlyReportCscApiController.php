<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreMonthlyReportCscRequest;
use App\Http\Requests\UpdateMonthlyReportCscRequest;
use App\Http\Resources\Admin\MonthlyReportCscResource;
use App\MonthlyReportCsc;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MonthlyReportCscApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('monthly_report_csc_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new MonthlyReportCscResource(MonthlyReportCsc::with(['team'])->get());
    }

    public function store(StoreMonthlyReportCscRequest $request)
    {
        $monthlyReportCsc = MonthlyReportCsc::create($request->all());

        if ($request->input('file_upload', false)) {
            $monthlyReportCsc->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
        }

        return (new MonthlyReportCscResource($monthlyReportCsc))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(MonthlyReportCsc $monthlyReportCsc)
    {
        abort_if(Gate::denies('monthly_report_csc_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new MonthlyReportCscResource($monthlyReportCsc->load(['team']));
    }

    public function update(UpdateMonthlyReportCscRequest $request, MonthlyReportCsc $monthlyReportCsc)
    {
        $monthlyReportCsc->update($request->all());

        if ($request->input('file_upload', false)) {
            if (!$monthlyReportCsc->file_upload || $request->input('file_upload') !== $monthlyReportCsc->file_upload->file_name) {
                $monthlyReportCsc->addMedia(storage_path('tmp/uploads/' . $request->input('file_upload')))->toMediaCollection('file_upload');
            }
        } elseif ($monthlyReportCsc->file_upload) {
            $monthlyReportCsc->file_upload->delete();
        }

        return (new MonthlyReportCscResource($monthlyReportCsc))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(MonthlyReportCsc $monthlyReportCsc)
    {
        abort_if(Gate::denies('monthly_report_csc_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $monthlyReportCsc->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
