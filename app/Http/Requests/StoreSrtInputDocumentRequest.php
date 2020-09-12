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
        abort_if(Gate::denies('srt_input_document_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'document_number'  => [
                'string',
                'required',
            ],
            'incoming_date'    => [
                'required',
                'date_format:' . config('panel.date_format'),
            ],
            'refer_to'         => [
                'string',
                'nullable',
            ],
            'attachments'      => [
                'string',
                'nullable',
            ],
            'from'             => [
                'required',
            ],
            'to'               => [
                'required',
            ],
            'signer'           => [
                'string',
                'nullable',
            ],
            'document_storage' => [
                'string',
                'nullable',
            ],
        ];
    }
}
