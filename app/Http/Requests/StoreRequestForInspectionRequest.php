<?php

namespace App\Http\Requests;

use App\RequestForInspection;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreRequestForInspectionRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('request_for_inspection_create');
    }

    public function rules()
    {
        return [
            'construction_contract_id' => [
                'required',
                'integer',
            ],
            'subject'                  => [
                'string',
                'nullable',
            ],
            'ref_no'                   => [
                'string',
                'nullable',
            ],
            'location'                 => [
                'string',
                'nullable',
            ],
            'submittal_date'           => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'replied_date'             => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'ipa'                      => [
                'string',
                'nullable',
            ],
            'end_loop'                 => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
        ];
    }
}
