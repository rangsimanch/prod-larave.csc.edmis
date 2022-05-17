<?php

namespace App\Http\Requests;

use App\LetterSubjectType;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyLetterSubjectTypeRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('letter_subject_type_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:letter_subject_types,id',
        ];
    }
}
