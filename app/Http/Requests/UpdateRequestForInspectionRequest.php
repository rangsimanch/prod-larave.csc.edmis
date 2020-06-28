<?php

namespace App\Http\Requests;

use App\RequestForInspection;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateRequestForInspectionRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('request_for_inspection_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'inspection_date_time' => [
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
                'nullable',
            ],
        ];
    }
}
