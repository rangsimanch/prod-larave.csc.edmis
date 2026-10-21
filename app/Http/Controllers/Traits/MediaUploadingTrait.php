<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

trait MediaUploadingTrait
{
    public function storeMedia(Request $request)
    {
        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));

        if ($receiver->isUploaded() === false) {
            return response()->json(['error' => 'No file uploaded'], 422);
        }

        $save = $receiver->receive();

        if (!$save->isFinished()) {
            return response()->json([
                'done' => $save->handler()->getPercentageDone(),
            ]);
        }

        $file = $save->getFile();

        $this->validateMediaFile($request, $file);

        $path = storage_path('tmp/uploads');

        try {
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }
        } catch (\Exception $e) {
        }

        $name = uniqid() . '_' . trim($file->getClientOriginalName());

        $file->move($path, $name);

        return response()->json([
            'name'          => $name,
            'original_name' => $file->getClientOriginalName(),
        ]);
    }

    protected function validateMediaFile(Request $request, $file)
    {
        if ($request->has('size')) {
            Validator::make(['file' => $file], [
                'file' => 'max:' . $request->input('size') * 1024,
            ])->validate();
        }

        if ($request->has('width') || $request->has('height')) {
            Validator::make(['file' => $file], [
                'file' => sprintf(
                    'image|dimensions:max_width=%s,max_height=%s',
                    $request->input('width', 100000),
                    $request->input('height', 100000)
                ),
            ])->validate();
        }
    }
}
