<?php

namespace App\Http\Requests;

use App\Wbslevelfour;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateWbslevelfourRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('wbslevelfour_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
        ];
    }
}
