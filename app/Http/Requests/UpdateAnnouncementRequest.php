<?php

namespace App\Http\Requests;

use App\Announcement;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateAnnouncementRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('announcement_edit');
    }

    public function rules()
    {
        return [
            'title' => [
                'string',
                'nullable',
            ],
            'start_date' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'end_date' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'announce_contracts.*' => [
                'integer',
            ],
            'announce_contracts' => [
                'array',
            ],
        ];
    }
}
