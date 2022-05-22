<?php

namespace App\Http\Requests;

use App\SrtExternalAgencyDocument;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroySrtExternalAgencyDocumentRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('srt_external_agency_document_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:srt_external_agency_documents,id',
        ];
    }
}
