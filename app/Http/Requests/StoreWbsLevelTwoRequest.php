<?php

namespace App\Http\Requests;

use App\WbsLevelTwo;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreWbsLevelTwoRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('wbs_level_two_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [];
    }
}
