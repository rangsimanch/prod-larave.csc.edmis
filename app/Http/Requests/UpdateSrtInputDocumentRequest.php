<?php

namespace App\Http\Requests;

use App\SrtInputDocument;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateSrtInputDocumentRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('srt_input_document_edit');
    }

    public function rules()
    {
        return [
            'constuction_contract_id' => [
                'required',
                'integer',
            ],
            'registration_number'     => [
                'string',
                'required',
                'unique:srt_input_documents,registration_number,' . request()->route('srt_input_document')->id,
            ],
            'document_number'         => [
                'string',
                'required',
            ],
            'incoming_date'           => [
                'required',
                'date_format:' . config('panel.date_format'),
            ],
            'refer_to'                => [
                'string',
                'nullable',
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
