<?php

namespace App\Http\Requests;

use App\ContractAndComponent;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateContractAndComponentRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('contract_and_component_edit');
    }

    public function rules()
    {
        return [
            'document_name' => [
                'string',
                'nullable',
            ],
            'document_code' => [
                'string',
                'nullable',
            ],
        ];
    }
}
