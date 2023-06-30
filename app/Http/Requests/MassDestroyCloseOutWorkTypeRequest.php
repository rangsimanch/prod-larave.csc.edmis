<?php

namespace App\Http\Requests;

use App\CloseOutWorkType;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyCloseOutWorkTypeRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('close_out_work_type_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:close_out_work_types,id',
        ];
    }
}
