<?php

namespace App\Http\Requests;

use App\InterimPaymentMeeting;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateInterimPaymentMeetingRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('interim_payment_meeting_edit');
    }

    public function rules()
    {
        return [
            'meeting_name' => [
                'string',
                'nullable',
            ],
            'meeting_no' => [
                'string',
                'nullable',
            ],
            'date' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'file_upload' => [
                'array',
            ],
        ];
    }
}
