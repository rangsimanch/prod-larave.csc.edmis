<?php

namespace App\Http\Requests;

use App\DailyConstructionActivity;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyDailyConstructionActivityRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('daily_construction_activity_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:daily_construction_activities,id',
        ];
    }
}
