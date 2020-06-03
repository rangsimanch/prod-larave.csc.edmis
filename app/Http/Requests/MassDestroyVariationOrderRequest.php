<?php

namespace App\Http\Requests;

use App\VariationOrder;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyVariationOrderRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('variation_order_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:variation_orders,id',
        ];
    }
}
