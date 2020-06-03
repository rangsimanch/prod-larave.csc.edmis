<?php

namespace App\Http\Requests;

use App\ShopDrawing;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyShopDrawingRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('shop_drawing_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:shop_drawings,id',
        ];
    }
}
