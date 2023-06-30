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
            'construction_contract_id' => [
                'required',
                'integer',
            ],
            'chapter_no' => [
                'string',
                'required',
            ],
            'title' => [
                'string',
                'required',
            ],
            'final_file' => [
                'array',
                'required',
            ],
            'final_file.*' => [
                'required',
            ],
        ];
    }
}
