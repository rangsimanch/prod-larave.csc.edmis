<?php

namespace App\Http\Requests;

use App\LetterIncomingPmc;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyLetterIncomingPmcRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('letter_incoming_pmc_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:letter_incoming_pmcs,id',
        ];
    }
}
