<?php

namespace App\Http\Requests;

use App\FileManager;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyFileManagerRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('file_manager_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:file_managers,id',
        ];
    }
}
