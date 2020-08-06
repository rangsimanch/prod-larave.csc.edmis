<?php

namespace App\Http\Requests;

use App\RequestForInspection;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreRequestForInspectionRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('request_for_inspection_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'items.*'        => [
                'integer',
            ],
            'items'          => [
                'array',
            ],
            'submittal_date' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'replied_date'   => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'start_loop'     => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'end_loop'       => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
        ];
    }
}
