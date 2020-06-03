<?php

namespace App\Http\Controllers\Admin;

use App\AddDrawing;
use App\DownloadSystemActivity;
use App\DownloadSystemWork;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyAddDrawingRequest;
use App\Http\Requests\StoreAddDrawingRequest;
use App\Http\Requests\UpdateAddDrawingRequest;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Team;


class AddDrawingController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('add_drawing_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = AddDrawing::with(['activity_type', 'work_type', 'team'])->select(sprintf('%s.*', (new AddDrawing)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'add_drawing_show';
                $editGate      = 'add_drawing_edit';
                $deleteGate    = 'add_drawing_delete';
                $crudRoutePart = 'add-drawings';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('drawing_title', function ($row) {
                return $row->drawing_title ? $row->drawing_title : "";
            });
            $table->addColumn('activity_type_activity_title', function ($row) {
                return $row->activity_type ? $row->activity_type->activity_title : '';
            });

            $table->addColumn('work_type_work_title', function ($row) {
                return $row->work_type ? $row->work_type->work_title : '';
            });

            $table->editColumn('coding_of_drawing', function ($row) {
                return $row->coding_of_drawing ? $row->coding_of_drawing : "";
            });
            $table->editColumn('location', function ($row) {
                return $row->location ? $row->location : "";
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

            $table->rawColumns(['actions', 'placeholder', 'activity_type', 'work_type', 'file_upload']);

            return $table->make(true);
        }

        $download_system_activities = DownloadSystemActivity::get()->pluck('activity_title')->toArray();
        $download_system_works      = DownloadSystemWork::get()->pluck('work_title')->toArray();
        $teams                      = Team::get()->pluck('name')->toArray();

        return view('admin.addDrawings.index', compact('download_system_activities', 'download_system_works', 'teams'));
    }

    public function create()
    {
        abort_if(Gate::denies('add_drawing_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $activity_types = DownloadSystemActivity::all()->pluck('activity_title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $work_types = DownloadSystemWork::all()->pluck('work_title', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.addDrawings.create', compact('activity_types', 'work_types'));
    }

    public function store(StoreAddDrawingRequest $request)
    {
        $addDrawing = AddDrawing::create($request->all());

        foreach ($request->input('file_upload', []) as $file) {
            $addDrawing->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $addDrawing->id]);
        }

        return redirect()->route('admin.add-drawings.index');
    }

    public function edit(AddDrawing $addDrawing)
    {
        abort_if(Gate::denies('add_drawing_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $activity_types = DownloadSystemActivity::all()->pluck('activity_title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $work_types = DownloadSystemWork::all()->pluck('work_title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $addDrawing->load('activity_type', 'work_type', 'team');

        return view('admin.addDrawings.edit', compact('activity_types', 'work_types', 'addDrawing'));
    }

    public function update(UpdateAddDrawingRequest $request, AddDrawing $addDrawing)
    {
        $addDrawing->update($request->all());

        if (count($addDrawing->file_upload) > 0) {
            foreach ($addDrawing->file_upload as $media) {
                if (!in_array($media->file_name, $request->input('file_upload', []))) {
                    $media->delete();
                }
            }
        }

        $media = $addDrawing->file_upload->pluck('file_name')->toArray();

        foreach ($request->input('file_upload', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $addDrawing->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
            }
        }

        return redirect()->route('admin.add-drawings.index');
    }

    public function show(AddDrawing $addDrawing)
    {
        abort_if(Gate::denies('add_drawing_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $addDrawing->load('activity_type', 'work_type', 'team');

        return view('admin.addDrawings.show', compact('addDrawing'));
    }

    public function destroy(AddDrawing $addDrawing)
    {
        abort_if(Gate::denies('add_drawing_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $addDrawing->delete();

        return back();
    }

    public function massDestroy(MassDestroyAddDrawingRequest $request)
    {
        AddDrawing::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('add_drawing_create') && Gate::denies('add_drawing_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new AddDrawing();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
