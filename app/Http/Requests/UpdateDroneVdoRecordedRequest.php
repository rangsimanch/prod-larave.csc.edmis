<?php

namespace App\Http\Requests;

use App\DroneVdoRecorded;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateDroneVdoRecordedRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('drone_vdo_recorded_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'operation_date' => [
                'date_format:' . config('panel.date_format'),
                'nullable'],
        ];
    }
}
