<?php

namespace App\Http\Requests;

use App\WbsLevelOne;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateWbsLevelOneRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('wbs_level_one_edit');
    }

    public function rules()
    {
        return [
            'name'        => [
                'string',
                'nullable',
            ],
            'code'        => [
                'string',
                'nullable',
            ],
            'wbs_lv_1_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
