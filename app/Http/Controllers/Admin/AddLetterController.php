<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\AddLetter;
use App\ConstructionContract;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyAddLetterRequest;
use App\Http\Requests\StoreAddLetterRequest;
use App\Http\Requests\UpdateAddLetterRequest;
use App\LetterSubjectType;
use App\Team;
use App\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

use DateTime;
use Illuminate\Support\Facades\Auth;


class AddLetterController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('add_letter_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = AddLetter::with(['sender', 'receiver', 'cc_tos', 'construction_contract', 'create_by', 'receive_by', 'team'])->select(sprintf('%s.*', (new AddLetter)->table));
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

            $table->editColumn('status', function ($row) {
                if ($row->status == 'New'){
                    return sprintf('<p style="color:#003399"><b>%s</b></p>',$row->status ? AddLetter::STATUS_SELECT[$row->status] : '');
                }
                else if ($row->status == 'Acknowledged'){
                    return sprintf('<p style="color:#009933"><b>%s</b></p>',$row->status ? AddLetter::STATUS_SELECT[$row->status] : '');
                }
                else if ($row->status == 'Replied'){
                    return sprintf('<p style="color:#ff9900"><b>%s</b></p>',$row->status ? AddLetter::STATUS_SELECT[$row->status] : '');
                }
                else if ($row->status == 'Other'){
                    return sprintf('<p style="color:#6600cc"><b>%s</b></p>',$row->status ? AddLetter::STATUS_SELECT[$row->status] : '');
                }
                else if ($row->status == 'Cancel'){
                    return sprintf('<p style="color:#FF0000"><b>%s</b></p>',$row->status ? AddLetter::STATUS_SELECT[$row->status] : '');
                }
                else {
                    return $row->status ? AddLetter::STATUS_SELECT[$row->status] : '';

                }
            });

            $table->editColumn('letter_type', function ($row) {
                return $row->letter_type ? AddLetter::LETTER_TYPE_SELECT[$row->letter_type] : '';
            });
            $table->editColumn('topic_category', function ($row) {
                $labels = [];
                foreach ($row->topic_categories as $topic_category) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $topic_category->subject_name);
                }

                return implode(' ', $labels);
            });
            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : "";
            });
            $table->editColumn('letter_no', function ($row) {
                return $row->letter_no ? $row->letter_no : "";
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

            $table->rawColumns(['actions', 'placeholder', 'topic_category', 'receiver', 'cc_to', 'construction_contract', 'letter_upload', 'responsible', 'status']);

            return $table->make(true);
        }
        $letter_subject_types   = LetterSubjectType::get();
        $teams                  = Team::get();
        $teams                  = Team::get();
        $teams                  = Team::get();
        $construction_contracts = ConstructionContract::get();
        $users                  = User::get();
        $users                  = User::get();
        $teams                  = Team::get();

        session(['previous-url' => request()->url()]);
        return view('admin.addLetters.index', compact('letter_subject_types', 'teams', 'teams', 'teams', 'construction_contracts', 'users', 'users', 'teams'));
    }

    public function create()
    {
        abort_if(Gate::denies('add_letter_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $topic_categories = LetterSubjectType::pluck('subject_name', 'id');

        $receivers = Team::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $cc_tos = Team::all()->pluck('code', 'id');

        //Contract Check
            //Check is Admin
        if(Auth::id() != 1){
            $construction_contracts = ConstructionContract::where('id',session('construction_contract_id'))->pluck('code', 'id');
            // $senders = Team::where('id',auth()->user()->team_id)->pluck('code', 'id');
            $senders = Team::all()->pluck('code', 'id');

        }
        else{
            $construction_contracts = ConstructionContract::all()->pluck('code', 'id');
            $senders = Team::all()->pluck('code', 'id');
        }

        $responsibles = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.addLetters.create', compact('cc_tos', 'construction_contracts', 'receivers', 'responsibles', 'senders', 'topic_categories'));
    }

    public function store(StoreAddLetterRequest $request)
    {
        $data = $request->all();
        $start_date = $data['start_date'];
        $complete_date = $data['complete_date'];

        if($start_date != "" && $complete_date != ""){
            $start_date = \DateTime::createFromFormat('d/m/Y', $data['start_date']);
            $complete_date = \DateTime::createFromFormat('d/m/Y', $data['complete_date']);
            $diff = date_diff($start_date, $complete_date);
            $data['processing_time'] = $diff->format("%a days");
        }
        else{
            $data['processing_time'] = "-" ;   
        }
        // $data['objective'] = 'เพื่อทราบ';
        $data['create_by_id'] = auth()->id();
        
        //Add letter ISO Number HERE!

        $addLetter = AddLetter::create($data);
        $addLetter->cc_tos()->sync($request->input('cc_tos', []));

        foreach ($request->input('letter_upload', []) as $file) {
            $addLetter->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('letter_upload');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $addLetter->id]);
        }

        return redirect(session('previous-url'));
    }

    public function edit(AddLetter $addLetter)
    {
        abort_if(Gate::denies('add_letter_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $topic_categories = LetterSubjectType::pluck('subject_name', 'id');

        if(Auth::id() != 1){
            $construction_contracts = ConstructionContract::where('id',session('construction_contract_id'))->pluck('code', 'id');
            // $senders = Team::where('id',auth()->user()->team_id)->pluck('code', 'id');
            $senders = Team::all()->pluck('code', 'id');

        }
        else{
            $construction_contracts = ConstructionContract::all()->pluck('code', 'id');
            $senders = Team::all()->pluck('code', 'id');
        }

        $receivers = Team::pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $cc_tos = Team::pluck('code', 'id');

        $responsibles = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $addLetter->load('sender', 'receiver', 'cc_tos', 'construction_contract', 'create_by', 'receive_by', 'team');

        return view('admin.addLetters.edit', compact('addLetter', 'cc_tos', 'construction_contracts', 'receivers', 'responsibles', 'senders', 'topic_categories'));
    }
    
    public function update(UpdateAddLetterRequest $request, AddLetter $addLetter)
    {
        $data = $request->all();
        // if($data['mask_as_received'] == 1){
        //     $data['receive_by_id'] = auth()->id();
        //     $received_date = new DateTime();
        //     $data['received_date'] = $received_date->format("d/m/Y");
        // }
        $start_date = $data['start_date'];
        $complete_date = $data['complete_date'];

        if($start_date != "" && $complete_date != ""){
            $start_date = \DateTime::createFromFormat('d/m/Y', $data['start_date']);
            $complete_date = \DateTime::createFromFormat('d/m/Y', $data['complete_date']);
            $diff = date_diff($start_date, $complete_date);
            $data['processing_time'] = $diff->format("%a days");
        }
        else{
            $data['processing_time'] = "-" ;   
        }
        $addLetter->update($data);
        $addLetter->cc_tos()->sync($request->input('cc_tos', []));

        if (count($addLetter->letter_upload) > 0) {
            foreach ($addLetter->letter_upload as $media) {
                if (!in_array($media->file_name, $request->input('letter_upload', []))) {
                    $media->delete();
                }
            }
        }

        $media = $addLetter->letter_upload->pluck('file_name')->toArray();
        foreach ($request->input('letter_upload', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $addLetter->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('letter_upload');
            }
        }

        return redirect(session('previous-url'));
    }

    public function show(AddLetter $addLetter)
    {
        abort_if(Gate::denies('add_letter_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $addLetter->load('sender', 'receiver', 'cc_tos', 'construction_contract', 'create_by', 'receive_by', 'team');

        return view('admin.addLetters.show', compact('addLetter'));
    }

    public function destroy(AddLetter $addLetter)
    {
        abort_if(Gate::denies('add_letter_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $addLetter->delete();

        return back();
    }

    public function massDestroy(MassDestroyAddLetterRequest $request)
    {
        AddLetter::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('add_letter_create') && Gate::denies('add_letter_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new AddLetter();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
