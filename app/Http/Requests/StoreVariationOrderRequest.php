<?php

namespace App\Http\Requests;

use App\VariationOrder;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreVariationOrderRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('variation_order_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'operation_date' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
        ];
    }
}
