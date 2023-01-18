<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            'refresh_token' => '1//0gUecs7K_MZIGCgYIARAAGBASNwF-L9Irg6igIfkc8fUiownZH-3OSOKDkA-EvzmvpBfIHJSFAVAm0oeEE9KV3kWBPfjODWqwYbI',
            'access_token' => 'ya29.a0ARrdaM_03d0U4ZmnUZDxanhWavi7gczMZWOKKjVgbfhRRuU-V2_KgNLtnjnLzXLqMD8Tjj7tkF07d87kHd3zXYiBjKjgRvTfGBMLCcr-_J4BYRuV0W5Zq13_YfWhjT3DKWES9xiIeT6BJTfB1he3jSbpcGNAmw',
            'client_id' => '846635682699-7pojaqu8m578l4fqbdpsc7o003bdvh31.apps.googleusercontent.com',
            'client_secret' => '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',
        ];

        foreach ($settings as $name=>$value){
            Setting::firstOrCreate(
                ['name'=>$name],
                ['value'=>$value]
            );

        }
    }
}
