<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyNonConformanceReportRequest;
use App\Http\Requests\StoreNonConformanceReportRequest;
use App\Http\Requests\UpdateNonConformanceReportRequest;
use App\NonConformanceNotice;
use App\NonConformanceReport;
use App\Team;
use App\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class NonConformanceReportController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('non_conformance_report_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = NonConformanceReport::with(['ncn_ref', 'prepared_by', 'contractors_project', 'approved_by', 'team'])->select(sprintf('%s.*', (new NonConformanceReport)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'non_conformance_report_show';
                $editGate      = 'non_conformance_report_edit';
                $deleteGate    = 'non_conformance_report_delete';
                $crudRoutePart = 'non-conformance-reports';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->addColumn('ncn_ref_ref_no', function ($row) {
                return $row->ncn_ref ? $row->ncn_ref->ref_no : '';
            });

            $table->editColumn('corresponding_to', function ($row) {
                return $row->corresponding_to ? $row->corresponding_to : "";
            });

            $table->editColumn('root_cause', function ($row) {
                return $row->root_cause ? $row->root_cause : "";
            });
            $table->editColumn('corrective_action', function ($row) {
                return $row->corrective_action ? $row->corrective_action : "";
            });
            $table->editColumn('preventive_action', function ($row) {
                return $row->preventive_action ? $row->preventive_action : "";
            });
            $table->editColumn('ref_no', function ($row) {
                return $row->ref_no ? $row->ref_no : "";
            });
            $table->editColumn('attachment', function ($row) {
                if (!$row->attachment) {
                    return '';
                }

                $links = [];

                foreach ($row->attachment as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });
            $table->addColumn('prepared_by_name', function ($row) {
                return $row->prepared_by ? $row->prepared_by->name : '';
            });

            $table->addColumn('contractors_project_name', function ($row) {
                return $row->contractors_project ? $row->contractors_project->name : '';
            });

            $table->editColumn('csc_consideration_status', function ($row) {
                return $row->csc_consideration_status ? NonConformanceReport::CSC_CONSIDERATION_STATUS_SELECT[$row->csc_consideration_status] : '';
            });
            $table->addColumn('approved_by_name', function ($row) {
                return $row->approved_by ? $row->approved_by->name : '';
            });

            $table->editColumn('csc_disposition_status', function ($row) {
                return $row->csc_disposition_status ? NonConformanceReport::CSC_DISPOSITION_STATUS_SELECT[$row->csc_disposition_status] : '';
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

            $table->rawColumns(['actions', 'placeholder', 'ncn_ref', 'attachment', 'prepared_by', 'contractors_project', 'approved_by', 'file_upload']);

            return $table->make(true);
        }

        $non_conformance_notices = NonConformanceNotice::get();
        $users                   = User::get();
        $users                   = User::get();
        $users                   = User::get();
        $teams                   = Team::get();

        return view('admin.nonConformanceReports.index', compact('non_conformance_notices', 'users', 'users', 'users', 'teams'));
    }

    public function create()
    {
        abort_if(Gate::denies('non_conformance_report_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ncn_refs = NonConformanceNotice::all()->pluck('ref_no', 'id')->prepend(trans('global.pleaseSelect'), '');

        $prepared_bies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $contractors_projects = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $approved_bies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.nonConformanceReports.create', compact('ncn_refs', 'prepared_bies', 'contractors_projects', 'approved_bies'));
    }

    public function store(StoreNonConformanceReportRequest $request)
    {
        $nonConformanceReport = NonConformanceReport::create($request->all());

        foreach ($request->input('attachment', []) as $file) {
            $nonConformanceReport->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('attachment');
        }

        foreach ($request->input('file_upload', []) as $file) {
            $nonConformanceReport->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $nonConformanceReport->id]);
        }

        return redirect()->route('admin.non-conformance-reports.index');
    }

    public function edit(NonConformanceReport $nonConformanceReport)
    {
        abort_if(Gate::denies('non_conformance_report_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ncn_refs = NonConformanceNotice::all()->pluck('ref_no', 'id')->prepend(trans('global.pleaseSelect'), '');

        $prepared_bies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $contractors_projects = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $approved_bies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $nonConformanceReport->load('ncn_ref', 'prepared_by', 'contractors_project', 'approved_by', 'team');

        return view('admin.nonConformanceReports.edit', compact('ncn_refs', 'prepared_bies', 'contractors_projects', 'approved_bies', 'nonConformanceReport'));
    }

    public function update(UpdateNonConformanceReportRequest $request, NonConformanceReport $nonConformanceReport)
    {
        $nonConformanceReport->update($request->all());

        if (count($nonConformanceReport->attachment) > 0) {
            foreach ($nonConformanceReport->attachment as $media) {
                if (!in_array($media->file_name, $request->input('attachment', []))) {
                    $media->delete();
                }
            }
        }

        $media = $nonConformanceReport->attachment->pluck('file_name')->toArray();

        foreach ($request->input('attachment', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $nonConformanceReport->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('attachment');
            }
        }

        if (count($nonConformanceReport->file_upload) > 0) {
            foreach ($nonConformanceReport->file_upload as $media) {
                if (!in_array($media->file_name, $request->input('file_upload', []))) {
                    $media->delete();
                }
            }
        }

        $media = $nonConformanceReport->file_upload->pluck('file_name')->toArray();

        foreach ($request->input('file_upload', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $nonConformanceReport->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
            }
        }

        return redirect()->route('admin.non-conformance-reports.index');
    }

    public function show(NonConformanceReport $nonConformanceReport)
    {
        abort_if(Gate::denies('non_conformance_report_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $nonConformanceReport->load('ncn_ref', 'prepared_by', 'contractors_project', 'approved_by', 'team');

        return view('admin.nonConformanceReports.show', compact('nonConformanceReport'));
    }

    public function destroy(NonConformanceReport $nonConformanceReport)
    {
        abort_if(Gate::denies('non_conformance_report_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $nonConformanceReport->delete();

        return back();
    }

    public function massDestroy(MassDestroyNonConformanceReportRequest $request)
    {
        NonConformanceReport::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('non_conformance_report_create') && Gate::denies('non_conformance_report_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new NonConformanceReport();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
