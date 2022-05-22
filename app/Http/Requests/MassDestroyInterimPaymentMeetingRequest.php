<?php

namespace App\Http\Requests;

use App\InterimPaymentMeeting;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyInterimPaymentMeetingRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('interim_payment_meeting_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:interim_payment_meetings,id',
        ];
    }
}
