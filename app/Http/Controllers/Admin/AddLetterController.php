<?php

namespace App\Http\Controllers\Admin;

use App\AddLetter;
use App\ConstructionContract;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyAddLetterRequest;
use App\Http\Requests\StoreAddLetterRequest;
use App\Http\Requests\UpdateAddLetterRequest;
use App\LetterType;
use App\Team;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class AddLetterController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('add_letter_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = AddLetter::with(['letter_type', 'sender', 'receiver', 'construction_contract', 'team'])->select(sprintf('%s.*', (new AddLetter)->table));
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

        return view('admin.addLetters.index');
    }

    public function create()
    {
        abort_if(Gate::denies('add_letter_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $letter_types = LetterType::all()->pluck('type_title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $senders = Team::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $receivers = Team::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.addLetters.create', compact('letter_types', 'senders', 'receivers', 'construction_contracts'));
    }

    public function store(StoreAddLetterRequest $request)
    {
        $addLetter = AddLetter::create($request->all());

        foreach ($request->input('letter_upload', []) as $file) {
            $addLetter->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('letter_upload');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $addLetter->id]);
        }

        return redirect()->route('admin.add-letters.index');
    }

    public function edit(AddLetter $addLetter)
    {
        abort_if(Gate::denies('add_letter_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $letter_types = LetterType::all()->pluck('type_title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $senders = Team::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $receivers = Team::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $addLetter->load('letter_type', 'sender', 'receiver', 'construction_contract', 'team');

        return view('admin.addLetters.edit', compact('letter_types', 'senders', 'receivers', 'construction_contracts', 'addLetter'));
    }

    public function update(UpdateAddLetterRequest $request, AddLetter $addLetter)
    {
        $addLetter->update($request->all());

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

        return redirect()->route('admin.add-letters.index');
    }

    public function show(AddLetter $addLetter)
    {
        abort_if(Gate::denies('add_letter_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $addLetter->load('letter_type', 'sender', 'receiver', 'construction_contract', 'team');

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
