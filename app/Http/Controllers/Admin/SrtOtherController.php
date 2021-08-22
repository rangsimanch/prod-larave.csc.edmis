<?php

namespace App\Http\Controllers\Admin;

use App\ConstructionContract;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroySrtOtherRequest;
use App\Http\Requests\StoreSrtOtherRequest;
use App\Http\Requests\UpdateSrtOtherRequest;
use App\SrtDocumentStatus;
use App\SrtOther;
use App\Team;
use App\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class SrtOtherController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('srt_other_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = SrtOther::with(['docuement_status', 'constuction_contract', 'from', 'tos', 'close_by', 'team'])->select(sprintf('%s.*', (new SrtOther())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'srt_other_show';
                $editGate = 'srt_other_edit';
                $deleteGate = 'srt_other_delete';
                $crudRoutePart = 'srt-others';

                return view('partials.datatablesActions', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'row'
            ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->addColumn('docuement_status_title', function ($row) {
                return $row->docuement_status ? $row->docuement_status->title : '';
            });

            $table->addColumn('constuction_contract_code', function ($row) {
                return $row->constuction_contract ? $row->constuction_contract->code : '';
            });

            $table->editColumn('document_type', function ($row) {
                return $row->document_type ? SrtOther::DOCUMENT_TYPE_SELECT[$row->document_type] : '';
            });
            $table->editColumn('document_number', function ($row) {
                return $row->document_number ? $row->document_number : '';
            });
            $table->editColumn('subject', function ($row) {
                return $row->subject ? $row->subject : '';
            });
            $table->editColumn('refer_to', function ($row) {
                return $row->refer_to ? $row->refer_to : '';
            });
            $table->editColumn('speed_class', function ($row) {
                return $row->speed_class ? SrtOther::SPEED_CLASS_SELECT[$row->speed_class] : '';
            });
            $table->editColumn('objective', function ($row) {
                return $row->objective ? SrtOther::OBJECTIVE_SELECT[$row->objective] : '';
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
            $table->editColumn('save_for', function ($row) {
                return $row->save_for ? SrtOther::SAVE_FOR_SELECT[$row->save_for] : '';
            });
            $table->addColumn('close_by_name', function ($row) {
                return $row->close_by ? $row->close_by->name : '';
            });

            $table->editColumn('to_text', function ($row) {
                return $row->to_text ? $row->to_text : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'docuement_status', 'constuction_contract', 'file_upload', 'close_by']);

            return $table->make(true);
        }

        $srt_document_statuses  = SrtDocumentStatus::get();
        $construction_contracts = ConstructionContract::get();
        $teams                  = Team::get();
        $users                  = User::get();

        return view('admin.srtOthers.index', compact('srt_document_statuses', 'construction_contracts', 'teams', 'users'));
    }

    public function create()
    {
        abort_if(Gate::denies('srt_other_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $constuction_contracts = ConstructionContract::pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $froms = Team::pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tos = User::pluck('name', 'id');

        $close_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.srtOthers.create', compact('constuction_contracts', 'froms', 'tos', 'close_bies'));
    }

    public function store(StoreSrtOtherRequest $request)
    {
        $srtOther = SrtOther::create($request->all());
        $srtOther->tos()->sync($request->input('tos', []));
        foreach ($request->input('file_upload', []) as $file) {
            $srtOther->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('file_upload');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $srtOther->id]);
        }

        return redirect()->route('admin.srt-others.index');
    }

    public function edit(SrtOther $srtOther)
    {
        abort_if(Gate::denies('srt_other_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $constuction_contracts = ConstructionContract::pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $froms = Team::pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tos = User::pluck('name', 'id');

        $close_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $srtOther->load('docuement_status', 'constuction_contract', 'from', 'tos', 'close_by', 'team');

        return view('admin.srtOthers.edit', compact('constuction_contracts', 'froms', 'tos', 'close_bies', 'srtOther'));
    }

    public function update(UpdateSrtOtherRequest $request, SrtOther $srtOther)
    {
        $srtOther->update($request->all());
        $srtOther->tos()->sync($request->input('tos', []));
        if (count($srtOther->file_upload) > 0) {
            foreach ($srtOther->file_upload as $media) {
                if (!in_array($media->file_name, $request->input('file_upload', []))) {
                    $media->delete();
                }
            }
        }
        $media = $srtOther->file_upload->pluck('file_name')->toArray();
        foreach ($request->input('file_upload', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $srtOther->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('file_upload');
            }
        }

        return redirect()->route('admin.srt-others.index');
    }

    public function show(SrtOther $srtOther)
    {
        abort_if(Gate::denies('srt_other_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $srtOther->load('docuement_status', 'constuction_contract', 'from', 'tos', 'close_by', 'team');

        return view('admin.srtOthers.show', compact('srtOther'));
    }

    public function destroy(SrtOther $srtOther)
    {
        abort_if(Gate::denies('srt_other_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $srtOther->delete();

        return back();
    }

    public function massDestroy(MassDestroySrtOtherRequest $request)
    {
        SrtOther::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('srt_other_create') && Gate::denies('srt_other_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new SrtOther();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
