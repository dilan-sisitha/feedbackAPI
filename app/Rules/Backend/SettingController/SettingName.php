<?php

namespace App\Rules\Backend\SettingController;

use App\Interfaces\SettingsRepositoryInterface;
use App\Models\Setting;
use Illuminate\Contracts\Validation\Rule;

class SettingName implements Rule
{

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $settingNames = Setting::select('name')->pluck('name')->toArray();
        if (in_array($attribute,$settingNames)){
            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The Setting does not exist';
    }
}
