<?php

namespace App\Http\Controllers\Admin;

use App\CheckList;
use App\ConstructionContract;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyCheckListRequest;
use App\Http\Requests\StoreCheckListRequest;
use App\Http\Requests\UpdateCheckListRequest;
use App\TaskTag;
use App\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Team;


class CheckListController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('check_list_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = CheckList::with(['work_type', 'name_of_inspector', 'construction_contract', 'team'])->select(sprintf('%s.*', (new CheckList)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'check_list_show';
                $editGate      = 'check_list_edit';
                $deleteGate    = 'check_list_delete';
                $crudRoutePart = 'check-lists';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->addColumn('work_type_name', function ($row) {
                return $row->work_type ? $row->work_type->name : '';
            });

            $table->editColumn('work_title', function ($row) {
                return $row->work_title ? $row->work_title : "";
            });
            $table->editColumn('location', function ($row) {
                return $row->location ? $row->location : "";
            });

            $table->addColumn('name_of_inspector_name', function ($row) {
                return $row->name_of_inspector ? $row->name_of_inspector->name : '';
            });

            $table->editColumn('thai_or_chinese_work', function ($row) {
                return $row->thai_or_chinese_work ? CheckList::THAI_OR_CHINESE_WORK_SELECT[$row->thai_or_chinese_work] : '';
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

            $table->rawColumns(['actions', 'placeholder', 'work_type', 'name_of_inspector', 'construction_contract', 'file_upload']);

            return $table->make(true);
        }

        $task_tags              = TaskTag::get()->pluck('name')->toArray();
        $users                  = User::get()->pluck('name')->toArray();
        $construction_contracts = ConstructionContract::get()->pluck('code')->toArray();
        $teams                  = Team::get()->pluck('name')->toArray();

        return view('admin.checkLists.index', compact('task_tags', 'users', 'construction_contracts', 'teams'));
    }

    public function create()
    {
        abort_if(Gate::denies('check_list_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $work_types = TaskTag::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $name_of_inspectors = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.checkLists.create', compact('work_types', 'name_of_inspectors', 'construction_contracts'));
    }

    public function store(StoreCheckListRequest $request)
    {
        $checkList = CheckList::create($request->all());

        foreach ($request->input('file_upload', []) as $file) {
            $checkList->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $checkList->id]);
        }

        return redirect()->route('admin.check-lists.index');
    }

    public function edit(CheckList $checkList)
    {
        abort_if(Gate::denies('check_list_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $work_types = TaskTag::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $name_of_inspectors = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $checkList->load('work_type', 'name_of_inspector', 'construction_contract', 'team');

        return view('admin.checkLists.edit', compact('work_types', 'name_of_inspectors', 'construction_contracts', 'checkList'));
    }

    public function update(UpdateCheckListRequest $request, CheckList $checkList)
    {
        $checkList->update($request->all());

        if (count($checkList->file_upload) > 0) {
            foreach ($checkList->file_upload as $media) {
                if (!in_array($media->file_name, $request->input('file_upload', []))) {
                    $media->delete();
                }
            }
        }

        $media = $checkList->file_upload->pluck('file_name')->toArray();

        foreach ($request->input('file_upload', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $checkList->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
            }
        }

        return redirect()->route('admin.check-lists.index');
    }

    public function show(CheckList $checkList)
    {
        abort_if(Gate::denies('check_list_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $checkList->load('work_type', 'name_of_inspector', 'construction_contract', 'team');

        return view('admin.checkLists.show', compact('checkList'));
    }

    public function destroy(CheckList $checkList)
    {
        abort_if(Gate::denies('check_list_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $checkList->delete();

        return back();
    }

    public function massDestroy(MassDestroyCheckListRequest $request)
    {
        CheckList::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('check_list_create') && Gate::denies('check_list_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new CheckList();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
