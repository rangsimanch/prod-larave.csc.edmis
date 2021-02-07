<?php

namespace App\Http\Requests;

use App\WbsLevelThree;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateWbsLevelThreeRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('wbs_level_three_edit');
    }

    public function rules()
    {
        return [
            'wbs_level_3_name' => [
                'string',
                'nullable',
            ],
            'wbs_level_3_code' => [
                'string',
                'nullable',
            ],
        ];
    }
}
