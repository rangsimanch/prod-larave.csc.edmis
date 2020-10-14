<?php

namespace App\Http\Controllers\Admin;

use App\ConstructionContract;
use App\DailyConstructionActivity;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyDailyConstructionActivityRequest;
use App\Http\Requests\StoreDailyConstructionActivityRequest;
use App\Http\Requests\UpdateDailyConstructionActivityRequest;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class DailyConstructionActivitiesController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('daily_construction_activity_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = DailyConstructionActivity::with(['construction_contract', 'team'])->select(sprintf('%s.*', (new DailyConstructionActivity)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'daily_construction_activity_show';
                $editGate      = 'daily_construction_activity_edit';
                $deleteGate    = 'daily_construction_activity_delete';
                $crudRoutePart = 'daily-construction-activities';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : "";
            });

            $table->editColumn('work_title', function ($row) {
                return $row->work_title ? $row->work_title : "";
            });

            $table->addColumn('construction_contract_code', function ($row) {
                return $row->construction_contract ? $row->construction_contract->code : '';
            });

            $table->editColumn('image_upload', function ($row) {
                if (!$row->image_upload) {
                    return '';
                }

                $links = [];
                $links[] = '<button id="gallery'. $row->id .'" data-toggle="modal" data-target="#exampleModal'. $row->id .'" class="btn btn-light" type="button"> Show Gallery </button>';
                // $links[] = '<div id="gallery'. $row->id .'" data-toggle="modal" data-target="#exampleModal'. $row->id .'">';
                // // $links[] = '<div id="gallery" data-toggle="modal" data-target="#exampleModal"><a class="btn-xs btn-info">View</a>';
                
                // foreach ($row->image_upload as $index => $media) {
                //         // $links[] = '<a href="' . $media->getUrl() . '" target="_blank"><img src="' . $media->getUrl('thumb') . '" width="50px" height="50px"></a>';      
                //         $links[] = '<img class="w-100" src="' . $media->getUrl('thumb') . '" width="30px" height="30px"
                //         data-target="#carouselExample'. $row->id .'" data-slide-to="'. $index .'">';      
                //     }
                // $links[] = '</div>';
                    
                $links[] = '<div class="modal fade" id="exampleModal'. $row->id .'" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      ' . $row->work_title .  '
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <div id="carouselExample'. $row->id .'" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">';
                
                foreach ($row->image_upload as $index => $media) {
                    if($index == 0){
                        $links[] = '<li data-target="#carouselExample'. $row->id .'" data-slide-to="'. $index .'" class="active"></li>';
                    }
                    else{
                        $links[] = '<li data-target="#carouselExample'. $row->id .'" data-slide-to="'. $index .'"></li>';
                    }
                }

                $links[] = '</ol> <div class="carousel-inner">';

                foreach ($row->image_upload as $index => $media) {
                    if($index == 0){
                        $links[] = '<div class="item active">
                        <div class="descPhoto">' . ($index+1) .' of '. count($row->image_upload) .'</div>
                        <img class="d-block w-100" src="'. $media->getUrl() .'">
                        </div>';
                    }
                    else{
                        $links[] = '<div class="item">
                        <div class="descPhoto">' . ($index+1) .' of '. count($row->image_upload) .'</div>
                        <img class="d-block w-100" src="'. $media->getUrl() .'">
                        </div>';
                    }
                    
                }
                
                $links[] = '</div>
                <a class="left carousel-control" href="#carouselExample'. $row->id .'" role="button" data-slide="prev">
                  <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                  <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#carouselExample'. $row->id .'" role="button" data-slide="next">
                  <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                  <span class="sr-only">Next</span>
                </a>
              </div>
            </div>
            <div class="modal-footer">';
       


            $links[] =' <a class="btn btn-success" href="' . route('admin.' . 'daily-construction-activities' . '.ZipFile', $row->id) . '">'
            . "Download All" . 
            '</a>';

            $links[] = 
            '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
          </div>
        </div>';


                    return implode(' ', $links);
                
                //   return $links;
            });
            
            $table->rawColumns(['actions', 'placeholder', 'construction_contract', 'image_upload']);
            
            return $table->make(true);
            
        }

        return view('admin.dailyConstructionActivities.index');
    }

    public function ZipFile(DailyConstructionActivity $request){
    
            $zip_file = str_replace('/','',$request->operation_date) . '.zip';
            $zip = new \ZipArchive();
            
            if($zip->open($zip_file, \ZipArchive::CREATE) === TRUE){
            
                foreach ($request->image_upload as $index => $media) {
                    $filePath  = $media->getPath();
                    $filename = $index+1 . '.jpg';
                    $zip->addFile($filePath, $filename);   
                }

                $zip->close();
            }

            //  return response()->download(public_path($zip_file))->deleteFileAfterSend(true);
             return serialize($zip->numFiles);
    }

    public function create()
    {
        abort_if(Gate::denies('daily_construction_activity_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.dailyConstructionActivities.create', compact('construction_contracts'));
    }

    public function store(StoreDailyConstructionActivityRequest $request)
    {
        $dailyConstructionActivity = DailyConstructionActivity::create($request->all());

        foreach ($request->input('image_upload', []) as $file) {
            $dailyConstructionActivity->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('image_upload');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $dailyConstructionActivity->id]);
        }

        return redirect()->route('admin.daily-construction-activities.index');
    }

    public function edit(DailyConstructionActivity $dailyConstructionActivity)
    {
        abort_if(Gate::denies('daily_construction_activity_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $dailyConstructionActivity->load('construction_contract', 'team');

        return view('admin.dailyConstructionActivities.edit', compact('construction_contracts', 'dailyConstructionActivity'));
    }

    public function update(UpdateDailyConstructionActivityRequest $request, DailyConstructionActivity $dailyConstructionActivity)
    {
        $dailyConstructionActivity->update($request->all());

        if (count($dailyConstructionActivity->image_upload) > 0) {
            foreach ($dailyConstructionActivity->image_upload as $media) {
                if (!in_array($media->file_name, $request->input('image_upload', []))) {
                    $media->delete();
                }
            }
        }

        $media = $dailyConstructionActivity->image_upload->pluck('file_name')->toArray();

        foreach ($request->input('image_upload', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $dailyConstructionActivity->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('image_upload');
            }
        }

        return redirect()->route('admin.daily-construction-activities.index');
    }

    public function show(DailyConstructionActivity $dailyConstructionActivity)
    {
        abort_if(Gate::denies('daily_construction_activity_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dailyConstructionActivity->load('construction_contract', 'team');

        return view('admin.dailyConstructionActivities.show', compact('dailyConstructionActivity'));
    }

    public function destroy(DailyConstructionActivity $dailyConstructionActivity)
    {
        abort_if(Gate::denies('daily_construction_activity_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dailyConstructionActivity->delete();

        return back();
    }

    public function massDestroy(MassDestroyDailyConstructionActivityRequest $request)
    {
        DailyConstructionActivity::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('daily_construction_activity_create') && Gate::denies('daily_construction_activity_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new DailyConstructionActivity();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
