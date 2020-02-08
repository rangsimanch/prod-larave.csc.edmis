<?php

namespace App\Http\Requests;

use App\SubmittalsRfa;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroySubmittalsRfaRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('submittals_rfa_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:submittals_rfas,id',
        ];
    }
}
