<?php

namespace App\Http\Requests;

use App\SrtDocumentStatus;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroySrtDocumentStatusRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('srt_document_status_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:srt_document_statuses,id',
        ];
    }
}
