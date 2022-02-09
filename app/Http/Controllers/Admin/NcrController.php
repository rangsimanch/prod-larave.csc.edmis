<?php

namespace App\Http\Controllers\Admin;

use App\ConstructionContract;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyNcrRequest;
use App\Http\Requests\StoreNcrRequest;
use App\Http\Requests\UpdateNcrRequest;
use App\Ncn;
use App\Ncr;
use App\Team;
use App\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class NcrController extends Controller
{
    use MediaUploadingTrait;
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('ncr_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Ncr::with(['construction_contract', 'corresponding_ncn', 'prepared_by', 'contractor_manager', 'issue_by', 'construction_specialist', 'related_specialist', 'leader', 'team'])->select(sprintf('%s.*', (new Ncr())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'ncr_show';
                $editGate = 'ncr_edit';
                $deleteGate = 'ncr_delete';
                $crudRoutePart = 'ncrs';

                return view('partials.datatablesActions', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'row'
            ));
            });

            $table->addColumn('construction_contract_code', function ($row) {
                return $row->construction_contract ? $row->construction_contract->code : '';
            });

            // $table->addColumn('corresponding_ncn_document_number', function ($row) {
            //     return $row->corresponding_ncn ? $row->corresponding_ncn->document_number : '';
            // });

            $table->editColumn('document_number', function ($row) {
                return $row->document_number ? $row->document_number : '';
            });

            $table->editColumn('file_attachment', function ($row) {
                if (!$row->file_attachment) {
                    return '';
                }
                $links = [];
                foreach ($row->file_attachment as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });
            $table->addColumn('prepared_by_name', function ($row) {
                return $row->prepared_by ? $row->prepared_by->name : '';
            });

            $table->addColumn('contractor_manager_name', function ($row) {
                return $row->contractor_manager ? $row->contractor_manager->name : '';
            });

            $table->editColumn('documents_status', function ($row) {
                if (Ncr::DOCUMENTS_STATUS_SELECT[$row->documents_status] == 'New'){
                    return sprintf('<p style="color:#003399"><b>%s</b></p>',$row->documents_status ? Ncr::DOCUMENTS_STATUS_SELECT[$row->documents_status] : '');
                }
                else if(Ncr::DOCUMENTS_STATUS_SELECT[$row->documents_status] == 'Reply'){
                    return sprintf('<p style="color:#ff9900"><b>%s</b></p>',$row->documents_status ? Ncr::DOCUMENTS_STATUS_SELECT[$row->documents_status] : '');
                }
                else if(Ncr::DOCUMENTS_STATUS_SELECT[$row->documents_status] == 'Accepted and Closed case.'){
                    return sprintf('<p style="color:#28B463"><b>%s</b></p>',$row->documents_status ? Ncr::DOCUMENTS_STATUS_SELECT[$row->documents_status] : '');
                }
                else if(Ncr::DOCUMENTS_STATUS_SELECT[$row->documents_status] == 'Rejected and need further action.'){
                    return sprintf('<p style="color:#E74C3C"><b>%s</b></p>',$row->documents_status ? Ncr::DOCUMENTS_STATUS_SELECT[$row->documents_status] : '');
                }
                else{
                    return $row->documents_status ? Ncr::DOCUMENTS_STATUS_SELECT[$row->documents_status] : '';
                }
            });
            $table->addColumn('issue_by_name', function ($row) {
                return $row->issue_by ? $row->issue_by->name : '';
            });

            $table->addColumn('construction_specialist_name', function ($row) {
                return $row->construction_specialist ? $row->construction_specialist->name : '';
            });

            $table->addColumn('related_specialist_name', function ($row) {
                return $row->related_specialist ? $row->related_specialist->name : '';
            });

            $table->addColumn('leader_name', function ($row) {
                return $row->leader ? $row->leader->name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'construction_contract', 'file_attachment', 'prepared_by', 'contractor_manager', 'issue_by', 'construction_specialist', 'related_specialist', 'leader', 'documents_status']);

            return $table->make(true);
        }

        $construction_contracts = ConstructionContract::get();
        $ncns                   = Ncn::get();
        $users                  = User::get();
        $teams                  = Team::get();

        return view('admin.ncrs.index', compact('construction_contracts', 'ncns', 'users', 'teams'));
    }

    public function create()
    {
        abort_if(Gate::denies('ncr_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if(Auth::id() != 1){
            $construction_contracts = ConstructionContract::where('id',session('construction_contract_id'))->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');
        }
        else{
            $construction_contracts = ConstructionContract::pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');
        }
        if(Auth::id() != 1){
            $corresponding_ncns = Ncn::where('construction_contract_id', session('construction_contract_id'))
            ->where('documents_status', '1')
            ->pluck('document_number', 'id')->prepend(trans('global.pleaseSelect'), '');
        }   
        else{
            $corresponding_ncns = Ncn::where('documents_status', '1')->pluck('document_number', 'id')->prepend(trans('global.pleaseSelect'), '');
        }

        $prepared_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $contractor_managers = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $issue_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $construction_specialists = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $related_specialists = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $leaders = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.ncrs.create', compact('construction_contracts', 'construction_specialists', 'contractor_managers', 'corresponding_ncns', 'issue_bies', 'leaders', 'prepared_bies', 'related_specialists'));
    }

    public function store(StoreNcrRequest $request)
    {
        $data = $request->all();
        $ncnNumber = Ncn::where("id",$data['corresponding_ncn_id'])->value('document_number');
        $data['document_number'] = str_replace("NCN", "NCR", $ncnNumber);
        $data['documents_status'] = '2';

        $ncr = Ncr::create($data);

        $ncn = Ncn::where("id",$data['corresponding_ncn_id'])->update(['documents_status' => '2']);
        
        foreach ($request->input('rootcase_image', []) as $file) {
            $ncr->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('rootcase_image');
        }

        foreach ($request->input('containment_image', []) as $file) {
            $ncr->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('containment_image');
        }

        foreach ($request->input('corrective_image', []) as $file) {
            $ncr->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('corrective_image');
        }

        foreach ($request->input('file_attachment', []) as $file) {
            $ncr->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('file_attachment');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $ncr->id]);
        }

        return redirect()->route('admin.ncrs.index');
    }

    public function edit(Ncr $ncr)
    {
        abort_if(Gate::denies('ncr_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if(Auth::id() != 1){
            $construction_contracts = ConstructionContract::where('id',session('construction_contract_id'))->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');
        }
        else{
            $construction_contracts = ConstructionContract::pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');
        }

        if(Auth::id() != 1){
            $corresponding_ncns = Ncn::where('construction_contract_id', session('construction_contract_id'))->pluck('document_number', 'id')->prepend(trans('global.pleaseSelect'), '');
        }   
        else{
            $corresponding_ncns = Ncn::pluck('document_number', 'id')->prepend(trans('global.pleaseSelect'), '');
        }

        $prepared_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $contractor_managers = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $issue_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $construction_specialists = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $related_specialists = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $leaders = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $ncr->load('construction_contract', 'corresponding_ncn', 'prepared_by', 'contractor_manager', 'issue_by', 'construction_specialist', 'related_specialist', 'leader', 'team');

        return view('admin.ncrs.edit', compact('construction_contracts', 'construction_specialists', 'contractor_managers', 'corresponding_ncns', 'issue_bies', 'leaders', 'ncr', 'prepared_bies', 'related_specialists'));
    }

    public function update(UpdateNcrRequest $request, Ncr $ncr)
    {
        $data = $request->all();

        $ncr->update($request->all());
        $ncn = Ncn::where("id",$ncr->corresponding_ncn_id)->update(['documents_status' => $data['documents_status']]);


        if (count($ncr->rootcase_image) > 0) {
            foreach ($ncr->rootcase_image as $media) {
                if (!in_array($media->file_name, $request->input('rootcase_image', []))) {
                    $media->delete();
                }
            }
        }
        $media = $ncr->rootcase_image->pluck('file_name')->toArray();
        foreach ($request->input('rootcase_image', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $ncr->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('rootcase_image');
            }
        }

        if (count($ncr->containment_image) > 0) {
            foreach ($ncr->containment_image as $media) {
                if (!in_array($media->file_name, $request->input('containment_image', []))) {
                    $media->delete();
                }
            }
        }
        $media = $ncr->containment_image->pluck('file_name')->toArray();
        foreach ($request->input('containment_image', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $ncr->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('containment_image');
            }
        }

        if (count($ncr->corrective_image) > 0) {
            foreach ($ncr->corrective_image as $media) {
                if (!in_array($media->file_name, $request->input('corrective_image', []))) {
                    $media->delete();
                }
            }
        }
        $media = $ncr->corrective_image->pluck('file_name')->toArray();
        foreach ($request->input('corrective_image', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $ncr->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('corrective_image');
            }
        }

        if (count($ncr->file_attachment) > 0) {
            foreach ($ncr->file_attachment as $media) {
                if (!in_array($media->file_name, $request->input('file_attachment', []))) {
                    $media->delete();
                }
            }
        }
        $media = $ncr->file_attachment->pluck('file_name')->toArray();
        foreach ($request->input('file_attachment', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $ncr->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('file_attachment');
            }
        }

        return redirect()->route('admin.ncrs.index');
    }

    public function show(Ncr $ncr)
    {
        abort_if(Gate::denies('ncr_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ncr->load('construction_contract', 'corresponding_ncn', 'prepared_by', 'contractor_manager', 'issue_by', 'construction_specialist', 'related_specialist', 'leader', 'team');

        return view('admin.ncrs.show', compact('ncr'));
    }

    public function destroy(Ncr $ncr)
    {
        abort_if(Gate::denies('ncr_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ncr->delete();

        return back();
    }

    public function massDestroy(MassDestroyNcrRequest $request)
    {
        Ncr::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('ncr_create') && Gate::denies('ncr_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Ncr();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
