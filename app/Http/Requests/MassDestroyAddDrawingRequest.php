<?php

namespace App\Http\Requests;

use App\AddDrawing;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyAddDrawingRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('add_drawing_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:add_drawings,id',
        ];
    }
}
