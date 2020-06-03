<?php

namespace App\Http\Requests;

use App\ProvisionalSum;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreProvisionalSumRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('provisional_sum_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [];
    }
}
