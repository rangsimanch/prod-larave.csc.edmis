<?php

namespace App\Http\Requests;

use App\MeetingMonthly;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateMeetingMonthlyRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('meeting_monthly_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;

    }

    public function rules()
    {
        return [
            'date' => [
                'date_format:' . config('panel.date_format'),
                'nullable'],
        ];

    }
}
