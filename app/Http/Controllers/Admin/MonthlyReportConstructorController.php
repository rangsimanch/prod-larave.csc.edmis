<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyMonthlyReportConstructorRequest;
use App\Http\Requests\StoreMonthlyReportConstructorRequest;
use App\Http\Requests\UpdateMonthlyReportConstructorRequest;
use App\MonthlyReportConstructor;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class MonthlyReportConstructorController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('monthly_report_constructor_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $monthlyReportConstructors = MonthlyReportConstructor::all();

        return view('admin.monthlyReportConstructors.index', compact('monthlyReportConstructors'));
    }

    public function create()
    {
        abort_if(Gate::denies('monthly_report_constructor_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.monthlyReportConstructors.create');
    }

    public function store(StoreMonthlyReportConstructorRequest $request)
    {
        $monthlyReportConstructor = MonthlyReportConstructor::create($request->all());

        foreach ($request->input('file_uplaod', []) as $file) {
            $monthlyReportConstructor->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_uplaod');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $monthlyReportConstructor->id]);
        }

        return redirect()->route('admin.monthly-report-constructors.index');
    }

    public function edit(MonthlyReportConstructor $monthlyReportConstructor)
    {
        abort_if(Gate::denies('monthly_report_constructor_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.monthlyReportConstructors.edit', compact('monthlyReportConstructor'));
    }

    public function update(UpdateMonthlyReportConstructorRequest $request, MonthlyReportConstructor $monthlyReportConstructor)
    {
        $monthlyReportConstructor->update($request->all());

        if (count($monthlyReportConstructor->file_uplaod) > 0) {
            foreach ($monthlyReportConstructor->file_uplaod as $media) {
                if (!in_array($media->file_name, $request->input('file_uplaod', []))) {
                    $media->delete();
                }
            }
        }

        $media = $monthlyReportConstructor->file_uplaod->pluck('file_name')->toArray();

        foreach ($request->input('file_uplaod', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $monthlyReportConstructor->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_uplaod');
            }
        }

        return redirect()->route('admin.monthly-report-constructors.index');
    }

    public function show(MonthlyReportConstructor $monthlyReportConstructor)
    {
        abort_if(Gate::denies('monthly_report_constructor_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.monthlyReportConstructors.show', compact('monthlyReportConstructor'));
    }

    public function destroy(MonthlyReportConstructor $monthlyReportConstructor)
    {
        abort_if(Gate::denies('monthly_report_constructor_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $monthlyReportConstructor->delete();

        return back();
    }

    public function massDestroy(MassDestroyMonthlyReportConstructorRequest $request)
    {
        MonthlyReportConstructor::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('monthly_report_constructor_create') && Gate::denies('monthly_report_constructor_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new MonthlyReportConstructor();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
