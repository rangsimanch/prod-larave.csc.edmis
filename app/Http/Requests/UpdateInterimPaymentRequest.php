<?php

namespace App\Http\Requests;

use App\InterimPayment;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateInterimPaymentRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('interim_payment_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'month' => [
                'required',
            ],
            'year'  => [
                'required',
            ],
        ];
    }
}
