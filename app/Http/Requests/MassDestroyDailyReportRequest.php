<?php

namespace App\Http\Requests;

use App\DailyReport;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyDailyReportRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('daily_report_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;

    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:daily_reports,id',
        ];

    }
}
