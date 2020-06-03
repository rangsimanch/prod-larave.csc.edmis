<?php

namespace App\Http\Requests;

use App\AddDrawing;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreAddDrawingRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('add_drawing_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [];
    }
}
