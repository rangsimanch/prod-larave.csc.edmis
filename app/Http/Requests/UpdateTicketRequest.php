<?php

namespace App\Http\Requests;

use App\Ticket;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateTicketRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('ticket_edit');
    }

    public function rules()
    {
        return [
            'subject'      => [
                'string',
                'required',
            ],
            'request_type' => [
                'required',
            ],
            'module'       => [
                'required',
            ],
            'detail'       => [
                'required',
            ],
        ];
    }
}
