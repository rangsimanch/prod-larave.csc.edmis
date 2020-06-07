<?php

namespace App\Http\Controllers\Admin;

use App\AddLetter;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyLetterIncomingCscRequest;
use App\Http\Requests\StoreLetterIncomingCscRequest;
use App\Http\Requests\UpdateLetterIncomingCscRequest;
use App\LetterIncomingCsc;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class LetterIncomingCscController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('letter_incoming_csc_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = AddLetter::with(['letter_type', 'sender', 'receiver', 'construction_contract', 'team'])->select(sprintf('%s.*', (new AddLetter)->table))
            ->where([
                ['receiver_id',3],
                ['cc_csc',1]
                ]);
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'add_letter_show';
                $editGate      = 'add_letter_edit';
                $deleteGate    = 'add_letter_delete';
                $crudRoutePart = 'add-letters';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : "";
            });
            $table->addColumn('letter_type_type_title', function ($row) {
                return $row->letter_type ? $row->letter_type->type_title : '';
            });

            $table->editColumn('letter_no', function ($row) {
                return $row->letter_no ? $row->letter_no : "";
            });
            $table->addColumn('sender_code', function ($row) {
                return $row->sender ? $row->sender->code : '';
            });

            $table->addColumn('receiver_code', function ($row) {
                return $row->receiver ? $row->receiver->code : '';
            });

            $table->addColumn('construction_contract_code', function ($row) {
                return $row->construction_contract ? $row->construction_contract->code : '';
            });

            $table->editColumn('letter_upload', function ($row) {
                if (!$row->letter_upload) {
                    return '';
                }

                $links = [];

                foreach ($row->letter_upload as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });

            $table->rawColumns(['actions', 'placeholder', 'letter_type', 'sender', 'receiver', 'construction_contract', 'letter_upload']);

            return $table->make(true);
        }


        return view('admin.letterIncomingCscs.index');
    }

    public function create()
    {
        abort_if(Gate::denies('letter_incoming_csc_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $letters = AddLetter::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.letterIncomingCscs.create', compact('letters'));
    }

    public function store(StoreLetterIncomingCscRequest $request)
    {
        $letterIncomingCsc = LetterIncomingCsc::create($request->all());

        return redirect()->route('admin.letter-incoming-cscs.index');
    }

    public function edit(LetterIncomingCsc $letterIncomingCsc)
    {
        abort_if(Gate::denies('letter_incoming_csc_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $letters = AddLetter::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $letterIncomingCsc->load('letter', 'team');

        return view('admin.letterIncomingCscs.edit', compact('letters', 'letterIncomingCsc'));
    }

    public function update(UpdateLetterIncomingCscRequest $request, LetterIncomingCsc $letterIncomingCsc)
    {
        $letterIncomingCsc->update($request->all());

        return redirect()->route('admin.letter-incoming-cscs.index');
    }

    public function show(LetterIncomingCsc $letterIncomingCsc)
    {
        abort_if(Gate::denies('letter_incoming_csc_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $letterIncomingCsc->load('letter', 'team');

        return view('admin.letterIncomingCscs.show', compact('letterIncomingCsc'));
    }

    public function destroy(LetterIncomingCsc $letterIncomingCsc)
    {
        abort_if(Gate::denies('letter_incoming_csc_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $letterIncomingCsc->delete();

        return back();
    }

    public function massDestroy(MassDestroyLetterIncomingCscRequest $request)
    {
        LetterIncomingCsc::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
