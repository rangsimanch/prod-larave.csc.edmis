<?php

namespace App\Http\Requests;

use App\SrtHeadOfficeDocument;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateSrtHeadOfficeDocumentRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('srt_head_office_document_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'refer_documents_id' => [
                'required',
                'integer',
            ],
            'process_date'       => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'finished_date'      => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'practitioner'       => [
                'string',
                'nullable',
            ],
            'note'               => [
                'string',
                'nullable',
            ],
        ];
    }
}
