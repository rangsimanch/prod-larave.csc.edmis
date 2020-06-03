<?php

namespace App\Http\Requests;

use App\DownloadSystemWork;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyDownloadSystemWorkRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('download_system_work_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:download_system_works,id',
        ];
    }
}
