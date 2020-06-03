<?php

namespace App\Http\Requests;

use App\LetterIncomingSrt;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyLetterIncomingSrtRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('letter_incoming_srt_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:letter_incoming_srts,id',
        ];
    }
}
