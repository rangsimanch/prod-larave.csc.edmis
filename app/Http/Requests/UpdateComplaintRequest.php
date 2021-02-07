<?php

namespace App\Http\Requests;

use App\Complaint;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateComplaintRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('complaint_edit');
    }

    public function rules()
    {
        return [
            'action_date' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
        ];
    }
}
