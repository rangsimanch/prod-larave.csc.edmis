<?php

namespace App\Http\Requests;

use App\WbsLevelFive;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateWbsLevelFiveRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('wbs_level_five_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'nullable',
            ],
            'code' => [
                'string',
                'nullable',
            ],
        ];
    }
}
