<?php

namespace App\Http\Requests;

use App\RequestForInformation;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateRequestForInformationRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('request_for_information_edit');
    }

    public function rules()
    {
        return [
            'originator_code' => [
                'string',
                'nullable',
            ],
            'outgoing_date'   => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'outgoing_no'     => [
                'string',
                'nullable',
            ],
            'response_date'   => [
                'string',
                'nullable',
            ],
        ];
    }
}
