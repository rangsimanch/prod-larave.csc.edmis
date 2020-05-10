<?php

namespace App\Http\Requests;

use App\RecordsOfVisitor;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreRecordsOfVisitorRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('records_of_visitor_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'date_of_visit' => [
                'date_format:' . config('panel.date_format'),
                'nullable'],
        ];
    }
}
