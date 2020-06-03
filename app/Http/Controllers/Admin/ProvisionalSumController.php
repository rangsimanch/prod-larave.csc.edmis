<?php

namespace App\Http\Controllers\Admin;

use App\ConstructionContract;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyProvisionalSumRequest;
use App\Http\Requests\StoreProvisionalSumRequest;
use App\Http\Requests\UpdateProvisionalSumRequest;
use App\ProvisionalSum;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Team;


class ProvisionalSumController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('provisional_sum_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ProvisionalSum::with(['construction_contract', 'team'])->select(sprintf('%s.*', (new ProvisionalSum)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'provisional_sum_show';
                $editGate      = 'provisional_sum_edit';
                $deleteGate    = 'provisional_sum_delete';
                $crudRoutePart = 'provisional-sums';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('bill_no', function ($row) {
                return $row->bill_no ? $row->bill_no : "";
            });
            $table->editColumn('item_no', function ($row) {
                return $row->item_no ? $row->item_no : "";
            });
            $table->addColumn('construction_contract_code', function ($row) {
                return $row->construction_contract ? $row->construction_contract->code : '';
            });

            $table->editColumn('name_of_ps', function ($row) {
                return $row->name_of_ps ? $row->name_of_ps : "";
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

        $construction_contracts = ConstructionContract::get()->pluck('code')->toArray();
        $teams                  = Team::get()->pluck('name')->toArray();

        return view('admin.provisionalSums.index', compact('construction_contracts', 'teams'));
    }

    public function create()
    {
        abort_if(Gate::denies('provisional_sum_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.provisionalSums.create', compact('construction_contracts'));
    }

    public function store(StoreProvisionalSumRequest $request)
    {
        $provisionalSum = ProvisionalSum::create($request->all());

        foreach ($request->input('file_upload', []) as $file) {
            $provisionalSum->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $provisionalSum->id]);
        }

        return redirect()->route('admin.provisional-sums.index');
    }

    public function edit(ProvisionalSum $provisionalSum)
    {
        abort_if(Gate::denies('provisional_sum_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $provisionalSum->load('construction_contract', 'team');

        return view('admin.provisionalSums.edit', compact('construction_contracts', 'provisionalSum'));
    }

    public function update(UpdateProvisionalSumRequest $request, ProvisionalSum $provisionalSum)
    {
        $provisionalSum->update($request->all());

        if (count($provisionalSum->file_upload) > 0) {
            foreach ($provisionalSum->file_upload as $media) {
                if (!in_array($media->file_name, $request->input('file_upload', []))) {
                    $media->delete();
                }
            }
        }

        $media = $provisionalSum->file_upload->pluck('file_name')->toArray();

        foreach ($request->input('file_upload', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $provisionalSum->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
            }
        }

        return redirect()->route('admin.provisional-sums.index');
    }

    public function show(ProvisionalSum $provisionalSum)
    {
        abort_if(Gate::denies('provisional_sum_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $provisionalSum->load('construction_contract', 'team');

        return view('admin.provisionalSums.show', compact('provisionalSum'));
    }

    public function destroy(ProvisionalSum $provisionalSum)
    {
        abort_if(Gate::denies('provisional_sum_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $provisionalSum->delete();

        return back();
    }

    public function massDestroy(MassDestroyProvisionalSumRequest $request)
    {
        ProvisionalSum::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('provisional_sum_create') && Gate::denies('provisional_sum_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new ProvisionalSum();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
