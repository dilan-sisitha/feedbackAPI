<?php

namespace App\Http\Requests\Api;

use App\Rules\Api\FeedbackController\Base64;
use Illuminate\Foundation\Http\FormRequest;

class CreateFeedbackRequest extends FormRequest
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
            'email'=>'nullable|email',
            'comment'=>'required|string|max:500',
            'screenshot'=>['sometimes',new Base64()],
            'site'=>'sometimes|string',
            'site_section'=>'sometimes'
        ];
    }
}
