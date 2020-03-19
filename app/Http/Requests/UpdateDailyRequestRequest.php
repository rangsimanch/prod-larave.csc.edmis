<?php

namespace App\Http\Requests;

use App\DailyRequest;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateDailyRequestRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('daily_request_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;

    }

    public function rules()
    {
        return [
            'input_date' => [
                'date_format:' . config('panel.date_format'),
                'nullable'],
        ];

    }
}
