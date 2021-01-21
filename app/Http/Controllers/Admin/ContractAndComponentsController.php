<?php

namespace App\Http\Controllers\Admin;

use App\ConstructionContract;
use App\ContractAndComponent;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyContractAndComponentRequest;
use App\Http\Requests\StoreContractAndComponentRequest;
use App\Http\Requests\UpdateContractAndComponentRequest;
use App\Team;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ContractAndComponentsController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('contract_and_component_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ContractAndComponent::with(['construction_contract', 'team'])->select(sprintf('%s.*', (new ContractAndComponent)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'contract_and_component_show';
                $editGate      = 'contract_and_component_edit';
                $deleteGate    = 'contract_and_component_delete';
                $crudRoutePart = 'contract-and-components';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('document_name', function ($row) {
                return $row->document_name ? $row->document_name : "";
            });
            $table->editColumn('document_code', function ($row) {
                return $row->document_code ? $row->document_code : "";
            });
            $table->addColumn('construction_contract_code', function ($row) {
                return $row->construction_contract ? $row->construction_contract->code : '';
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

            $table->rawColumns(['actions', 'placeholder', 'construction_contract', 'file_upload']);

            return $table->make(true);
        }

        $construction_contracts = ConstructionContract::get();
        $teams                  = Team::get();

        return view('admin.contractAndComponents.index', compact('construction_contracts', 'teams'));
    }

    public function create()
    {
        abort_if(Gate::denies('contract_and_component_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.contractAndComponents.create', compact('construction_contracts'));
    }

    public function store(StoreContractAndComponentRequest $request)
    {
        $contractAndComponent = ContractAndComponent::create($request->all());

        foreach ($request->input('file_upload', []) as $file) {
            $contractAndComponent->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $contractAndComponent->id]);
        }

        return redirect()->route('admin.contract-and-components.index');
    }

    public function edit(ContractAndComponent $contractAndComponent)
    {
        abort_if(Gate::denies('contract_and_component_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $contractAndComponent->load('construction_contract', 'team');

        return view('admin.contractAndComponents.edit', compact('construction_contracts', 'contractAndComponent'));
    }

    public function update(UpdateContractAndComponentRequest $request, ContractAndComponent $contractAndComponent)
    {
        $contractAndComponent->update($request->all());

        if (count($contractAndComponent->file_upload) > 0) {
            foreach ($contractAndComponent->file_upload as $media) {
                if (!in_array($media->file_name, $request->input('file_upload', []))) {
                    $media->delete();
                }
            }
        }

        $media = $contractAndComponent->file_upload->pluck('file_name')->toArray();

        foreach ($request->input('file_upload', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $contractAndComponent->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
            }
        }

        return redirect()->route('admin.contract-and-components.index');
    }

    public function show(ContractAndComponent $contractAndComponent)
    {
        abort_if(Gate::denies('contract_and_component_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $contractAndComponent->load('construction_contract', 'team');

        return view('admin.contractAndComponents.show', compact('contractAndComponent'));
    }

    public function destroy(ContractAndComponent $contractAndComponent)
    {
        abort_if(Gate::denies('contract_and_component_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $contractAndComponent->delete();

        return back();
    }

    public function massDestroy(MassDestroyContractAndComponentRequest $request)
    {
        ContractAndComponent::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('contract_and_component_create') && Gate::denies('contract_and_component_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new ContractAndComponent();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
