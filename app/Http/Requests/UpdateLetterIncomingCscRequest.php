<?php

namespace App\Http\Requests;

use App\LetterIncomingCsc;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateLetterIncomingCscRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('letter_incoming_csc_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [];
    }
}
