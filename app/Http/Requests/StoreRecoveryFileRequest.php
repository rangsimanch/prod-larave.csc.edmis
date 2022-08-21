<?php

namespace App\Http\Requests;

use App\RecoveryFile;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreRecoveryFileRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('recovery_file_create');
    }

    public function rules()
    {
        return [
            'recovery_file' => [
                'array',
            ],
            'dir_name' => [
                'string',
                'required',
            ],
        ];
    }
}
