<?php

namespace App\Http\Requests;

use App\SubmittalsRfa;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateSubmittalsRfaRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('submittals_rfa_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'qty_sets'      => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647'],
            'date_returned' => [
                'date_format:' . config('panel.date_format'),
                'nullable'],
        ];
    }
}
