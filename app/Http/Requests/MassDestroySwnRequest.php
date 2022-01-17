<?php

namespace App\Http\Requests;

use App\Swn;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroySwnRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('swn_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:swns,id',
        ];
    }
}
