<?php

namespace App\Http\Requests;

use App\RecoveryFile;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateRecoveryFileRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('recovery_file_edit');
    }

    public function rules()
    {
        return [
        'dir_name' => [
            'string',
            'required',
        ],];
    }
}
