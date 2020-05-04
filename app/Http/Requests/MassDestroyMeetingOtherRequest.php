<?php

namespace App\Http\Requests;

use App\MeetingOther;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyMeetingOtherRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('meeting_other_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;

    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:meeting_others,id',
        ];

    }
}
