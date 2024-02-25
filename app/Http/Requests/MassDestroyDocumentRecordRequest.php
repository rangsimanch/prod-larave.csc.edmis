<?php

namespace App\Http\Requests;

use App\DocumentRecord;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyDocumentRecordRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('document_record_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:document_records,id',
        ];
    }
}
