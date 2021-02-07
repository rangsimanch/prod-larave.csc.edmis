<?php

namespace App\Http\Requests;

use App\Complaint;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreComplaintRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('complaint_create');
    }

    public function rules()
    {
        return [
            'construction_contract_id' => [
                'required',
                'integer',
            ],
            'document_number'          => [
                'string',
                'required',
            ],
            'complaint_recipient_id'   => [
                'required',
                'integer',
            ],
            'received_date'            => [
                'required',
                'date_format:' . config('panel.date_format'),
            ],
            'source_code'              => [
                'required',
            ],
            'complainant'              => [
                'string',
                'nullable',
            ],
            'complainant_tel'          => [
                'string',
                'nullable',
            ],
            'complainant_detail'       => [
                'string',
                'nullable',
            ],
            'type_code'                => [
                'required',
            ],
            'impact_code'              => [
                'required',
            ],
        ];
    }
}
