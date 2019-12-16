<?php

namespace App\Http\Requests;

use App\Jobtitle;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreJobtitleRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('jobtitle_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name'          => [
                'required',
            ],
            'departments.*' => [
                'integer',
            ],
            'departments'   => [
                'required',
                'array',
            ],
        ];
    }
}
