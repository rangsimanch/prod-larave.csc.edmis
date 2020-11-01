<?php

namespace App\Http\Requests;

use App\SrtPeDocument;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroySrtPeDocumentRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('srt_pe_document_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:srt_pe_documents,id',
        ];
    }
}
