<?php

namespace App\Http\Requests;

use App\ProvisionalSum;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyProvisionalSumRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('provisional_sum_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:provisional_sums,id',
        ];
    }
}
