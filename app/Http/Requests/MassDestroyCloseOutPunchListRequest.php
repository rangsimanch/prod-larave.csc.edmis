<?php

namespace App\Http\Requests;

use App\CloseOutPunchList;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyCloseOutPunchListRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('close_out_punch_list_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:close_out_punch_lists,id',
        ];
    }
}
