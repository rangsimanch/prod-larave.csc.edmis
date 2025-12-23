<?php

namespace App\Http\Requests;

use App\CloseOutMain;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateCloseOutMainRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('close_out_main_edit');
    }

    public function rules()
    {
        return [
            'status' => [
                'required',
            ],
            'construction_contract_id' => [
                'required',
                'integer',
            ],
            'closeout_subject_id' => [
                'required',
                'integer',
            ],
            'detail' => [
                'string',
                'max:255',
                'required',
            ],
            'quantity' => [
                'string',
                'max:255',
                'nullable',
            ],
            'ref_documents' => [
                'string',
                'max:255',
                'nullable',
            ],
            'final_file' => [
                'array',
                'required',
            ],
            'final_file.*' => [
                'required',
            ],
            'closeout_url_filename' => [
                'array',
            ],
            'closeout_url_filename.*' => [
                'nullable',
                'string',
                'max:255',
            ],
            'closeout_url_url' => [
                'array',
            ],
            'closeout_url_url.*' => [
                'nullable',
                'string',
                'max:2048',
                'url',
            ],
            'closeout_url_ids' => [
                'array',
            ],
            'closeout_url_ids.*' => [
                'nullable',
                'integer',
            ],
        ];
    }
}
