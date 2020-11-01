<?php

namespace App\Http\Requests;

use App\SrtPdDocument;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroySrtPdDocumentRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('srt_pd_document_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:srt_pd_documents,id',
        ];
    }
}
