<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreMonthlyReportConstructorRequest;
use App\Http\Requests\UpdateMonthlyReportConstructorRequest;
use App\Http\Resources\Admin\MonthlyReportConstructorResource;
use App\MonthlyReportConstructor;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MonthlyReportConstructorApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('monthly_report_constructor_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new MonthlyReportConstructorResource(MonthlyReportConstructor::all());
    }

    public function store(StoreMonthlyReportConstructorRequest $request)
    {
        $monthlyReportConstructor = MonthlyReportConstructor::create($request->all());

        if ($request->input('file_uplaod', false)) {
            $monthlyReportConstructor->addMedia(storage_path('tmp/uploads/' . $request->input('file_uplaod')))->toMediaCollection('file_uplaod');
        }

        return (new MonthlyReportConstructorResource($monthlyReportConstructor))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(MonthlyReportConstructor $monthlyReportConstructor)
    {
        abort_if(Gate::denies('monthly_report_constructor_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new MonthlyReportConstructorResource($monthlyReportConstructor);
    }

    public function update(UpdateMonthlyReportConstructorRequest $request, MonthlyReportConstructor $monthlyReportConstructor)
    {
        $monthlyReportConstructor->update($request->all());

        if ($request->input('file_uplaod', false)) {
            if (!$monthlyReportConstructor->file_uplaod || $request->input('file_uplaod') !== $monthlyReportConstructor->file_uplaod->file_name) {
                $monthlyReportConstructor->addMedia(storage_path('tmp/uploads/' . $request->input('file_uplaod')))->toMediaCollection('file_uplaod');
            }
        } elseif ($monthlyReportConstructor->file_uplaod) {
            $monthlyReportConstructor->file_uplaod->delete();
        }

        return (new MonthlyReportConstructorResource($monthlyReportConstructor))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(MonthlyReportConstructor $monthlyReportConstructor)
    {
        abort_if(Gate::denies('monthly_report_constructor_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $monthlyReportConstructor->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
