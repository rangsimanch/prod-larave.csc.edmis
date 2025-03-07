<?php

namespace App\Http\Requests;

use App\ShopDrawing;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateShopDrawingRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('shop_drawing_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'drawing_references.*' => [
                'integer',
            ],
            'drawing_references'   => [
                'array',
            ],
        ];
    }
}
