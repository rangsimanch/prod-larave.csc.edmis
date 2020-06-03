<?php

namespace App\Http\Requests;

use App\AddLetter;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateAddLetterRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('add_letter_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'sender_id'     => [
                'required',
                'integer',
            ],
            'sent_date'     => [
                'required',
                'date_format:' . config('panel.date_format'),
            ],
            'receiver_id'   => [
                'required',
                'integer',
            ],
            'received_date' => [
                'required',
                'date_format:' . config('panel.date_format'),
            ],
        ];
    }
}
