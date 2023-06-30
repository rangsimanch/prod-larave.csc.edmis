<?php

namespace App\Http\Controllers\Admin;

use App\CloseOutMain;
use App\ConstructionContract;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyCloseOutMainRequest;
use App\Http\Requests\StoreCloseOutMainRequest;
use App\Http\Requests\UpdateCloseOutMainRequest;
use App\Team;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class CloseOutMainController extends Controller
{
    use MediaUploadingTrait;
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('close_out_main_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = CloseOutMain::with(['construction_contract', 'team'])->select(sprintf('%s.*', (new CloseOutMain())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'close_out_main_show';
                $editGate = 'close_out_main_edit';
                $deleteGate = 'close_out_main_delete';
                $crudRoutePart = 'close-out-mains';

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

            $table->editColumn('chapter_no', function ($row) {
                return $row->chapter_no ? $row->chapter_no : '';
            });
            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : '';
            });
            $table->editColumn('final_file', function ($row) {
                if (!$row->final_file) {
                    return '';
                }
                $links = [];
                foreach ($row->final_file as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });

            $table->rawColumns(['actions', 'placeholder', 'construction_contract', 'final_file']);

            return $table->make(true);
        }

        $construction_contracts = ConstructionContract::get();
        $teams                  = Team::get();

        return view('admin.closeOutMains.index', compact('construction_contracts', 'teams'));
    }

    public function create()
    {
        abort_if(Gate::denies('close_out_main_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $construction_contracts = ConstructionContract::pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.closeOutMains.create', compact('construction_contracts'));
    }

    public function store(StoreCloseOutMainRequest $request)
    {
        $closeOutMain = CloseOutMain::create($request->all());

        foreach ($request->input('final_file', []) as $file) {
            $closeOutMain->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('final_file');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $closeOutMain->id]);
        }

        return redirect()->route('admin.close-out-mains.index');
    }

    public function edit(CloseOutMain $closeOutMain)
    {
        abort_if(Gate::denies('close_out_main_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $construction_contracts = ConstructionContract::pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $closeOutMain->load('construction_contract', 'team');

        return view('admin.closeOutMains.edit', compact('closeOutMain', 'construction_contracts'));
    }

    public function update(UpdateCloseOutMainRequest $request, CloseOutMain $closeOutMain)
    {
        $closeOutMain->update($request->all());

        if (count($closeOutMain->final_file) > 0) {
            foreach ($closeOutMain->final_file as $media) {
                if (!in_array($media->file_name, $request->input('final_file', []))) {
                    $media->delete();
                }
            }
        }
        $media = $closeOutMain->final_file->pluck('file_name')->toArray();
        foreach ($request->input('final_file', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $closeOutMain->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('final_file');
            }
        }

        return redirect()->route('admin.close-out-mains.index');
    }

    public function show(CloseOutMain $closeOutMain)
    {
        abort_if(Gate::denies('close_out_main_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $closeOutMain->load('construction_contract', 'team');

        return view('admin.closeOutMains.show', compact('closeOutMain'));
    }

    public function destroy(CloseOutMain $closeOutMain)
    {
        abort_if(Gate::denies('close_out_main_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $closeOutMain->delete();

        return back();
    }

    public function massDestroy(MassDestroyCloseOutMainRequest $request)
    {
        CloseOutMain::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('close_out_main_create') && Gate::denies('close_out_main_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new CloseOutMain();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
