<?php

namespace App\Http\Requests;

use App\SrtExternalAgencyDocument;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreSrtExternalAgencyDocumentRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('srt_external_agency_document_create');
    }

    public function rules()
    {
        return [
            'construction_contracts.*' => [
                'integer',
            ],
            'construction_contracts' => [
                'array',
            ],
            'subject' => [
                'string',
                'nullable',
            ],
            'originator_number' => [
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
            'from' => [
                'string',
                'nullable',
            ],
            'to' => [
                'string',
                'nullable',
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
        ];
    }
}
