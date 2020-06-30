<?php

namespace App\Http\Requests;

use App\NonConformanceNotice;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateNonConformanceNoticeRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('non_conformance_notice_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'submit_date' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
        ];
    }
}
