<?php

namespace App\Http\Requests;

use App\BoqItem;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyBoqItemRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('boq_item_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:boq_items,id',
        ];
    }
}
