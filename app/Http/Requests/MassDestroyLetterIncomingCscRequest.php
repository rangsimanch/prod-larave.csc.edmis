<?php

namespace App\Http\Requests;

use App\LetterIncomingCsc;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyLetterIncomingCscRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('letter_incoming_csc_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:letter_incoming_cscs,id',
        ];
    }
}
