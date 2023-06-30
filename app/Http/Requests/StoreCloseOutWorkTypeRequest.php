<?php

namespace App\Http\Requests;

use App\CloseOutWorkType;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreCloseOutWorkTypeRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('close_out_work_type_create');
    }

    public function rules()
    {
        return [
            'work_type_name' => [
                'string',
                'required',
            ],
            'work_type_code' => [
                'string',
                'nullable',
            ],
            'construction_contract_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
