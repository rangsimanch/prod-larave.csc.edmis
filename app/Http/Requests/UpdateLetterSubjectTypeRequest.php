<?php

namespace App\Http\Requests;

use App\LetterSubjectType;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateLetterSubjectTypeRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('letter_subject_type_edit');
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
