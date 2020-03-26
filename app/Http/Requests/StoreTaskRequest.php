<?php

namespace App\Http\Requests;

use App\Task;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreTaskRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('task_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;

    }

    public function rules()
    {
        return [
            'name'        => [
                'required'],
            'tags.*'      => [
                'integer'],
            'tags'        => [
                'array'],
            'due_date'    => [
                'date_format:' . config('panel.date_format'),
                'nullable'],
            'end_date'    => [
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
                'nullable'],
            'temperature' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647'],
            'status_id'   => [
                'required',
                'integer'],
        ];

    }
}
