<?php

namespace App\Http\Requests;

use App\MeetingMonthly;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyMeetingMonthlyRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('meeting_monthly_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;

    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:meeting_monthlies,id',
        ];

    }
}
