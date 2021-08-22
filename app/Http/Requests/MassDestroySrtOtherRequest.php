<?php

namespace App\Http\Requests;

use App\SrtOther;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroySrtOtherRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('srt_other_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:srt_others,id',
        ];
    }
}
