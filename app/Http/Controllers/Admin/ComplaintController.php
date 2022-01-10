<?php

namespace App\Http\Controllers\Admin;

use App\Complaint;
use App\ConstructionContract;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyComplaintRequest;
use App\Http\Requests\StoreComplaintRequest;
use App\Http\Requests\UpdateComplaintRequest;
use App\Team;
use App\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

use App\Charts\ComplaintChart;

class ComplaintController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('complaint_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Complaint::with(['construction_contract', 'complaint_recipient', 'operator', 'team'])->select(sprintf('%s.*', (new Complaint)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'complaint_show';
                $editGate      = 'complaint_edit';
                $deleteGate    = 'complaint_delete';
                $crudRoutePart = 'complaints';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('status', function ($row) {
                return $row->status ? Complaint::STATUS_SELECT[$row->status] : '';
            });
            $table->addColumn('construction_contract_code', function ($row) {
                return $row->construction_contract ? $row->construction_contract->code : '';
            });

            $table->editColumn('document_number', function ($row) {
                return $row->document_number ? $row->document_number : "";
            });
            $table->addColumn('complaint_recipient_code', function ($row) {
                return $row->complaint_recipient ? $row->complaint_recipient->code : '';
            });

            $table->editColumn('source_code', function ($row) {
                return $row->source_code ? Complaint::SOURCE_CODE_SELECT[$row->source_code] : '';
            });
            $table->editColumn('file_attachment_create', function ($row) {
                if (!$row->file_attachment_create) {
                    return '';
                }

                $links = [];

                foreach ($row->file_attachment_create as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });
            $table->editColumn('complainant', function ($row) {
                return $row->complainant ? $row->complainant : "";
            });
            $table->editColumn('complainant_tel', function ($row) {
                return $row->complainant_tel ? $row->complainant_tel : "";
            });
            $table->editColumn('complainant_detail', function ($row) {
                return $row->complainant_detail ? $row->complainant_detail : "";
            });
            $table->editColumn('complaint_description', function ($row) {
                return $row->complaint_description ? $row->complaint_description : "";
            });
            $table->editColumn('type_code', function ($row) {
                return $row->type_code ? Complaint::TYPE_CODE_SELECT[$row->type_code] : '';
            });
            $table->editColumn('impact_code', function ($row) {
                return $row->impact_code ? Complaint::IMPACT_CODE_SELECT[$row->impact_code] : '';
            });
            $table->addColumn('operator_name', function ($row) {
                return $row->operator ? $row->operator->name : '';
            });

            $table->editColumn('action_detail', function ($row) {
                return $row->action_detail ? $row->action_detail : "";
            });
            $table->editColumn('progress_file', function ($row) {
                if (!$row->progress_file) {
                    return '';
                }

                $links = [];

                foreach ($row->progress_file as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });

            $table->rawColumns(['actions', 'placeholder', 'construction_contract', 'complaint_recipient', 'file_attachment_create', 'operator', 'progress_file']);

            return $table->make(true);
        }

        $construction_contracts = ConstructionContract::get();
        $teams                  = Team::get();
        $users                  = User::get();

        return view('admin.complaints.index', compact('construction_contracts', 'teams', 'users'));
    }

    public function create()
    {
        abort_if(Gate::denies('complaint_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $complaint_recipients = Team::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.complaints.create', compact('construction_contracts', 'complaint_recipients'));
    }

    public function store(StoreComplaintRequest $request)
    {
        $data = $request->all();
        $data['status'] = 1;
        $complaint = Complaint::create($data);

        foreach ($request->input('file_attachment_create', []) as $file) {
            $complaint->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_attachment_create');
        }

        foreach ($request->input('progress_file', []) as $file) {
            $complaint->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('progress_file');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $complaint->id]);
        }

        return redirect()->route('admin.complaints.index');
    }

    public function edit(Complaint $complaint)
    {
        abort_if(Gate::denies('complaint_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $operators = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $complaint->load('construction_contract', 'complaint_recipient', 'operator', 'team');

        return view('admin.complaints.edit', compact('operators', 'complaint'));
    }

    public function update(UpdateComplaintRequest $request, Complaint $complaint)
    {
        $data = $request->all();
        $complaint->update($data);

        if (count($complaint->file_attachment_create) > 0) {
            foreach ($complaint->file_attachment_create as $media) {
                if (!in_array($media->file_name, $request->input('file_attachment_create', []))) {
                    $media->delete();
                }
            }
        }

        $media = $complaint->file_attachment_create->pluck('file_name')->toArray();

        foreach ($request->input('file_attachment_create', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $complaint->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_attachment_create');
            }
        }

        if (count($complaint->progress_file) > 0) {
            foreach ($complaint->progress_file as $media) {
                if (!in_array($media->file_name, $request->input('progress_file', []))) {
                    $media->delete();
                }
            }
        }

        $media = $complaint->progress_file->pluck('file_name')->toArray();

        foreach ($request->input('progress_file', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $complaint->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('progress_file');
            }
        }

        return redirect()->route('admin.complaints.index');
    }

    public function show(Complaint $complaint)
    {
        abort_if(Gate::denies('complaint_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $complaint->load('construction_contract', 'complaint_recipient', 'operator', 'team');

        return view('admin.complaints.show', compact('complaint'));
    }

    public function destroy(Complaint $complaint)
    {
        abort_if(Gate::denies('complaint_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $complaint->delete();

        return back();
    }

    public function massDestroy(MassDestroyComplaintRequest $request)
    {
        Complaint::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('complaint_create') && Gate::denies('complaint_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Complaint();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
