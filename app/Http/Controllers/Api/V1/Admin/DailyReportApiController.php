<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\DailyReport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreDailyReportRequest;
use App\Http\Requests\UpdateDailyReportRequest;
use App\Http\Resources\Admin\DailyReportResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DailyReportApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('daily_report_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DailyReportResource(DailyReport::with(['construction_contract'])->get());
    }

    public function store(StoreDailyReportRequest $request)
    {
        $dailyReport = DailyReport::create($request->all());

        if ($request->input('documents', false)) {
            $dailyReport->addMedia(storage_path('tmp/uploads/' . $request->input('documents')))->toMediaCollection('documents');
        }

        return (new DailyReportResource($dailyReport))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(DailyReport $dailyReport)
    {
        abort_if(Gate::denies('daily_report_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DailyReportResource($dailyReport->load(['construction_contract']));
    }

    public function update(UpdateDailyReportRequest $request, DailyReport $dailyReport)
    {
        $dailyReport->update($request->all());

        if ($request->input('documents', false)) {
            if (!$dailyReport->documents || $request->input('documents') !== $dailyReport->documents->file_name) {
                $dailyReport->addMedia(storage_path('tmp/uploads/' . $request->input('documents')))->toMediaCollection('documents');
            }
        } elseif ($dailyReport->documents) {
            $dailyReport->documents->delete();
        }

        return (new DailyReportResource($dailyReport))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(DailyReport $dailyReport)
    {
        abort_if(Gate::denies('daily_report_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dailyReport->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
