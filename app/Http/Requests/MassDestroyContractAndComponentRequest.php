<?php

namespace App\Http\Requests;

use App\ContractAndComponent;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyContractAndComponentRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('contract_and_component_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;

    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:contract_and_components,id',
        ];

    }
}
