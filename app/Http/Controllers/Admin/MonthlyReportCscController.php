<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyMonthlyReportCscRequest;
use App\Http\Requests\StoreMonthlyReportCscRequest;
use App\Http\Requests\UpdateMonthlyReportCscRequest;
use App\MonthlyReportCsc;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class MonthlyReportCscController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('monthly_report_csc_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = MonthlyReportCsc::with(['team'])->select(sprintf('%s.*', (new MonthlyReportCsc)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'monthly_report_csc_show';
                $editGate      = 'monthly_report_csc_edit';
                $deleteGate    = 'monthly_report_csc_delete';
                $crudRoutePart = 'monthly-report-cscs';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('file_upload', function ($row) {
                if (!$row->file_upload) {
                    return '';
                }

                $links = [];

                foreach ($row->file_upload as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });

            $table->rawColumns(['actions', 'placeholder', 'file_upload']);

            return $table->make(true);
        }

        return view('admin.monthlyReportCscs.index');
    }

    public function create()
    {
        abort_if(Gate::denies('monthly_report_csc_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.monthlyReportCscs.create');
    }

    public function store(StoreMonthlyReportCscRequest $request)
    {
        $monthlyReportCsc = MonthlyReportCsc::create($request->all());

        foreach ($request->input('file_upload', []) as $file) {
            $monthlyReportCsc->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $monthlyReportCsc->id]);
        }

        return redirect()->route('admin.monthly-report-cscs.index');
    }

    public function edit(MonthlyReportCsc $monthlyReportCsc)
    {
        abort_if(Gate::denies('monthly_report_csc_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $monthlyReportCsc->load('team');

        return view('admin.monthlyReportCscs.edit', compact('monthlyReportCsc'));
    }

    public function update(UpdateMonthlyReportCscRequest $request, MonthlyReportCsc $monthlyReportCsc)
    {
        $monthlyReportCsc->update($request->all());

        if (count($monthlyReportCsc->file_upload) > 0) {
            foreach ($monthlyReportCsc->file_upload as $media) {
                if (!in_array($media->file_name, $request->input('file_upload', []))) {
                    $media->delete();
                }
            }
        }

        $media = $monthlyReportCsc->file_upload->pluck('file_name')->toArray();

        foreach ($request->input('file_upload', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $monthlyReportCsc->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
            }
        }

        return redirect()->route('admin.monthly-report-cscs.index');
    }

    public function show(MonthlyReportCsc $monthlyReportCsc)
    {
        abort_if(Gate::denies('monthly_report_csc_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $monthlyReportCsc->load('team');

        return view('admin.monthlyReportCscs.show', compact('monthlyReportCsc'));
    }

    public function destroy(MonthlyReportCsc $monthlyReportCsc)
    {
        abort_if(Gate::denies('monthly_report_csc_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $monthlyReportCsc->delete();

        return back();
    }

    public function massDestroy(MassDestroyMonthlyReportCscRequest $request)
    {
        MonthlyReportCsc::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('monthly_report_csc_create') && Gate::denies('monthly_report_csc_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new MonthlyReportCsc();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
