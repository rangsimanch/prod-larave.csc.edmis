<?php

namespace App\Http\Requests;

use App\SrtOther;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateSrtOtherRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('srt_other_edit');
    }

    public function rules()
    {
        return [
            'constuction_contract_id' => [
                'integer',
                'nullable',
            ],
            'document_number' => [
                'string',
                'nullable',
            ],
            'subject' => [
                'string',
                'nullable',
            ],
            'incoming_date' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'refer_to' => [
                'string',
                'nullable',
            ],
            'tos.*' => [
                'integer',
            ],
            'tos' => [
                'array',
            ],
            'attachments' => [
                'string',
                'nullable',
            ],
            'signatory' => [
                'string',
                'nullable',
            ],
            'document_storage' => [
                'string',
                'nullable',
            ],
            'close_date' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'save_for' => [
                'required',
            ],
            'to_text' => [
                'string',
                'nullable',
            ],
        ];
    }
}
