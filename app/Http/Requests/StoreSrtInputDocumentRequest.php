<?php

namespace App\Http\Requests;

use App\SrtInputDocument;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreSrtInputDocumentRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('srt_input_document_create');
    }

    public function rules()
    {
        return [
            'constuction_contract_id' => [
                'required',
                'integer',
            ],
            'subject'                 => [
                'string',
                'nullable',
            ],
            'incoming_date'           => [
                'required',
                'date_format:' . config('panel.date_format'),
            ],
            'refer_to'                => [
                'string',
                'nullable',
            ],
            'tos.*'                   => [
                'integer',
            ],
            'tos'                     => [
                'array',
            ],
            'attachments'             => [
                'string',
                'nullable',
            ],
            'signatory'               => [
                'string',
                'nullable',
            ],
            'document_storage'        => [
                'string',
                'nullable',
            ],
        ];
    }
}
