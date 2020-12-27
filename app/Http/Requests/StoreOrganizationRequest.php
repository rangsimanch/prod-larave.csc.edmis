<?php

namespace App\Http\Requests;

use App\Organization;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreOrganizationRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('organization_create');
    }

    public function rules()
    {
        return [
            'title_th' => [
                'string',
                'required',
            ],
            'title_en' => [
                'string',
                'nullable',
            ],
            'code'     => [
                'string',
                'nullable',
            ],
        ];
    }
}
