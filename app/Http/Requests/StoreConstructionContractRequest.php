<?php

namespace App\Http\Requests;

use App\ConstructionContract;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreConstructionContractRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('construction_contract_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'required',
            ],
            'code' => [
                'required',
            ],
        ];
    }
}
