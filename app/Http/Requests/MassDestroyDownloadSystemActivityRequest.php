<?php

namespace App\Http\Requests;

use App\DownloadSystemActivity;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyDownloadSystemActivityRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('download_system_activity_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:download_system_activities,id',
        ];
    }
}
