<?php

namespace App\Http\Requests;

use App\ContractAndComponent;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreContractAndComponentRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('contract_and_component_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;

    }

    public function rules()
    {
        return [
        ];

    }
}
