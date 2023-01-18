<?php

namespace App\Http\Requests\Backend;

use App\Models\Setting;
use App\Rules\Backend\SettingController\SettingName;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
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
        $rules = [];
        foreach (request()->keys() as $attribute){
            $rules[$attribute] = ['string',new SettingName()];
        }
        return $rules;

    }
}
