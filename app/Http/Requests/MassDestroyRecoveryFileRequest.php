<?php

namespace App\Http\Requests;

use App\RecoveryFile;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyRecoveryFileRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('recovery_file_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:recovery_files,id',
        ];
    }
}
