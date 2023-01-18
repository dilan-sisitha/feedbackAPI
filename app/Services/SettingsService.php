<?php

namespace App\Services;

use App\Interfaces\SettingsRepositoryInterface;
use App\Traits\HttpResponseTrait;

class SettingsService
{

    private $settingsRepository;

    public function __construct(SettingsRepositoryInterface $settingsRepository)
    {
        $this->settingsRepository = $settingsRepository;
    }

    public function updateSettings($request)
    {
        foreach ($request->all() as $key=>$value){
            $this->settingsRepository->updateSetting($key,$value);
        }

    }
}
