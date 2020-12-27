<?php

namespace App\Http\Requests;

use App\AddLetter;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateAddLetterRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('add_letter_edit');
    }

    public function rules()
    {
        return [
            'received_date' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
        ];
    }
}
