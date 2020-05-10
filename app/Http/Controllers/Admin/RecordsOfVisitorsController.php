<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyRecordsOfVisitorRequest;
use App\Http\Requests\StoreRecordsOfVisitorRequest;
use App\Http\Requests\UpdateRecordsOfVisitorRequest;
use App\RecordsOfVisitor;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class RecordsOfVisitorsController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('records_of_visitor_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = RecordsOfVisitor::query()->select(sprintf('%s.*', (new RecordsOfVisitor)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'records_of_visitor_show';
                $editGate      = 'records_of_visitor_edit';
                $deleteGate    = 'records_of_visitor_delete';
                $crudRoutePart = 'records-of-visitors';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('name_of_visitor', function ($row) {
                return $row->name_of_visitor ? $row->name_of_visitor : "";
            });
            $table->editColumn('details', function ($row) {
                return $row->details ? $row->details : "";
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

            $table->rawColumns(['actions', 'placeholder', 'file_upload']);

            return $table->make(true);
        }

        return view('admin.recordsOfVisitors.index');
    }

    public function create()
    {
        abort_if(Gate::denies('records_of_visitor_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.recordsOfVisitors.create');
    }

    public function store(StoreRecordsOfVisitorRequest $request)
    {
        $recordsOfVisitor = RecordsOfVisitor::create($request->all());

        foreach ($request->input('file_upload', []) as $file) {
            $recordsOfVisitor->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $recordsOfVisitor->id]);
        }

        return redirect()->route('admin.records-of-visitors.index');
    }

    public function edit(RecordsOfVisitor $recordsOfVisitor)
    {
        abort_if(Gate::denies('records_of_visitor_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.recordsOfVisitors.edit', compact('recordsOfVisitor'));
    }

    public function update(UpdateRecordsOfVisitorRequest $request, RecordsOfVisitor $recordsOfVisitor)
    {
        $recordsOfVisitor->update($request->all());

        if (count($recordsOfVisitor->file_upload) > 0) {
            foreach ($recordsOfVisitor->file_upload as $media) {
                if (!in_array($media->file_name, $request->input('file_upload', []))) {
                    $media->delete();
                }
            }
        }

        $media = $recordsOfVisitor->file_upload->pluck('file_name')->toArray();

        foreach ($request->input('file_upload', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $recordsOfVisitor->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
            }
        }

        return redirect()->route('admin.records-of-visitors.index');
    }

    public function show(RecordsOfVisitor $recordsOfVisitor)
    {
        abort_if(Gate::denies('records_of_visitor_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.recordsOfVisitors.show', compact('recordsOfVisitor'));
    }

    public function destroy(RecordsOfVisitor $recordsOfVisitor)
    {
        abort_if(Gate::denies('records_of_visitor_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $recordsOfVisitor->delete();

        return back();
    }

    public function massDestroy(MassDestroyRecordsOfVisitorRequest $request)
    {
        RecordsOfVisitor::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('records_of_visitor_create') && Gate::denies('records_of_visitor_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new RecordsOfVisitor();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
