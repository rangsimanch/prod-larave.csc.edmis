<?php

namespace App\Http\Requests;

use App\MonthlyReportCsc;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyMonthlyReportCscRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('monthly_report_csc_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:monthly_report_cscs,id',
        ];
    }
}
