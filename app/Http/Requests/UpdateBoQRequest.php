<?php

namespace App\Http\Requests;

use App\BoQ;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateBoQRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('bo_q_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [];
    }
}
