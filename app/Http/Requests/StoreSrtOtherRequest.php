<?php

namespace App\Http\Requests;

use App\SrtOther;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreSrtOtherRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('srt_other_create');
    }

    public function rules()
    {
        return [
            'constuction_contract_id' => [
                'required',
                'integer',
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
            'from_text' => [
                'string',
                'nullable',
            ],
            'to_text' => [
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
            'file_upload' => [
                'array',
                'required',
            ],
            'file_upload.*' => [
                'required',
            ],
            'save_for' => [
                'required',
            ],
            'close_by_text' => [
                'string',
                'nullable',
            ],
        ];
    }
}
