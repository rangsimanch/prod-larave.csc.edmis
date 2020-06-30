<?php

namespace App\Http\Requests;

use App\NonConformanceReport;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyNonConformanceReportRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('non_conformance_report_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:non_conformance_reports,id',
        ];
    }
}
