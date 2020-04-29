<?php

namespace App\Http\Requests;

use App\DepartmentDocument;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyDepartmentDocumentRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('department_document_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;

    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:department_documents,id',
        ];

    }
}
