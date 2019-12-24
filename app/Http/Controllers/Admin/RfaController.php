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
            $query = Rfa::with(['type', 'construction_contract', 'issueby', 'assign', 'comment_by', 'information_by', 'comment_status', 'for_status', 'document_status', 'create_by_user', 'update_by_user', 'approve_by_user', 'team'])->select(sprintf('%s.*', (new Rfa)->table));
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
            $table->addColumn('construction_contract_code', function ($row) {
                return $row->construction_contract ? $row->construction_contract->code : '';
            });

            $table->editColumn('construction_contract.name', function ($row) {
                return $row->construction_contract ? (is_string($row->construction_contract) ? $row->construction_contract : $row->construction_contract->name) : '';
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
            $table->addColumn('comment_by_name', function ($row) {
                return $row->comment_by ? $row->comment_by->name : '';
            });

            $table->addColumn('information_by_name', function ($row) {
                return $row->information_by ? $row->information_by->name : '';
            });

            $table->editColumn('note_2', function ($row) {
                return $row->note_2 ? $row->note_2 : "";
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

            $table->addColumn('create_by_user_name', function ($row) {
                return $row->create_by_user ? $row->create_by_user->name : '';
            });

            $table->addColumn('update_by_user_name', function ($row) {
                return $row->update_by_user ? $row->update_by_user->name : '';
            });

            $table->addColumn('approve_by_user_name', function ($row) {
                return $row->approve_by_user ? $row->approve_by_user->name : '';
            });

            $table->addColumn('team_name', function ($row) {
                return $row->team ? $row->team->name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'type', 'construction_contract', 'issueby', 'assign', 'file_upload_1', 'comment_by', 'information_by', 'comment_status', 'for_status', 'document_status', 'create_by_user', 'update_by_user', 'approve_by_user', 'team']);

            return $table->make(true);
        }

        return view('admin.rfas.index');
    }

    public function create()
    {
        abort_if(Gate::denies('rfa_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $types = Rfatype::all()->pluck('type_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $issuebies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $assigns = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $comment_bies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $information_bies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $comment_statuses = RfaCommentStatus::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $for_statuses = RfaCommentStatus::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.rfas.create', compact('types', 'construction_contracts', 'issuebies', 'assigns', 'comment_bies', 'information_bies', 'comment_statuses', 'for_statuses'));
    }

    public function store(StoreRfaRequest $request)
    {   $data = $request->all();
        $data['create_by_user_id'] = auth()->id();
        $data['document_status_id'] = 2;
        
        

        $rfa = Rfa::create($data);

        foreach ($request->input('file_upload_1', []) as $file) {
            $rfa->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload_1');
        }

        return redirect()->route('admin.rfas.index');
    }

    public function edit(Rfa $rfa)
    {
        abort_if(Gate::denies('rfa_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $types = Rfatype::all()->pluck('type_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $issuebies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $assigns = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $comment_bies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $information_bies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $comment_statuses = RfaCommentStatus::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $for_statuses = RfaCommentStatus::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        

        $rfa->load('type', 'construction_contract', 'issueby', 'assign', 'comment_by', 'information_by', 'comment_status', 'for_status', 'document_status', 'create_by_user', 'update_by_user', 'approve_by_user', 'team');

        return view('admin.rfas.edit', compact('types', 'construction_contracts', 'issuebies', 'assigns', 'comment_bies', 'information_bies', 'comment_statuses', 'for_statuses', 'rfa'));
    }

    public function update(UpdateRfaRequest $request, Rfa $rfa)
    {
        if($request->comment_status_id != null){
            $rfa['document_status_id'] = 3;
            $rfa['update_by_user_id'] = auth()->id();
            
            if($request->for_status_id != null){
                $rfa['document_status_id'] = 4;
                $rfa['approve_by_user_id'] = auth()->id();
            }
        }  

        


        $rfa->update($request->all());

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

        $rfa->load('type', 'construction_contract', 'issueby', 'assign', 'comment_by', 'information_by', 'comment_status', 'for_status', 'document_status', 'create_by_user', 'update_by_user', 'approve_by_user', 'team');

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
