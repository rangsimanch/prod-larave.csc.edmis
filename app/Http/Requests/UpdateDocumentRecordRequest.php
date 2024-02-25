<?php

namespace App\Http\Requests;

use App\DocumentRecord;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateDocumentRecordRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('document_record_edit');
    }

    public function rules()
    {
        return [
            'title' => [
                'string',
                'required',
            ],
            'category' => [
                'required',
            ],
            'file_upload' => [
                'array',
            ],
        ];
    }
}
