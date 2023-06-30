<?php

namespace App\Http\Requests;

use App\CloseOutLocation;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateCloseOutLocationRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('close_out_location_edit');
    }

    public function rules()
    {
        return [
            'location_name' => [
                'string',
                'required',
            ],
            'location_code' => [
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
