<?php

namespace App\Http\Requests;

use App\AsBuildPlan;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyAsBuildPlanRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('as_build_plan_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:as_build_plans,id',
        ];
    }
}
