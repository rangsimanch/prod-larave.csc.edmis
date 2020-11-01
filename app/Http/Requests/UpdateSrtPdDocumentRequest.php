<?php

namespace App\Http\Requests;

use App\SrtPdDocument;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateSrtPdDocumentRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('srt_pd_document_edit');
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
            'operators.*'        => [
                'integer',
            ],
            'operators'          => [
                'array',
            ],
            'note'               => [
                'string',
                'nullable',
            ],
            'save_for'           => [
                'required',
            ],
        ];
    }
}
