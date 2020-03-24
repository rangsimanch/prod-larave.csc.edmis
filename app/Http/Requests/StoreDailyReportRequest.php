<?php

namespace App\Http\Requests;

use App\DailyReport;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreDailyReportRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('daily_report_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;

    }

    public function rules()
    {
        return [
            'input_date' => [
                'date_format:' . config('panel.date_format'),
                'nullable'],
        ];

    }
}
