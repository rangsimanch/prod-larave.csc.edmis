<?php

namespace App\Http\Controllers\Admin;

use App\CloseOutDescription;
use App\CloseOutDrive;
use App\CloseOutMain;
use App\ConstructionContract;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyCloseOutMainRequest;
use App\Http\Requests\StoreCloseOutMainRequest;
use App\Http\Requests\UpdateCloseOutMainRequest;
use App\Rfa;
use App\Team;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class CloseOutMainController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('close_out_main_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = CloseOutMain::with([
                'construction_contract:id,code',
                'closeout_subject:id,subject',
                'closeout_urls:id,filename,url',
                'ref_rfas:id,origin_number',
                'team:id,name',
            ])->select(sprintf('%s.*', (new CloseOutMain)->table))
                ->orderBy('closeout_subject_id', 'asc');
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'close_out_main_show';
                $editGate      = 'close_out_main_edit';
                $deleteGate    = 'close_out_main_delete';
                $crudRoutePart = 'close-out-mains';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('status', function ($row) {
                if ($row->status == 'in_progress')
                    return sprintf('<p style="color:#ff9900"><b>%s</b></p>',$row->status ? CloseOutMain::STATUS_SELECT[$row->status] : '');
                else
                    return sprintf('<p style="color:#009933"><b>%s</b></p>',$row->status ? CloseOutMain::STATUS_SELECT[$row->status] : '');
            });
            $table->addColumn('construction_contract_code', function ($row) {
                return $row->construction_contract ? $row->construction_contract->code : '';
            });

            $table->addColumn('closeout_subject_subject', function ($row) {
                return $row->closeout_subject ? $row->closeout_subject->subject : '';
            });

            $table->editColumn('detail', function ($row) {
                return $row->detail ? $row->detail : '';
            });
            $table->editColumn('quantity', function ($row) {
                return $row->quantity ? $row->quantity : '';
            });
            $table->editColumn('ref_documents', function ($row) {
                return $row->ref_documents ? $row->ref_documents : '';
            });
            $table->editColumn('final_file', function ($row) {
                if (! $row->final_file) {
                    return '';
                }
                $links = [];
                foreach ($row->final_file as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });
            $table->editColumn('closeout_url', function ($row) {
                if ($row->closeout_urls->isEmpty()) {
                    return '';
                }

                $urls = $row->closeout_urls->map(function ($closeout_url) {
                    return [
                        'filename' => $closeout_url->filename,
                        'url'      => $closeout_url->url,
                    ];
                });

                $buttonLabel = trans('global.view');
                $dataAttribute = e(rawurlencode($urls->toJson()));
                $title = e($row->detail ?? $row->id);

                return '<button type="button" class="btn btn-info btn-xs view-closeout-urls" data-title="'
                    . $title . '" data-closeout-urls=\'' . $dataAttribute . '\'>' . $buttonLabel . '</button>';
            });
            

            $table->rawColumns(['actions', 'placeholder', 'construction_contract', 'closeout_subject', 'final_file', 'closeout_url', 'status']);

            return $table->make(true);
        }

        $construction_contracts = ConstructionContract::select('id', 'code')->orderBy('code')->get();
        $close_out_descriptions = CloseOutDescription::select('id', 'subject')->orderBy('id')->get();

        return view('admin.closeOutMains.index', compact('construction_contracts', 'close_out_descriptions'));
    }

    public function searchRefRfas(Request $request)
    {
        abort_if(Gate::denies('close_out_main_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $suggestionLimit = 5;

        $query = Rfa::query()->select('id', 'origin_number');

        if (Auth::id() != 1) {
            $query->where('construction_contract_id', session('construction_contract_id'));
        }

        if ($term = $request->input('term')) {
            $query->where('origin_number', 'like', '%' . $term . '%');
        }

        $results = $query
            ->latest('id')
            ->limit($suggestionLimit)
            ->get()
            ->map(function ($rfa) {
                return [
                    'id'   => $rfa->id,
                    'text' => $rfa->origin_number,
                ];
            })
            ->values();

        return response()->json([
            'results' => $results,
        ]);
    }

    public function create()
    {
        abort_if(Gate::denies('close_out_main_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if(Auth::id() != 1){
            $construction_contracts = ConstructionContract::where('id',session('construction_contract_id'))->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');
        }
        else{
            $construction_contracts = ConstructionContract::pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');
        }

        $closeout_subjects = CloseOutDescription::pluck('subject', 'id')->prepend(trans('global.pleaseSelect'), '');

        $closeout_urls = CloseOutDrive::pluck('filename', 'id');
        $close_out_drives       = CloseOutDrive::get();
        $teams                  = Team::get();


        return view('admin.closeOutMains.create', compact('closeout_subjects', 'closeout_urls', 'construction_contracts', 'close_out_drives', 'teams'));
    }

    public function store(StoreCloseOutMainRequest $request)
    {
        $closeOutMain = CloseOutMain::create($request->all());
        $this->syncCloseoutUrls($request, $closeOutMain);

        foreach ($request->input('final_file', []) as $file) {
            $closeOutMain->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('final_file');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $closeOutMain->id]);
        }

        return redirect()->route('admin.close-out-mains.index');
    }

    public function edit(CloseOutMain $closeOutMain)
    {
        abort_if(Gate::denies('close_out_main_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
       
        if(Auth::id() != 1){
            $construction_contracts = ConstructionContract::where('id',session('construction_contract_id'))->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');
        }
        else{
            $construction_contracts = ConstructionContract::pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');
        }


        $closeout_subjects = CloseOutDescription::pluck('subject', 'id')->prepend(trans('global.pleaseSelect'), '');

        $closeout_urls = CloseOutDrive::pluck('filename', 'id');


        $closeOutMain->load('construction_contract', 'closeout_subject', 'closeout_urls', 'team');

        return view('admin.closeOutMains.edit', compact('closeOutMain', 'closeout_subjects', 'closeout_urls', 'construction_contracts'));
    }

    public function update(UpdateCloseOutMainRequest $request, CloseOutMain $closeOutMain)
    {
        $closeOutMain->update($request->all());
        $this->syncCloseoutUrls($request, $closeOutMain);

        $media = $closeOutMain->final_file->pluck('file_name')->toArray();
        foreach ($request->input('final_file', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $closeOutMain->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('final_file');
            }
        }

        return redirect()->route('admin.close-out-mains.index');
    }

    public function show(CloseOutMain $closeOutMain)
    {
        abort_if(Gate::denies('close_out_main_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $closeOutMain->load('construction_contract', 'closeout_subject', 'closeout_urls', 'team');

        return view('admin.closeOutMains.show', compact('closeOutMain'));
    }

    protected function syncCloseoutUrls(Request $request, CloseOutMain $closeOutMain): void
    {
        $filenames = $request->input('closeout_url_filename', []);
        $urls = $request->input('closeout_url_url', []);
        $ids = $request->input('closeout_url_ids', []);
        $contractId = $request->input('construction_contract_id') ?: $closeOutMain->construction_contract_id;

        $syncedIds = [];

        foreach ($filenames as $index => $filename) {
            $name = trim($filename ?? '');
            $url = trim($urls[$index] ?? '');
            $existingId = $ids[$index] ?? null;

            if ($name === '' && $url === '') {
                continue;
            }

            if ($existingId) {
                $drive = CloseOutDrive::find($existingId);
                if ($drive) {
                    $drive->update([
                        'filename' => $name ?: $drive->filename,
                        'url'      => $url,
                        'construction_contract_id' => $contractId,
                    ]);
                }
            } else {
                $drive = CloseOutDrive::create([
                    'filename' => $name ?: trans('cruds.closeOutMain.fields.closeout_url'),
                    'url'      => $url,
                    'team_id'  => auth()->user()->team_id ?? null,
                    'construction_contract_id' => $contractId,
                ]);
            }

            if (isset($drive)) {
                $syncedIds[] = $drive->id;
            }
        }

        $closeOutMain->closeout_urls()->sync($syncedIds);
    }

    public function destroy(CloseOutMain $closeOutMain)
    {
        abort_if(Gate::denies('close_out_main_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $closeOutMain->delete();

        return back();
    }

    public function massDestroy(MassDestroyCloseOutMainRequest $request)
    {
        $closeOutMains = CloseOutMain::find(request('ids'));

        foreach ($closeOutMains as $closeOutMain) {
            $closeOutMain->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('close_out_main_create') && Gate::denies('close_out_main_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new CloseOutMain();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
