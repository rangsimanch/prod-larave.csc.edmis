<?php

namespace App\Http\Requests;

use App\Ncr;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreNcrRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('ncr_create');
    }

    public function rules()
    {
        return [
            'construction_contract_id' => [
                'required',
                'integer',
            ],
            'document_number' => [
                'string',
                'nullable',
            ],
            'acceptance_date' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'rootcase_image' => [
                'array',
            ],
            'containment_image' => [
                'array',
            ],
            'corrective_image' => [
                'array',
            ],
            'pages_of_attachment' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'file_attachment' => [
                'array',
            ],
        ];
    }
}
