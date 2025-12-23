<?php

namespace App\Http\Requests;

use App\CloseOutDescription;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateCloseOutDescriptionRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('close_out_description_edit');
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
