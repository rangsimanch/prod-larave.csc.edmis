<?php

namespace App\Http\Controllers\Admin;

use App\ConstructionContract;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyMeetingOtherRequest;
use App\Http\Requests\StoreMeetingOtherRequest;
use App\Http\Requests\UpdateMeetingOtherRequest;
use App\MeetingOther;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class MeetingOtherController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('meeting_other_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = MeetingOther::with(['construction_contract', 'team'])->select(sprintf('%s.*', (new MeetingOther)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'meeting_other_show';
                $editGate      = 'meeting_other_edit';
                $deleteGate    = 'meeting_other_delete';
                $crudRoutePart = 'meeting-others';

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

            $table->editColumn('note', function ($row) {
                return $row->note ? $row->note : "";
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

        return view('admin.meetingOthers.index');
    }

    public function create()
    {
        abort_if(Gate::denies('meeting_other_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.meetingOthers.create', compact('construction_contracts'));
    }

    public function store(StoreMeetingOtherRequest $request)
    {
        $meetingOther = MeetingOther::create($request->all());

        foreach ($request->input('file_upload', []) as $file) {
            $meetingOther->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $meetingOther->id]);
        }

        return redirect()->route('admin.meeting-others.index');

    }

    public function edit(MeetingOther $meetingOther)
    {
        abort_if(Gate::denies('meeting_other_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $meetingOther->load('construction_contract', 'team');

        return view('admin.meetingOthers.edit', compact('construction_contracts', 'meetingOther'));
    }

    public function update(UpdateMeetingOtherRequest $request, MeetingOther $meetingOther)
    {
        $meetingOther->update($request->all());

        if (count($meetingOther->file_upload) > 0) {
            foreach ($meetingOther->file_upload as $media) {
                if (!in_array($media->file_name, $request->input('file_upload', []))) {
                    $media->delete();
                }

            }

        }

        $media = $meetingOther->file_upload->pluck('file_name')->toArray();

        foreach ($request->input('file_upload', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $meetingOther->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
            }

        }

        return redirect()->route('admin.meeting-others.index');

    }

    public function show(MeetingOther $meetingOther)
    {
        abort_if(Gate::denies('meeting_other_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $meetingOther->load('construction_contract', 'team');

        return view('admin.meetingOthers.show', compact('meetingOther'));
    }

    public function destroy(MeetingOther $meetingOther)
    {
        abort_if(Gate::denies('meeting_other_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $meetingOther->delete();

        return back();

    }

    public function massDestroy(MassDestroyMeetingOtherRequest $request)
    {
        MeetingOther::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);

    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('meeting_other_create') && Gate::denies('meeting_other_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new MeetingOther();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);

    }

}
