<?php

namespace App\Http\Requests;

use App\DocumentsAndAssignment;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyDocumentsAndAssignmentRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('documents_and_assignment_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:documents_and_assignments,id',
        ];
    }
}
