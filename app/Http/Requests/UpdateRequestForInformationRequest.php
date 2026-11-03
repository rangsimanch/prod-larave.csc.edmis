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
            'signatory_id' => [
                'nullable',
                'integer',
                \Illuminate\Validation\Rule::exists('authorized_signatories', 'id')->where('construction_contract_id', optional($this->route('request_for_information'))->construction_contract_id)],
            'signatory_2_id' => [
                'nullable',
                'integer',
                'different:signatory_id',
                \Illuminate\Validation\Rule::exists('authorized_signatories', 'id')->where('construction_contract_id', optional($this->route('request_for_information'))->construction_contract_id)],
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
