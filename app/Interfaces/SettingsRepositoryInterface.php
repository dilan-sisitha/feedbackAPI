<?php

namespace App\Interfaces;

interface SettingsRepositoryInterface extends BaseRepositoryInterface
{
    public function getSetting($settingName);
    public function updateSetting($settingName,$value);
    public function getSettings();
    public function getSettingNames();
}
