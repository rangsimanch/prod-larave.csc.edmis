<?php

namespace App\Http\Requests;

use App\FileManager;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreFileManagerRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('file_manager_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
        ];
    }
}
