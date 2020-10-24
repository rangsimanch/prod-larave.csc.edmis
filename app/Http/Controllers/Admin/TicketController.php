<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyTicketRequest;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Ticket;
use Gate;
use DateTime;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class TicketController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('ticket_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Ticket::query()->select(sprintf('%s.*', (new Ticket)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'ticket_show';
                $editGate      = 'ticket_edit';
                $deleteGate    = 'ticket_delete';
                $crudRoutePart = 'tickets';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('subject', function ($row) {
                return $row->subject ? $row->subject : "";
            });
            $table->editColumn('ticket_code', function ($row) {
                return $row->ticket_code ? $row->ticket_code : "";
            });
            $table->editColumn('status', function ($row) {

                if ($row->status == "Requested"){
                    return sprintf('<span class="label label-warning label-many">%s</span>',$row->status ? Ticket::STATUS_SELECT[$row->status] : '');
                }
                else{
                    return sprintf('<span class="label label-success label-many">%s</span>',$row->status ? Ticket::STATUS_SELECT[$row->status] : '');
                }
            });

            $table->rawColumns(['actions', 'placeholder','status']);

            return $table->make(true);
        }

        return view('admin.tickets.index');
    }

    public function create()
    {
        abort_if(Gate::denies('ticket_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.tickets.create');
    }

    public function store(StoreTicketRequest $request)
    {

        $data = $request->all();

        $date = new Datetime;
        $date_code = $date->format("dmy");
        $create_by = auth()->id();
        $rand = rand(100,999);

        $data['ticket_code'] = $data['request_type'] . $data['module'] . $rand  . $date_code;
        $data['status'] = "Requested";

        $ticket = Ticket::create($data);

        foreach ($request->input('attachment', []) as $file) {
            $ticket->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('attachment');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $ticket->id]);
        }

        return redirect()->route('admin.tickets.index');
    }

    public function edit(Ticket $ticket)
    {
        abort_if(Gate::denies('ticket_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.tickets.edit', compact('ticket'));
    }

    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        $ticket->update($request->all());

        if (count($ticket->attachment) > 0) {
            foreach ($ticket->attachment as $media) {
                if (!in_array($media->file_name, $request->input('attachment', []))) {
                    $media->delete();
                }
            }
        }

        $media = $ticket->attachment->pluck('file_name')->toArray();

        foreach ($request->input('attachment', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $ticket->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('attachment');
            }
        }

        return redirect()->route('admin.tickets.index');
    }

    public function show(Ticket $ticket)
    {
        abort_if(Gate::denies('ticket_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.tickets.show', compact('ticket'));
    }

    public function destroy(Ticket $ticket)
    {
        abort_if(Gate::denies('ticket_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ticket->delete();

        return back();
    }

    public function massDestroy(MassDestroyTicketRequest $request)
    {
        Ticket::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('ticket_create') && Gate::denies('ticket_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Ticket();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
