<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyRecoveryFileRequest;
use App\Http\Requests\StoreRecoveryFileRequest;
use App\Http\Requests\UpdateRecoveryFileRequest;
use App\RecoveryFile;
use App\Team;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use File;
use Illuminate\Support\Facades\Log;

class RecoveryFilesController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('recovery_file_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = RecoveryFile::with(['team'])->select(sprintf('%s.*', (new RecoveryFile())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'recovery_file_show';
                $editGate = 'recovery_file_edit';
                $deleteGate = 'recovery_file_delete';
                $crudRoutePart = 'recovery-files';

                return view('partials.datatablesActions', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'row'
            ));
            });

            $table->editColumn('recovery_success', function ($row) {
                return $row->recovery_success ? $row->recovery_success : '';
            });
            $table->editColumn('recovery_fail', function ($row) {
                return $row->recovery_fail ? $row->recovery_fail : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        $teams = Team::get();

        return view('admin.recoveryFiles.index', compact('teams'));
    }

    public function create()
    {
        abort_if(Gate::denies('recovery_file_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.recoveryFiles.create');
    }

    public function store(StoreRecoveryFileRequest $request)
    {
        $success_file = "";
        $fail_file = "";

        foreach ($request->input('recovery_file', []) as $file) {
            $filename = basename($file).PHP_EOL;
            $tmp_ext = explode('.', $filename);
            $extension_end = end($tmp_ext);
            $extension = strtolower($extension_end);
            
            $mime_type = "none_type";
            if(strstr($extension, "docx")){
                $mime_type = "application/vnd.openxmlformats-officedocument.wordprocessingml.document";
            }
            if(strstr($extension, "pdf")){
                $mime_type = "application/pdf";
            }
            if(strstr($extension, "png")){
                $mime_type = "image/png";
            }
            if(strstr($extension,"xlsx")){
                $mime_type = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
            }
            if(strstr($extension, "txt") || strstr($extension, "gsi") || strstr($extension, "csv")){
                $mime_type = "text/plain";
            }
            if(strstr($extension, "jpg") || strstr($extension, "jpeg")){
                $mime_type == "image/jpeg";
            }
            if(strstr($extension, "zip") || strstr($extension, "xps")){
                $mime_type == "application/zip";
            }
            if(strstr($extension, "xls")){
                $mime_type = "application/vnd.ms-excel";
            }
            if(strstr($extension,"pptx")){
                $mime_type = "application/vnd.openxmlformats-officedocument.presentationml.presentation";
            }
            if(strstr($extension, "mp4")){
                $mime_type = "video/mp4";
            }
            if(strstr($extension, "mov")){
                $mime_type = "video/quicktime";
            }
            if(strstr($extension, "rar")){
                $mime_type = "application/x-rar";
            }
            if(strstr($extension, "lnk") || strstr($extension, "heic") || strstr($extension, "dwg")){
                $mime_type = "application/octet-stream";
            }
            if(strstr($extension, "tif")){
                $mime_type = "image/tiff";
            }
            if(strstr($extension, "gif")){
                $mime_type = "image/gif";
            }
            if(strstr($extension, "bmp")){
                $mime_type = "image/x-ms-bmp";
            }
            $size = filesize(storage_path('tmp/uploads/' . basename($file)));

            Log::alert("File MS = " . $extension . " | " . $size . " | " . $mime_type);

            $dir_id = DB::table('media')
                ->where('mime_type', '=', $mime_type)
                ->where('size', '=', $size)
                ->pluck('id')->toArray();
            $original_name = DB::table('media')
                ->where('mime_type', '=', $mime_type)
                ->where('size', '=', $size)
                ->pluck('file_name')->toArray();


            if(count($dir_id) == 1){
                $defualt_file = storage_path('tmp/uploads/' . basename($file));
                $rename_file = storage_path('tmp/uploads/' . $original_name[0]);
                rename($defualt_file, $rename_file);
                if(!file_exists(storage_path("/" . "public" . "/" . $dir_id[0]))){
                    Storage::disk('local')->putFileAs("/" . "public" . "/" . $dir_id[0], $rename_file, $original_name[0]);
                    File::delete($rename_file);
                    $success_file .= $original_name[0] . ", ";
                }
                
            }
            else{
                $fail_file .= $filename . ", ";
            }
        }
        $data = $request->all();
        $data['recovery_success'] = $success_file;
        $data['recovery_fail'] = $fail_file;
        $recoveryFile = RecoveryFile::create($data);

        // foreach ($request->input('recovery_file', []) as $file) {
        //     $recoveryFile->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('recovery_file');
        // }

        // if ($media = $request->input('ck-media', false)) {
        //     Media::whereIn('id', $media)->update(['model_id' => $recoveryFile->id]);
        // }

        return redirect()->route('admin.recovery-files.index');
    }

    public function edit(RecoveryFile $recoveryFile)
    {
        abort_if(Gate::denies('recovery_file_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $recoveryFile->load('team');

        return view('admin.recoveryFiles.edit', compact('recoveryFile'));
    }

    public function update(UpdateRecoveryFileRequest $request, RecoveryFile $recoveryFile)
    {
        $recoveryFile->update($request->all());

        if (count($recoveryFile->recovery_file) > 0) {
            foreach ($recoveryFile->recovery_file as $media) {
                if (!in_array($media->file_name, $request->input('recovery_file', []))) {
                    $media->delete();
                }
            }
        }
        $media = $recoveryFile->recovery_file->pluck('file_name')->toArray();
        foreach ($request->input('recovery_file', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $recoveryFile->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('recovery_file');
            }
        }

        return redirect()->route('admin.recovery-files.index');
    }

    public function show(RecoveryFile $recoveryFile)
    {
        abort_if(Gate::denies('recovery_file_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $recoveryFile->load('team');

        return view('admin.recoveryFiles.show', compact('recoveryFile'));
    }

    public function destroy(RecoveryFile $recoveryFile)
    {
        abort_if(Gate::denies('recovery_file_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $recoveryFile->delete();

        return back();
    }

    public function massDestroy(MassDestroyRecoveryFileRequest $request)
    {
        RecoveryFile::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('recovery_file_create') && Gate::denies('recovery_file_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new RecoveryFile();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}