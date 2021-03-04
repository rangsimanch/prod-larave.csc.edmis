<?php

namespace App\Http\Requests;

use App\Task;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class CreateReportTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
                'create_by_user_id'     => [
                    'required'],
                'startDate'   => [
                    'date_format:' . config('panel.date_format'),
                    'required'],
                'endDate'     => [
                    'date_format:' . config('panel.date_format'),
                    'required'],
                'reportType' => [
                    'required'],
                'contracts' => [
                    'required'
                ],
        ];
    }
}
