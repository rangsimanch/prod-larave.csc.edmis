<?php

namespace App\Http\Controllers\Admin;

use App\ConstructionContract;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyMeetingWeeklyRequest;
use App\Http\Requests\StoreMeetingWeeklyRequest;
use App\Http\Requests\UpdateMeetingWeeklyRequest;
use App\MeetingWeekly;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class MeetingWeeklyController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('meeting_weekly_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = MeetingWeekly::with(['construction_contract', 'team'])->select(sprintf('%s.*', (new MeetingWeekly)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'meeting_weekly_show';
                $editGate      = 'meeting_weekly_edit';
                $deleteGate    = 'meeting_weekly_delete';
                $crudRoutePart = 'meeting-weeklies';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('meeting_name', function ($row) {
                return $row->meeting_name ? $row->meeting_name : "";
            });
            $table->editColumn('meeting_no', function ($row) {
                return $row->meeting_no ? $row->meeting_no : "";
            });

            $table->addColumn('construction_contract_code', function ($row) {
                return $row->construction_contract ? $row->construction_contract->code : '';
            });

            $table->editColumn('note', function ($row) {
                return $row->note ? $row->note : "";
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

        return view('admin.meetingWeeklies.index');
    }

    public function create()
    {
        abort_if(Gate::denies('meeting_weekly_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.meetingWeeklies.create', compact('construction_contracts'));
    }

    public function store(StoreMeetingWeeklyRequest $request)
    {
        $meetingWeekly = MeetingWeekly::create($request->all());

        foreach ($request->input('file_upload', []) as $file) {
            $meetingWeekly->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $meetingWeekly->id]);
        }

        return redirect()->route('admin.meeting-weeklies.index');

    }

    public function edit(MeetingWeekly $meetingWeekly)
    {
        abort_if(Gate::denies('meeting_weekly_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $meetingWeekly->load('construction_contract', 'team');

        return view('admin.meetingWeeklies.edit', compact('construction_contracts', 'meetingWeekly'));
    }

    public function update(UpdateMeetingWeeklyRequest $request, MeetingWeekly $meetingWeekly)
    {
        $meetingWeekly->update($request->all());

        if (count($meetingWeekly->file_upload) > 0) {
            foreach ($meetingWeekly->file_upload as $media) {
                if (!in_array($media->file_name, $request->input('file_upload', []))) {
                    $media->delete();
                }

            }

        }

        $media = $meetingWeekly->file_upload->pluck('file_name')->toArray();

        foreach ($request->input('file_upload', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $meetingWeekly->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
            }

        }

        return redirect()->route('admin.meeting-weeklies.index');

    }

    public function show(MeetingWeekly $meetingWeekly)
    {
        abort_if(Gate::denies('meeting_weekly_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $meetingWeekly->load('construction_contract', 'team');

        return view('admin.meetingWeeklies.show', compact('meetingWeekly'));
    }

    public function destroy(MeetingWeekly $meetingWeekly)
    {
        abort_if(Gate::denies('meeting_weekly_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $meetingWeekly->delete();

        return back();

    }

    public function massDestroy(MassDestroyMeetingWeeklyRequest $request)
    {
        MeetingWeekly::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);

    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('meeting_weekly_create') && Gate::denies('meeting_weekly_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new MeetingWeekly();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);

    }

}
