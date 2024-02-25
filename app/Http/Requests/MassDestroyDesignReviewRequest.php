<?php

namespace App\Http\Requests;

use App\DesignReview;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyDesignReviewRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('design_review_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:design_reviews,id',
        ];
    }
}
