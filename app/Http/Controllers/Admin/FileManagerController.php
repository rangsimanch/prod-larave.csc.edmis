<?php

namespace App\Http\Controllers\Admin;

use App\ConstructionContract;
use App\FileManager;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyFileManagerRequest;
use App\Http\Requests\StoreFileManagerRequest;
use App\Http\Requests\UpdateFileManagerRequest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class FileManagerController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = FileManager::with(['construction_contract', 'team'])->select(sprintf('%s.*', (new FileManager)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'file_manager_show';
                $editGate      = 'file_manager_edit';
                $deleteGate    = 'file_manager_delete';
                $crudRoutePart = 'file-managers';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : "";
            });
            $table->editColumn('file_name', function ($row) {
                return $row->file_name ? $row->file_name : "";
            });
            $table->editColumn('code', function ($row) {
                return $row->code ? $row->code : "";
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
            $table->addColumn('construction_contract_code', function ($row) {
                return $row->construction_contract ? $row->construction_contract->code : '';
            });

            $table->editColumn('construction_contract.name', function ($row) {
                return $row->construction_contract ? (is_string($row->construction_contract) ? $row->construction_contract : $row->construction_contract->name) : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'file_upload', 'construction_contract']);

            return $table->make(true);
        }

        return view('admin.fileManagers.index');
    }

    public function create()
    {
        abort_if(Gate::denies('file_manager_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.fileManagers.create', compact('construction_contracts'));
    }

    public function store(StoreFileManagerRequest $request)
    {
        $fileManager = FileManager::create($request->all());

        foreach ($request->input('file_upload', []) as $file) {
            $fileManager->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
        }

        return redirect()->route('admin.file-managers.index');
    }

    public function edit(FileManager $fileManager)
    {
        abort_if(Gate::denies('file_manager_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $fileManager->load('construction_contract', 'team');

        return view('admin.fileManagers.edit', compact('construction_contracts', 'fileManager'));
    }

    public function update(UpdateFileManagerRequest $request, FileManager $fileManager)
    {
        $fileManager->update($request->all());

        if (count($fileManager->file_upload) > 0) {
            foreach ($fileManager->file_upload as $media) {
                if (!in_array($media->file_name, $request->input('file_upload', []))) {
                    $media->delete();
                }
            }
        }

        $media = $fileManager->file_upload->pluck('file_name')->toArray();

        foreach ($request->input('file_upload', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $fileManager->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
            }
        }

        return redirect()->route('admin.file-managers.index');
    }

    public function show(FileManager $fileManager)
    {
        abort_if(Gate::denies('file_manager_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $fileManager->load('construction_contract', 'team');

        return view('admin.fileManagers.show', compact('fileManager'));
    }

    public function destroy(FileManager $fileManager)
    {
        abort_if(Gate::denies('file_manager_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $fileManager->delete();

        return back();
    }

    public function massDestroy(MassDestroyFileManagerRequest $request)
    {
        FileManager::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
