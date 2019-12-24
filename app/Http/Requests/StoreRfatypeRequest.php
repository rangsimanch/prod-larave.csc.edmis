<?php

namespace App\Http\Requests;

use App\Rfatype;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreRfatypeRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('rfatype_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
        ];
    }
}
