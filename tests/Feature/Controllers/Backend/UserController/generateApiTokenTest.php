<?php

namespace Tests\Feature\Controllers\Backend\UserController;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class generateApiTokenTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_generated_api_token()
    {
        $response = $this->post('/user/generate-token');
        $response->assertStatus(302);
        $response->assertDontSeeText('token');

    }
    public function test_authenticated_user_generated_api_token()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/user/generate-token');
        $response->assertOk();
        $response->assertSeeText('token');
        $this->assertDatabaseHas('users',[
            'id'=>$user->id,
            'api_token'=>json_decode($response->getContent(),true)['data']['token']
        ]);
    }
    public function test_authenticated_user_update_api_token()
    {
        $user = User::factory()->create(['api_token'=>Str::random(40)]);
        $response = $this->actingAs($user)->post('/user/generate-token');
        $response->assertOk();
        $response->assertSeeText('token');
        $this->assertDatabaseHas('users',[
            'id'=>$user->id,
            'api_token'=>json_decode($response->getContent(),true)['data']['token']
        ]);
    }
}
