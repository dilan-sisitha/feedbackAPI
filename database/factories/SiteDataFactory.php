<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SiteDataFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id'=>User::factory()->create()->id,
            'sheet_id'=>'1mOiZBWanKcDIzRoMPdmvNCWvw5rOkaTAFTJTaaBQmr0',
            'site_name'=>'test.com',
            'site_section'=>'back_end'
        ];
    }
}
