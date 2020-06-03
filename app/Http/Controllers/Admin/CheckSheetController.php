<?php

namespace App\Http\Controllers\Admin;

use App\CheckSheet;
use App\ConstructionContract;
use App\DownloadSystemWork;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyCheckSheetRequest;
use App\Http\Requests\StoreCheckSheetRequest;
use App\Http\Requests\UpdateCheckSheetRequest;
use App\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Team;


class CheckSheetController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('check_sheet_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = CheckSheet::with(['work_type', 'name_of_inspector', 'construction_contract', 'team'])->select(sprintf('%s.*', (new CheckSheet)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'check_sheet_show';
                $editGate      = 'check_sheet_edit';
                $deleteGate    = 'check_sheet_delete';
                $crudRoutePart = 'check-sheets';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->addColumn('work_type_work_title', function ($row) {
                return $row->work_type ? $row->work_type->work_title : '';
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
                return $row->thai_or_chinese_work ? CheckSheet::THAI_OR_CHINESE_WORK_SELECT[$row->thai_or_chinese_work] : '';
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

        $download_system_works  = DownloadSystemWork::get()->pluck('work_title')->toArray();
        $users                  = User::get()->pluck('name')->toArray();
        $construction_contracts = ConstructionContract::get()->pluck('code')->toArray();
        $teams                  = Team::get()->pluck('name')->toArray();

        return view('admin.checkSheets.index', compact('download_system_works', 'users', 'construction_contracts', 'teams'));
    }

    public function create()
    {
        abort_if(Gate::denies('check_sheet_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $work_types = DownloadSystemWork::all()->pluck('work_title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $name_of_inspectors = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.checkSheets.create', compact('work_types', 'name_of_inspectors', 'construction_contracts'));
    }

    public function store(StoreCheckSheetRequest $request)
    {
        $checkSheet = CheckSheet::create($request->all());

        foreach ($request->input('file_upload', []) as $file) {
            $checkSheet->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $checkSheet->id]);
        }

        return redirect()->route('admin.check-sheets.index');
    }

    public function edit(CheckSheet $checkSheet)
    {
        abort_if(Gate::denies('check_sheet_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $work_types = DownloadSystemWork::all()->pluck('work_title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $name_of_inspectors = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $checkSheet->load('work_type', 'name_of_inspector', 'construction_contract', 'team');

        return view('admin.checkSheets.edit', compact('work_types', 'name_of_inspectors', 'construction_contracts', 'checkSheet'));
    }

    public function update(UpdateCheckSheetRequest $request, CheckSheet $checkSheet)
    {
        $checkSheet->update($request->all());

        if (count($checkSheet->file_upload) > 0) {
            foreach ($checkSheet->file_upload as $media) {
                if (!in_array($media->file_name, $request->input('file_upload', []))) {
                    $media->delete();
                }
            }
        }

        $media = $checkSheet->file_upload->pluck('file_name')->toArray();

        foreach ($request->input('file_upload', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $checkSheet->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
            }
        }

        return redirect()->route('admin.check-sheets.index');
    }

    public function show(CheckSheet $checkSheet)
    {
        abort_if(Gate::denies('check_sheet_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $checkSheet->load('work_type', 'name_of_inspector', 'construction_contract', 'team');

        return view('admin.checkSheets.show', compact('checkSheet'));
    }

    public function destroy(CheckSheet $checkSheet)
    {
        abort_if(Gate::denies('check_sheet_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $checkSheet->delete();

        return back();
    }

    public function massDestroy(MassDestroyCheckSheetRequest $request)
    {
        CheckSheet::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('check_sheet_create') && Gate::denies('check_sheet_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new CheckSheet();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
