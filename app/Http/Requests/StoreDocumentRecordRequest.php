<?php

namespace App\Http\Requests;

use App\DocumentRecord;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreDocumentRecordRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('document_record_create');
    }

    public function rules()
    {
        return [
            'title' => [
                'string',
            ],
            'document_number' => [
                'string',
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
