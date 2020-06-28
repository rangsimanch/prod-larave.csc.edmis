<?php

namespace App\Http\Requests;

use App\WbsLevelFive;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateWbsLevelFiveRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('wbs_level_five_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [];
    }
}
