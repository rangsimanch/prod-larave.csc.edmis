<?php

namespace App\Http\Controllers\Admin;

use App\ConstructionContract;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyRfaRequest;
use App\Http\Requests\StoreRfaRequest;
use App\Http\Requests\UpdateRfaRequest;
use App\Rfa;
use App\RfaCommentStatus;
use App\RfaDocumentStatus;
use App\Rfatype;
use App\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class RfaController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Rfa::with(['type', 'issueby', 'assign', 'create_by', 'action_by', 'comment_by', 'information_by', 'comment_status', 'for_status', 'document_status', 'construction_contracts', 'team'])->select(sprintf('%s.*', (new Rfa)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'rfa_show';
                $editGate      = 'rfa_edit';
                $deleteGate    = 'rfa_delete';
                $crudRoutePart = 'rfas';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : "";
            });
            $table->editColumn('document_number', function ($row) {
                return $row->document_number ? $row->document_number : "";
            });
            $table->editColumn('rfa_code', function ($row) {
                return $row->rfa_code ? $row->rfa_code : "";
            });
            $table->addColumn('type_type_name', function ($row) {
                return $row->type ? $row->type->type_name : '';
            });

            $table->editColumn('type.type_code', function ($row) {
                return $row->type ? (is_string($row->type) ? $row->type : $row->type->type_code) : '';
            });

            $table->addColumn('issueby_name', function ($row) {
                return $row->issueby ? $row->issueby->name : '';
            });

            $table->addColumn('assign_name', function ($row) {
                return $row->assign ? $row->assign->name : '';
            });

            $table->editColumn('file_upload_1', function ($row) {
                if (!$row->file_upload_1) {
                    return '';
                }

                $links = [];

                foreach ($row->file_upload_1 as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });
            $table->addColumn('create_by_name', function ($row) {
                return $row->create_by ? $row->create_by->name : '';
            });

            $table->addColumn('action_by_name', function ($row) {
                return $row->action_by ? $row->action_by->name : '';
            });

            $table->addColumn('comment_by_name', function ($row) {
                return $row->comment_by ? $row->comment_by->name : '';
            });

            $table->addColumn('information_by_name', function ($row) {
                return $row->information_by ? $row->information_by->name : '';
            });

            $table->addColumn('comment_status_name', function ($row) {
                return $row->comment_status ? $row->comment_status->name : '';
            });

            $table->editColumn('note_3', function ($row) {
                return $row->note_3 ? $row->note_3 : "";
            });
            $table->addColumn('for_status_name', function ($row) {
                return $row->for_status ? $row->for_status->name : '';
            });

            $table->addColumn('document_status_status_name', function ($row) {
                return $row->document_status ? $row->document_status->status_name : '';
            });

            $table->editColumn('construction_contract', function ($row) {
                $labels = [];

                foreach ($row->construction_contracts as $construction_contract) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $construction_contract->code);
                }

                return implode(' ', $labels);
            });

            $table->rawColumns(['actions', 'placeholder', 'type', 'issueby', 'assign', 'file_upload_1', 'create_by', 'action_by', 'comment_by', 'information_by', 'comment_status', 'for_status', 'document_status', 'construction_contract']);

            return $table->make(true);
        }

        return view('admin.rfas.index');
    }

    public function create()
    {
        abort_if(Gate::denies('rfa_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $types = Rfatype::all()->pluck('type_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $issuebies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $assigns = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $create_bies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $action_bies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $comment_bies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $information_bies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $comment_statuses = RfaCommentStatus::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $for_statuses = RfaCommentStatus::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $document_statuses = RfaDocumentStatus::all()->pluck('status_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id');

        return view('admin.rfas.create', compact('types', 'issuebies', 'assigns', 'create_bies', 'action_bies', 'comment_bies', 'information_bies', 'comment_statuses', 'for_statuses', 'document_statuses', 'construction_contracts'));
    }

    public function store(StoreRfaRequest $request)
    {
        $rfa = Rfa::create($request->all());
        $rfa->construction_contracts()->sync($request->input('construction_contracts', []));

        foreach ($request->input('file_upload_1', []) as $file) {
            $rfa->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload_1');
        }

        return redirect()->route('admin.rfas.index');
    }

    public function edit(Rfa $rfa)
    {
        abort_if(Gate::denies('rfa_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $types = Rfatype::all()->pluck('type_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $issuebies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $assigns = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $create_bies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $action_bies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $comment_bies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $information_bies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $comment_statuses = RfaCommentStatus::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $for_statuses = RfaCommentStatus::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $document_statuses = RfaDocumentStatus::all()->pluck('status_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id');

        $rfa->load('type', 'issueby', 'assign', 'create_by', 'action_by', 'comment_by', 'information_by', 'comment_status', 'for_status', 'document_status', 'construction_contracts', 'team');

        return view('admin.rfas.edit', compact('types', 'issuebies', 'assigns', 'create_bies', 'action_bies', 'comment_bies', 'information_bies', 'comment_statuses', 'for_statuses', 'document_statuses', 'construction_contracts', 'rfa'));
    }

    public function update(UpdateRfaRequest $request, Rfa $rfa)
    {
        $rfa->update($request->all());
        $rfa->construction_contracts()->sync($request->input('construction_contracts', []));

        if (count($rfa->file_upload_1) > 0) {
            foreach ($rfa->file_upload_1 as $media) {
                if (!in_array($media->file_name, $request->input('file_upload_1', []))) {
                    $media->delete();
                }
            }
        }

        $media = $rfa->file_upload_1->pluck('file_name')->toArray();

        foreach ($request->input('file_upload_1', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $rfa->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload_1');
            }
        }

        return redirect()->route('admin.rfas.index');
    }

    public function show(Rfa $rfa)
    {
        abort_if(Gate::denies('rfa_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rfa->load('type', 'issueby', 'assign', 'create_by', 'action_by', 'comment_by', 'information_by', 'comment_status', 'for_status', 'document_status', 'construction_contracts', 'team');

        return view('admin.rfas.show', compact('rfa'));
    }

    public function destroy(Rfa $rfa)
    {
        abort_if(Gate::denies('rfa_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rfa->delete();

        return back();
    }

    public function massDestroy(MassDestroyRfaRequest $request)
    {
        Rfa::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
