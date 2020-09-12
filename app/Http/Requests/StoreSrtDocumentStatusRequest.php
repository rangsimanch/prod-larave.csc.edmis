<?php

namespace App\Http\Requests;

use App\SrtDocumentStatus;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreSrtDocumentStatusRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('srt_document_status_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'title' => [
                'string',
                'nullable',
            ],
        ];
    }
}
