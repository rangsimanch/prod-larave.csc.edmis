<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\LetterSubjectType;
use App\AddLetter;
use App\ConstructionContract;
use App\Team;
use App\User;
use Yajra\DataTables\Facades\DataTables;
use Spatie\MediaLibrary\Models\Media;
use Illuminate\Support\Facades\Auth;


class UniqueInboxController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('csc_inbox_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax()) {
            $query = AddLetter::with(['sender', 'receiver', 'cc_tos', 'construction_contract', 'create_by', 'receive_by', 'team'])
            ->select(sprintf('%s.*', (new AddLetter)->table))
            ->where(function ($query) {
                $query->where('add_letters.receiver_id', 14)
                    ->orWhere(function ($subquery) {
                        $subquery->whereRaw('EXISTS (SELECT 1 FROM add_letter_team WHERE add_letters.id = add_letter_team.add_letter_id AND add_letter_team.team_id = 14)');
                    });
            });
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'add_letter_show';
                if($row->receiver->code == "UNIQUE"){
                    $editGate      = 'add_letter_edit';
                }
                else{
                    $editGate      = 'can_not_show';
                }
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
                // if($row->receiver->code == "UNIQUE" && $row->mask_as_received == 0){
                //     return sprintf("<p style=\"color:blue\"><b>%s</b></p>",$row->letter_type ? AddLetter::LETTER_TYPE_SELECT[$row->letter_type] : '');
                // }
                // else{
                //     return $row->letter_type ? AddLetter::LETTER_TYPE_SELECT[$row->letter_type] : '';
                // }
                return $row->letter_type ? AddLetter::LETTER_TYPE_SELECT[$row->letter_type] : '';

            });
            $table->editColumn('title', function ($row) {
                // if($row->receiver->code == "UNIQUE" && $row->mask_as_received == 0){
                //     return sprintf("<p style=\"color:blue\"><b>%s</b></p>",$row->title ? $row->title : "");
                // }
                // else{
                //     return $row->title ? $row->title : "";
                // }
                return $row->title ? $row->title : "";

            });
            $table->editColumn('letter_no', function ($row) {
                // if($row->receiver->code == "UNIQUE" && $row->mask_as_received == 0){
                //     return sprintf("<p style=\"color:blue\"><b>%s</b></p>",$row->letter_no ? $row->letter_no : "");
                // }
                // else{
                //     return $row->letter_no ? $row->letter_no : "";
                // }
                return $row->letter_no ? $row->letter_no : "";

            });

            $table->addColumn('sender_code', function ($row) {
                // if($row->receiver->code == "UNIQUE" && $row->mask_as_received == 0){
                //     return sprintf("<p style=\"color:blue\"><b>%s</b></p>",$row->sender ? $row->sender->code : '');
                // }
                // else{
                //     return $row->sender ? $row->sender->code : '';
                // }
                return $row->sender ? $row->sender->code : '';

            });

            $table->addColumn('receiver_code', function ($row) {
                // if($row->receiver->code == "UNIQUE" && $row->mask_as_received == 0){
                //     return sprintf("<p style=\"color:blue\"><b>%s</b></p>",$row->receiver ? $row->receiver->code : '');
                // }
                // else{
                //     return $row->receiver ? $row->receiver->code : '';
                // }
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
                // if($row->receiver->code == "UNIQUE" && $row->mask_as_received == 0){
                //     return sprintf("<p style=\"color:blue\"><b>%s</b></p>",$row->construction_contract ? $row->construction_contract->code : '');
                // }
                // else{
                //     return $row->construction_contract ? $row->construction_contract->code : '';
                // }
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

            $table->editColumn('mask_as_received', function ($row) {
                if($row->mask_as_received == 1){
                    $mask_check = "Marked";
                }
                else{
                    $mask_check = "Not Marked";
                }
                return $mask_check;
            });

            $table->addColumn('responsible_name', function ($row) {
                return $row->responsible ? $row->responsible->name : '';
            });

            $table->editColumn('processing_time', function ($row) {
                return $row->processing_time ? $row->processing_time : '';
            });
            $table->editColumn('topic_category', function ($row) {
                $labels = [];
                foreach ($row->topic_categories as $topic_category) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $topic_category->subject_name);
                }

                return implode(' ', $labels);
            });

            $table->rawColumns(['actions', 'placeholder', 
            'letter_type', 'title', 'letter_no', 'sender_code', 'receiver_code' ,'construction_contract_code',
            'sender','receiver', 'cc_to', 'construction_contract', 'letter_upload', 'mask_as_received',
            'responsible_name', 'processing_time', 'topic_category']);

            return $table->make(true);
        }

        $letter_subject_types   = LetterSubjectType::get();
        $teams                  = Team::get();
        $teams                  = Team::get();
        $teams                  = Team::get();
        // $construction_contracts = ConstructionContract::get();
        $construction_contracts = ConstructionContract::where('id',session('construction_contract_id'))->get();
        $users                  = User::get();
        $users                  = User::get();
        $teams                  = Team::get();

        session(['previous-url' => route('admin.unique-inboxes.index')]);
        return view('admin.uniqueInboxes.index', compact('letter_subject_types','teams', 'teams', 'teams', 'construction_contracts', 'users', 'users', 'teams'));
    }
}
