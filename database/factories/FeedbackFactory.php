<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FeedbackFactory extends Factory
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
            'site_name'=>'test.com',
            'site_section'=>'BackEnd',
            'email'=>'test@email.com',
            'comment'=>'test comment',
            'screenshot'=>'testImageUrl.com',
            'checked'=>false
        ];
    }
}
