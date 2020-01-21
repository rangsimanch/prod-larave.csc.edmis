<?php

namespace App\Http\Requests;

use App\WorksCode;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateWorksCodeRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('works_code_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
        ];
    }
}
