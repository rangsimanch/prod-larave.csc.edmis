<?php

namespace App\Http\Requests;

use App\MonthlyReportConstructor;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreMonthlyReportConstructorRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('monthly_report_constructor_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'for_month' => [
                'required',
                'date_format:' . config('panel.date_format'),
            ],
        ];
    }
}
