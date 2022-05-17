<?php

namespace App\Http\Requests;

use App\LetterSubjectType;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreLetterSubjectTypeRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('letter_subject_type_create');
    }

    public function rules()
    {
        return [
            'subject_name' => [
                'string',
                'required',
            ],
        ];
    }
}
