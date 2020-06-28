<?php

namespace App\Http\Requests;

use App\RequestForInformation;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreRequestForInformationRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('request_for_information_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'date'          => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'title'         => [
                'required',
            ],
            'incoming_date' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'outgoing_date' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
        ];
    }
}
