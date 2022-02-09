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
            'dept_code_id' => [
                'required',
                'integer',
            ],
            'title' => [
                'string',
                'required',
            ],
            'issue_date' => [
                'required',
                'date_format:' . config('panel.date_format'),
            ],
            'document_number' => [
                'string',
                'nullable',
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
