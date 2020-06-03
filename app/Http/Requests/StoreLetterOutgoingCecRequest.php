<?php

namespace App\Http\Requests;

use App\LetterOutgoingCec;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreLetterOutgoingCecRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('letter_outgoing_cec_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [];
    }
}
