<?php

namespace App\Http\Requests;

use App\NonConformanceReport;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateNonConformanceReportRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('non_conformance_report_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'response_date' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
        ];
    }
}
