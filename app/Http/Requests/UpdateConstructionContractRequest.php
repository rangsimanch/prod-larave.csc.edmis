<?php

namespace App\Http\Requests;

use App\ConstructionContract;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateConstructionContractRequest extends FormRequest
{
    public function authorize()
    {
      //  abort_if(Gate::denies('construction_contract_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
