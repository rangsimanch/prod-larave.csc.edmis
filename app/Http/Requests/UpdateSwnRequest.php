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
            'document_number' => [
                'string',
                'required',
            ],
            'submit_date' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
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
