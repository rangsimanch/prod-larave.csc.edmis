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


class CanSentController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('can_sent_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax()) {
            $query = AddLetter::with(['sender', 'receiver', 'cc_tos', 'construction_contract', 'create_by', 'receive_by', 'team'])
            ->select(sprintf('%s.*', (new AddLetter)->table))
            ->orWhere('sender_id',19);
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'add_letter_show';
                // $editGate      = 'can_not_show';
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
        
        session(['previous-url' => route('admin.itd-crec-sents.index')]);
        return view('admin.itdCrecSents.index', compact('letter_subject_types','teams', 'teams', 'teams', 'construction_contracts', 'users', 'users', 'teams'));        
    }
}
