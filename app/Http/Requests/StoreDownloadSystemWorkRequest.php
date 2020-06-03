<?php

namespace App\Http\Requests;

use App\DownloadSystemWork;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreDownloadSystemWorkRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('download_system_work_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [];
    }
}
