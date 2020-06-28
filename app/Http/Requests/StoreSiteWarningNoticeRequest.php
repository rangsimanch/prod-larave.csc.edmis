<?php

namespace App\Http\Requests;

use App\SiteWarningNotice;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreSiteWarningNoticeRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('site_warning_notice_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'submit_date'                 => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'containment_completion_date' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'corrective_completion_date'  => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
        ];
    }
}
