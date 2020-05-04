<?php

namespace App\Http\Controllers\Admin;

use App\ConstructionContract;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyMeetingMonthlyRequest;
use App\Http\Requests\StoreMeetingMonthlyRequest;
use App\Http\Requests\UpdateMeetingMonthlyRequest;
use App\MeetingMonthly;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class MeetingMonthlyController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('meeting_monthly_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = MeetingMonthly::with(['construction_contract', 'team'])->select(sprintf('%s.*', (new MeetingMonthly)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'meeting_monthly_show';
                $editGate      = 'meeting_monthly_edit';
                $deleteGate    = 'meeting_monthly_delete';
                $crudRoutePart = 'meeting-monthlies';

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

        return view('admin.meetingMonthlies.index');
    }

    public function create()
    {
        abort_if(Gate::denies('meeting_monthly_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.meetingMonthlies.create', compact('construction_contracts'));
    }

    public function store(StoreMeetingMonthlyRequest $request)
    {
        $meetingMonthly = MeetingMonthly::create($request->all());

        foreach ($request->input('file_upload', []) as $file) {
            $meetingMonthly->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $meetingMonthly->id]);
        }

        return redirect()->route('admin.meeting-monthlies.index');

    }

    public function edit(MeetingMonthly $meetingMonthly)
    {
        abort_if(Gate::denies('meeting_monthly_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $meetingMonthly->load('construction_contract', 'team');

        return view('admin.meetingMonthlies.edit', compact('construction_contracts', 'meetingMonthly'));
    }

    public function update(UpdateMeetingMonthlyRequest $request, MeetingMonthly $meetingMonthly)
    {
        $meetingMonthly->update($request->all());

        if (count($meetingMonthly->file_upload) > 0) {
            foreach ($meetingMonthly->file_upload as $media) {
                if (!in_array($media->file_name, $request->input('file_upload', []))) {
                    $media->delete();
                }

            }

        }

        $media = $meetingMonthly->file_upload->pluck('file_name')->toArray();

        foreach ($request->input('file_upload', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $meetingMonthly->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
            }

        }

        return redirect()->route('admin.meeting-monthlies.index');

    }

    public function show(MeetingMonthly $meetingMonthly)
    {
        abort_if(Gate::denies('meeting_monthly_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $meetingMonthly->load('construction_contract', 'team');

        return view('admin.meetingMonthlies.show', compact('meetingMonthly'));
    }

    public function destroy(MeetingMonthly $meetingMonthly)
    {
        abort_if(Gate::denies('meeting_monthly_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $meetingMonthly->delete();

        return back();

    }

    public function massDestroy(MassDestroyMeetingMonthlyRequest $request)
    {
        MeetingMonthly::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);

    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('meeting_monthly_create') && Gate::denies('meeting_monthly_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new MeetingMonthly();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);

    }

}
