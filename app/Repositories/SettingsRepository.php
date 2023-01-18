<?php

namespace App\Repositories;

use App\Interfaces\SettingsRepositoryInterface;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Model;

class SettingsRepository extends BaseRepository implements SettingsRepositoryInterface
{
    public function __construct(Setting $model)
    {
        parent::__construct($model);
    }

    public function getSetting($settingName)
    {
        $value =  $this->model::where('name',$settingName);
        if ($value->exists()){
            return $value->first()->value;
        }
    }
    public function updateSetting($settingName,$value)
    {
        $setting =  $this->model::where('name',$settingName);
        if ($setting->exists()){
            return $setting->update(['value'=>$value]);
        }
    }
    public function getSettings(): object
    {
        $data = [];
        foreach ($this->getAll() as $setting){
            $data[$setting->name] = $setting->value;
        }
        return (object) $data;
    }

    public function getSettingNames()
    {
        return $this->model::all(['name'])->pluck('name');
    }
}
