<?php

namespace App\Http\Controllers\Admin;

use App\ConstructionContract;
use App\DailyRequest;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyDailyRequestRequest;
use App\Http\Requests\StoreDailyRequestRequest;
use App\Http\Requests\UpdateDailyRequestRequest;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

use Illuminate\Support\Facades\Auth;


class DailyRequestController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('daily_request_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = DailyRequest::with(['receive_by', 'constuction_contract'])->select(sprintf('%s.*', (new DailyRequest)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'daily_request_show';
                $editGate      = 'daily_request_edit';
                $deleteGate    = 'daily_request_delete';
                $crudRoutePart = 'daily-requests';

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
            $table->addColumn('receive_by_name', function ($row) {
                return $row->receive_by ? $row->receive_by->name : '';
            });

            $table->addColumn('constuction_contract_code', function ($row) {
                return $row->constuction_contract ? $row->constuction_contract->code : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'documents', 'receive_by', 'constuction_contract']);

            return $table->make(true);
        }

        return view('admin.dailyRequests.index');
    }

    public function create()
    {
        abort_if(Gate::denies('daily_request_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        //Contract Check
            //Check is Admin
            if(Auth::id() != 1){
                $constuction_contracts = ConstructionContract::where('id',session('constuction_contract_id'))->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');
            }
            else{
                $constuction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');
            }

        return view('admin.dailyRequests.create', compact('constuction_contracts'));
    }

    public function store(StoreDailyRequestRequest $request)
    {
        $data = $request->all();
        $data['receive_by_id'] = auth()->id();
        $dailyRequest = DailyRequest::create($data);
        foreach ($request->input('documents', []) as $file) {
            $dailyRequest->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('documents');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $dailyRequest->id]);
        }

        return redirect()->route('admin.daily-requests.index');
    }

    public function edit(DailyRequest $dailyRequest)
    {
        abort_if(Gate::denies('daily_request_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        //Contract Check
            //Check is Admin
            if(Auth::id() != 1){
                $constuction_contracts = ConstructionContract::where('id',session('constuction_contract_id'))->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');
            }
            else{
                $constuction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');
            }

        $dailyRequest->load('receive_by', 'constuction_contract');

        return view('admin.dailyRequests.edit', compact('constuction_contracts', 'dailyRequest'));
    }

    public function update(UpdateDailyRequestRequest $request, DailyRequest $dailyRequest)
    {
        $dailyRequest->update($request->all());

        if (count($dailyRequest->documents) > 0) {
            foreach ($dailyRequest->documents as $media) {
                if (!in_array($media->file_name, $request->input('documents', []))) {
                    $media->delete();
                }
            }
        }

        $media = $dailyRequest->documents->pluck('file_name')->toArray();

        foreach ($request->input('documents', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $dailyRequest->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('documents');
            }
        }

        return redirect()->route('admin.daily-requests.index');
    }

    public function show(DailyRequest $dailyRequest)
    {
        abort_if(Gate::denies('daily_request_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dailyRequest->load('receive_by', 'constuction_contract');

        return view('admin.dailyRequests.show', compact('dailyRequest'));
    }

    public function destroy(DailyRequest $dailyRequest)
    {
        abort_if(Gate::denies('daily_request_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dailyRequest->delete();

        return back();
    }

    public function massDestroy(MassDestroyDailyRequestRequest $request)
    {
        DailyRequest::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('daily_request_create') && Gate::denies('daily_request_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new DailyRequest();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
