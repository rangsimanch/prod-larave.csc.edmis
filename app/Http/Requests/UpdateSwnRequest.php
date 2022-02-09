<?php

namespace App\Http\Requests;

use App\Swn;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateSwnRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('swn_edit');
    }

    public function rules()
    {
        return [
            'construction_contract_id' => [
                'required',
                'integer',
            ],
            'title' => [
                'string',
                'required',
            ],
            'dept_code_id' => [
                'required',
                'integer',
            ],
            'submit_date' => [
                'required',
                'date_format:' . config('panel.date_format'),
            ],
            'location' => [
                'string',
                'nullable',
            ],
            'document_attachment' => [
                'array',
            ],
            'description_image' => [
                'array',
            ],
            'issue_by_id' => [
                'required',
                'integer',
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
        ];
    }
}
