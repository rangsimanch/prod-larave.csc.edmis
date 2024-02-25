<?php

namespace App\Http\Requests;

use App\DesignReview;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateDesignReviewRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('design_review_edit');
    }

    public function rules()
    {
        return [
            'sheet_title' => [
                'string',
                'required',
            ],
            'url' => [
                'string',
                'required',
            ],
        ];
    }
}
