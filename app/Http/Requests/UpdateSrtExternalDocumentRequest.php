<?php

namespace App\Http\Requests;

use App\SrtExternalDocument;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateSrtExternalDocumentRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('srt_external_document_edit');
    }

    public function rules()
    {
        return [
            'constuction_contract_id' => [
                'required',
                'integer',
            ],
            'document_number'         => [
                'string',
                'nullable',
            ],
            'subject'                 => [
                'string',
                'nullable',
            ],
            'incoming_date'           => [
                'date_format:' . config('panel.date_format'),
                'nullable',
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
            'close_date'              => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'save_for'                => [
                'required',
            ],
        ];
    }
}
