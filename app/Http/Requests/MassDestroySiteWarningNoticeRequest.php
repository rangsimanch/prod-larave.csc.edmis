<?php

namespace App\Http\Requests;

use App\SiteWarningNotice;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroySiteWarningNoticeRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('site_warning_notice_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:site_warning_notices,id',
        ];
    }
}
