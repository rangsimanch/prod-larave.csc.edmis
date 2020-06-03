<?php

namespace App\Http\Requests;

use App\DownloadSystemWork;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateDownloadSystemWorkRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('download_system_work_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [];
    }
}
