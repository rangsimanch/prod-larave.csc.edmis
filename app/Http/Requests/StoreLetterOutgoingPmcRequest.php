<?php

namespace App\Http\Requests;

use App\LetterOutgoingPmc;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreLetterOutgoingPmcRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('letter_outgoing_pmc_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [];
    }
}
