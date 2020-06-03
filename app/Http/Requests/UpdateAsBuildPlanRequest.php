<?php

namespace App\Http\Requests;

use App\AsBuildPlan;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateAsBuildPlanRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('as_build_plan_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'drawing_references.*' => [
                'integer',
            ],
            'drawing_references'   => [
                'array',
            ],
        ];
    }
}
