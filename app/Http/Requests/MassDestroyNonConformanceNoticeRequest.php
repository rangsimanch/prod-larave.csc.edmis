<?php

namespace App\Http\Requests;

use App\NonConformanceNotice;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyNonConformanceNoticeRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('non_conformance_notice_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:non_conformance_notices,id',
        ];
    }
}
