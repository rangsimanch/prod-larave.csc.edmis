<?php

namespace App\Http\Requests;

use App\SrtHeadOfficeDocument;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroySrtHeadOfficeDocumentRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('srt_head_office_document_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:srt_head_office_documents,id',
        ];
    }
}
