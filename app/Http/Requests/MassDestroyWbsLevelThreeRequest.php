<?php

namespace App\Http\Requests;

use App\WbsLevelThree;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyWbsLevelThreeRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('wbs_level_three_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:wbs_level_threes,id',
        ];
    }
}
