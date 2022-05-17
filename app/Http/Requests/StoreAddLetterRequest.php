<?php

namespace App\Http\Requests;

use App\AddLetter;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreAddLetterRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('add_letter_create');
    }

    public function rules()
    {
        return [
            'letter_type' => [
                'required',
            ],
            'topic_categories.*' => [
                'integer',
            ],
            'topic_categories' => [
                'array',
            ],
            'title' => [
                'string',
                'required',
            ],
            'letter_no' => [
                'string',
                'required',
            ],
            'speed_class' => [
                'required',
            ],
            'objective' => [
                'required',
            ],
            'sender_id' => [
                'required',
                'integer',
            ],
            'sent_date' => [
                'required',
                'date_format:' . config('panel.date_format'),
            ],
            'receiver_id' => [
                'required',
                'integer',
            ],
            'cc_tos.*' => [
                'integer',
            ],
            'cc_tos' => [
                'array',
            ],
            'construction_contract_id' => [
                'required',
                'integer',
            ],
            'letter_upload' => [
                'array',
                'required',
            ],
            'letter_upload.*' => [
                'required',
            ],
            'start_date' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'complete_date' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'processing_time' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
        ];
    }
}
