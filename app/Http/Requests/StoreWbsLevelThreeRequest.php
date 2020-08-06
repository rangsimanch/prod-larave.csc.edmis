<?php

namespace App\Http\Requests;

use App\WbsLevelThree;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreWbsLevelThreeRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('wbs_level_three_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [];
    }
}
