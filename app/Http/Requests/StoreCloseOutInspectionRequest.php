<?php

namespace App\Http\Requests;

use App\CloseOutInspection;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreCloseOutInspectionRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('close_out_inspection_create');
    }

    public function rules()
    {
        return [
            'construction_contract_id' => [
                'required',
                'integer',
            ],
            'subject' => [
                'string',
                'required',
            ],
            'document_number' => [
                'string',
                'nullable',
            ],
            'submit_date' => [
                'required',
                'date_format:' . config('panel.date_format'),
            ],
            'boqs.*' => [
                'integer',
            ],
            'boqs' => [
                'required',
                'array',
            ],
            'locations.*' => [
                'integer',
            ],
            'locations' => [
                'array',
            ],
            'work_types.*' => [
                'integer',
            ],
            'work_types' => [
                'array',
            ],
            'sub_worktype' => [
                'string',
                'nullable',
            ],
            'files_report_before' => [
                'array',
                'nullable',
            ],
            'files_report_before.*' => [
                'nullable',
            ],
            'respond_date' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'files_report_after' => [
                'array',
            ],
            'review_date' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
        ];
    }
}
