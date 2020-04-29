<?php

namespace App\Http\Requests;

use App\DocumentTag;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateDocumentTagRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('document_tag_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;

    }

    public function rules()
    {
        return [
        ];

    }
}
