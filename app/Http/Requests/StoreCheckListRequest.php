<?php

namespace App\Http\Requests;

use App\CheckList;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreCheckListRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('check_list_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'date_of_work_done' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
        ];
    }
}
