<?php

namespace App\Http\Requests;

use App\RequestForInformation;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreRequestForInformationRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('request_for_information_create');
    }

    public function rules()
    {
        return [
            'title'           => [
                'string',
                'required',
            ],
            'document_no'     => [
                'string',
                'nullable',
            ],
            'originator_code' => [
                'string',
                'nullable',
            ],
            'date'            => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'incoming_date'   => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
        ];
    }
}
