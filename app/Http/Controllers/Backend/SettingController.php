<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\UpdateSettingRequest;
use App\Services\SettingsService;
use App\Traits\HttpResponseTrait;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{
    use HttpResponseTrait;
    private $settingsService;
    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    public function updateSettings(UpdateSettingRequest $request)
    {
        try {
            $this->settingsService->updateSettings($request);
            return $this->success('Settings Updated Successfully');
        }catch (\Exception $e){
            report($e);
            return $this->error('Setting Update failed');
        }

    }
}
