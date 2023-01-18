<?php

namespace Database\Seeders;

use App\Models\SiteData;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SiteDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::firstOrCreate(
            ['name' => 'Administrator'],
            [
                'email' => 'dilan@asset.digital',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
            ]);
        SiteData::firstOrCreate(
            ['user_id' => $user->id],
            [
                'sheet_id' => '1mOiZBWanKcDIzRoMPdmvNCWvw5rOkaTAFTJTaaBQmr0',
                'site_section' => 'BackEnd',
                'site_name' => 'Europe Express'
            ]
        );
    }
}
