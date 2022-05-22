<?php

namespace App\Http\Controllers\Admin;

use App\ConstructionContract;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyInterimPaymentMeetingRequest;
use App\Http\Requests\StoreInterimPaymentMeetingRequest;
use App\Http\Requests\UpdateInterimPaymentMeetingRequest;
use App\InterimPaymentMeeting;
use App\Team;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class InterimPaymentMeetingController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('interim_payment_meeting_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = InterimPaymentMeeting::with(['construction_contract', 'team'])->select(sprintf('%s.*', (new InterimPaymentMeeting())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'interim_payment_meeting_show';
                $editGate = 'interim_payment_meeting_edit';
                $deleteGate = 'interim_payment_meeting_delete';
                $crudRoutePart = 'interim-payment-meetings';

                return view('partials.datatablesActions', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'row'
            ));
            });

            $table->editColumn('meeting_name', function ($row) {
                return $row->meeting_name ? $row->meeting_name : '';
            });
            $table->editColumn('meeting_no', function ($row) {
                return $row->meeting_no ? $row->meeting_no : '';
            });

            $table->addColumn('construction_contract_code', function ($row) {
                return $row->construction_contract ? $row->construction_contract->code : '';
            });

            $table->editColumn('note', function ($row) {
                return $row->note ? $row->note : '';
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

        return view('admin.interimPaymentMeetings.index', compact('construction_contracts', 'teams'));
    }

    public function create()
    {
        abort_if(Gate::denies('interim_payment_meeting_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $construction_contracts = ConstructionContract::pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.interimPaymentMeetings.create', compact('construction_contracts'));
    }

    public function store(StoreInterimPaymentMeetingRequest $request)
    {
        $interimPaymentMeeting = InterimPaymentMeeting::create($request->all());

        foreach ($request->input('file_upload', []) as $file) {
            $interimPaymentMeeting->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('file_upload');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $interimPaymentMeeting->id]);
        }

        return redirect()->route('admin.interim-payment-meetings.index');
    }

    public function edit(InterimPaymentMeeting $interimPaymentMeeting)
    {
        abort_if(Gate::denies('interim_payment_meeting_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $construction_contracts = ConstructionContract::pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $interimPaymentMeeting->load('construction_contract', 'team');

        return view('admin.interimPaymentMeetings.edit', compact('construction_contracts', 'interimPaymentMeeting'));
    }

    public function update(UpdateInterimPaymentMeetingRequest $request, InterimPaymentMeeting $interimPaymentMeeting)
    {
        $interimPaymentMeeting->update($request->all());

        if (count($interimPaymentMeeting->file_upload) > 0) {
            foreach ($interimPaymentMeeting->file_upload as $media) {
                if (!in_array($media->file_name, $request->input('file_upload', []))) {
                    $media->delete();
                }
            }
        }
        $media = $interimPaymentMeeting->file_upload->pluck('file_name')->toArray();
        foreach ($request->input('file_upload', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $interimPaymentMeeting->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('file_upload');
            }
        }

        return redirect()->route('admin.interim-payment-meetings.index');
    }

    public function show(InterimPaymentMeeting $interimPaymentMeeting)
    {
        abort_if(Gate::denies('interim_payment_meeting_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $interimPaymentMeeting->load('construction_contract', 'team');

        return view('admin.interimPaymentMeetings.show', compact('interimPaymentMeeting'));
    }

    public function destroy(InterimPaymentMeeting $interimPaymentMeeting)
    {
        abort_if(Gate::denies('interim_payment_meeting_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $interimPaymentMeeting->delete();

        return back();
    }

    public function massDestroy(MassDestroyInterimPaymentMeetingRequest $request)
    {
        InterimPaymentMeeting::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('interim_payment_meeting_create') && Gate::denies('interim_payment_meeting_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new InterimPaymentMeeting();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
