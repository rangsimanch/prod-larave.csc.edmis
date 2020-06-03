<?php

namespace App\Http\Requests;

use App\LetterIncomingCec;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyLetterIncomingCecRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('letter_incoming_cec_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:letter_incoming_cecs,id',
        ];
    }
}
