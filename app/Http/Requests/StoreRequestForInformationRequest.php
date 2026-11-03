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
            'signatory_id' => [
                'nullable',
                'integer',
                \Illuminate\Validation\Rule::exists('authorized_signatories', 'id')->where('construction_contract_id', $this->input('construction_contract_id'))],
            'signatory_2_id' => [
                'nullable',
                'integer',
                'different:signatory_id',
                \Illuminate\Validation\Rule::exists('authorized_signatories', 'id')->where('construction_contract_id', $this->input('construction_contract_id'))],
            'signatory_jobtitle_id' => [
                'nullable',
                'integer',
                \Illuminate\Validation\Rule::exists('authorized_signatory_jobtitles', 'id')->where('authorized_signatory_id', $this->input('signatory_id'))],
            'signatory_jobtitle_2_id' => [
                'nullable',
                'integer',
                \Illuminate\Validation\Rule::exists('authorized_signatory_jobtitles', 'id')->where('authorized_signatory_id', $this->input('signatory_2_id'))],
        ];
    }
}
