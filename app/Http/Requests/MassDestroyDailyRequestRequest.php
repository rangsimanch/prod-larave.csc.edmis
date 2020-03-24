<?php

namespace App\Http\Requests;

use App\DailyRequest;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyDailyRequestRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('daily_request_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;

    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:daily_requests,id',
        ];

    }
}
