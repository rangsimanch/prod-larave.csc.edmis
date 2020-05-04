<?php

namespace App\Http\Requests;

use App\Manual;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreManualRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('manual_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;

    }

    public function rules()
    {
        return [
        ];

    }
}
