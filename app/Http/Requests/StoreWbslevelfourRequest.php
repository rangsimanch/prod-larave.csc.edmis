<?php

namespace App\Http\Requests;

use App\Wbslevelfour;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreWbslevelfourRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('wbslevelfour_create');
    }

    public function rules()
    {
        return [
            'wbs_level_4_name' => [
                'string',
                'nullable',
            ],
            'wbs_level_4_code' => [
                'string',
                'nullable',
            ],
        ];
    }
}
