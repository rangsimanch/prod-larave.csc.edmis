<?php

namespace App\Http\Requests;

use App\CloseOutDescription;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreCloseOutDescriptionRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('close_out_description_create');
    }

    public function rules()
    {
        return [
            'subject' => [
                'string',
                'max:80',
                'required',
            ],
        ];
    }
}
