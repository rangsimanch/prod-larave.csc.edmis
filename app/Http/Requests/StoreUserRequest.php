<?php

namespace App\Http\Requests;

use App\User;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreUserRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('user_create');
    }

    public function rules()
    {
        return [
            'name'                     => [
                'string',
                'required',
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
                'unique:users',
            ],
            'password'                 => [
                'required',
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
