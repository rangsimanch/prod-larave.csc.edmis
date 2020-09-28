<?php

namespace App\Http\Requests;

use App\User;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('user_edit');
    }

    public function rules()
    {
        return [
            'name'                     => [
                'string',
                'required',
            ],
            'dob'                      => [
                'required',
                'date_format:' . config('panel.date_format'),
            ],
            'gender'                   => [
                'required',
            ],
            'workphone'                => [
                'string',
                'nullable',
            ],
            'team_id'                  => [
                'required',
                'integer',
            ],
            'email'                    => [
                'required',
                'unique:users,email,' . request()->route('user')->id,
            ],
            'roles.*'                  => [
                'integer',
            ],
            'roles'                    => [
                'required',
                'array',
            ],
            'construction_contracts.*' => [
                'integer',
            ],
            'construction_contracts'   => [
                'array',
            ],
        ];
    }
}
