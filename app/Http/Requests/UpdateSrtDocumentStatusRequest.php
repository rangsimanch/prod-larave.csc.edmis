<?php

namespace App\Http\Requests;

use App\SrtDocumentStatus;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateSrtDocumentStatusRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('srt_document_status_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
