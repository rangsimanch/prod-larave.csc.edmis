<?php

namespace App\Http\Requests;

use App\CloseOutDrive;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreCloseOutDriveRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('close_out_drive_create');
    }

    public function rules()
    {
        return [
            'filename' => [
                'string',
                'max:255',
                'required',
            ],
            'url' => [
                'string',
                'required',
            ],
            'construction_contract_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
