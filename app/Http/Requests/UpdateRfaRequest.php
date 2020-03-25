<?php

namespace App\Http\Requests;

use App\Rfa;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateRfaRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('rfa_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'review_time'   => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647'],
            'submit_date'   => [
                'date_format:' . config('panel.date_format'),
                'nullable'],
            'receive_date'  => [
                'date_format:' . config('panel.date_format'),
                'nullable'],
            'target_date'   => [
                'date_format:' . config('panel.date_format'),
                'nullable'],
            'hardcopy_date' => [
                'date_format:' . config('panel.date_format'),
                'nullable'],
        ];
    }
}
