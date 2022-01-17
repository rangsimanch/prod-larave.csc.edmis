<?php

namespace App\Http\Requests;

use App\Ncn;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreNcnRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('ncn_create');
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
                'required',
            ],
            'issue_date' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'title' => [
                'string',
                'required',
            ],
            'description_image' => [
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
            'acceptance_date' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
        ];
    }
}
