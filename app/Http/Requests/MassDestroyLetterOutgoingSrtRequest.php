<?php

namespace App\Http\Requests;

use App\LetterOutgoingSrt;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyLetterOutgoingSrtRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('letter_outgoing_srt_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:letter_outgoing_srts,id',
        ];
    }
}
