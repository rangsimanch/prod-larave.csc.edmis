<?php

namespace App\Http\Requests;

use App\SrtInputDocument;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroySrtInputDocumentRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('srt_input_document_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:srt_input_documents,id',
        ];
    }
}
