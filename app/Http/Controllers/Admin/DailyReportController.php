<?php

namespace App\Http\Controllers\Admin;

use App\ConstructionContract;
use App\DailyReport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyDailyReportRequest;
use App\Http\Requests\StoreDailyReportRequest;
use App\Http\Requests\UpdateDailyReportRequest;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class DailyReportController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('daily_report_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = DailyReport::with(['construction_contract'])->select(sprintf('%s.*', (new DailyReport)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'daily_report_show';
                $editGate      = 'daily_report_edit';
                $deleteGate    = 'daily_report_delete';
                $crudRoutePart = 'daily-reports';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('documents', function ($row) {
                if (!$row->documents) {
                    return '';
                }

                $links = [];

                foreach ($row->documents as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });
            $table->editColumn('document_code', function ($row) {
                return $row->document_code ? $row->document_code : "";
            });

            $table->addColumn('construction_contract_code', function ($row) {
                return $row->construction_contract ? $row->construction_contract->code : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'documents', 'construction_contract']);

            return $table->make(true);
        }

        return view('admin.dailyReports.index');
    }

    public function create()
    {
        abort_if(Gate::denies('daily_report_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.dailyReports.create', compact('construction_contracts'));
    }

    public function store(StoreDailyReportRequest $request)
    {
        $dailyReport = DailyReport::create($request->all());

        foreach ($request->input('documents', []) as $file) {
            $dailyReport->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('documents');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $dailyReport->id]);
        }

        return redirect()->route('admin.daily-reports.index');
    }

    public function edit(DailyReport $dailyReport)
    {
        abort_if(Gate::denies('daily_report_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $dailyReport->load('construction_contract');

        return view('admin.dailyReports.edit', compact('construction_contracts', 'dailyReport'));
    }

    public function update(UpdateDailyReportRequest $request, DailyReport $dailyReport)
    {
        $dailyReport->update($request->all());

        if (count($dailyReport->documents) > 0) {
            foreach ($dailyReport->documents as $media) {
                if (!in_array($media->file_name, $request->input('documents', []))) {
                    $media->delete();
                }
            }
        }

        $media = $dailyReport->documents->pluck('file_name')->toArray();

        foreach ($request->input('documents', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $dailyReport->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('documents');
            }
        }

        return redirect()->route('admin.daily-reports.index');
    }

    public function show(DailyReport $dailyReport)
    {
        abort_if(Gate::denies('daily_report_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dailyReport->load('construction_contract');

        return view('admin.dailyReports.show', compact('dailyReport'));
    }

    public function destroy(DailyReport $dailyReport)
    {
        abort_if(Gate::denies('daily_report_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dailyReport->delete();

        return back();
    }

    public function massDestroy(MassDestroyDailyReportRequest $request)
    {
        DailyReport::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('daily_report_create') && Gate::denies('daily_report_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new DailyReport();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
