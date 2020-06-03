<?php

namespace App\Http\Requests;

use App\DocumentsAndAssignment;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateDocumentsAndAssignmentRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('documents_and_assignment_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'date_of_receipt' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
        ];
    }
}
