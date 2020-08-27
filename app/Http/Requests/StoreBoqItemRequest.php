<?php

namespace App\Http\Requests;

use App\BoqItem;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreBoqItemRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('boq_item_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'code'           => [
                'string',
                'nullable',
            ],
            'name'           => [
                'string',
                'nullable',
            ],
            'unit'           => [
                'string',
                'nullable',
            ],
            'quantity'       => [
                'numeric',
            ],
            'unit_rate'      => [
                'numeric',
            ],
            'factor_f'       => [
                'numeric',
            ],
            'unit_rate_x_ff' => [
                'numeric',
            ],
            'total_amount'   => [
                'numeric',
            ],
        ];
    }
}
