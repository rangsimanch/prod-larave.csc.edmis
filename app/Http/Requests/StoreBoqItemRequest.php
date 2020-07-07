<?php

namespace App\Http\Requests;

use App\BoqItem;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreBoqItemRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('boq_item_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [];
    }
}
