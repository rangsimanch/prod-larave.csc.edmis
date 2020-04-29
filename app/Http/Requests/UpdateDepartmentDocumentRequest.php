<?php

namespace App\Http\Requests;

use App\DepartmentDocument;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateDepartmentDocumentRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('department_document_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;

    }

    public function rules()
    {
        return [
            'tags.*' => [
                'integer'],
            'tags'   => [
                'array'],
        ];

    }
}
