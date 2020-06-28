<?php

namespace App\Http\Requests;

use App\WbsLevelOne;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreWbsLevelOneRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('wbs_level_one_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [];
    }
}
