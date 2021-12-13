<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\AddLetter;
use App\ConstructionContract;
use App\Team;
use App\User;
use Yajra\DataTables\Facades\DataTables;
use Spatie\MediaLibrary\Models\Media;
use Illuminate\Support\Facades\Auth;


class CscSentController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('csc_sent_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = AddLetter::with(['sender', 'receiver', 'cc_tos', 'construction_contract', 'create_by', 'receive_by', 'team'])
            ->select(sprintf('%s.*', (new AddLetter)->table))
            ->orWhere('sender_id',3);
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'add_letter_show';
                $editGate      = 'can_not_show';
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

            $table->editColumn('letter_type', function ($row) {
                return $row->letter_type ? AddLetter::LETTER_TYPE_SELECT[$row->letter_type] : '';
            });
            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : "";
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

            $table->editColumn('cc_to', function ($row) {
                $labels = [];

                foreach ($row->cc_tos as $cc_to) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $cc_to->code);
                }

                return implode(' ', $labels);
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

            $table->rawColumns(['actions', 'placeholder', 'sender','receiver', 'cc_to', 'construction_contract', 'letter_upload']);

            return $table->make(true);
        }

        $teams                  = Team::get();
        $teams                  = Team::get();
        $teams                  = Team::get();
        $construction_contracts = ConstructionContract::where('id',session('construction_contract_id'))->get();
        // $construction_contracts = ConstructionContract::get();
        $users                  = User::get();
        $users                  = User::get();
        $teams                  = Team::get();
        
        session(['previous-url' => route('admin.csc-sents.index')]);
        return view('admin.cscSents.index', compact('teams', 'teams', 'teams', 'construction_contracts', 'users', 'users', 'teams'));
    }
}
