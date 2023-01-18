<?php

namespace Tests\Feature\Controllers\Backend\SettingsController;

use App\Models\Setting;
use App\Models\User;
use Database\Seeders\SettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateSettingTest extends TestCase
{
    use RefreshDatabase;
    public function testUnAuthenticatedUserUpdateSettings()
    {
        Setting::factory()->create(['name'=>'access_token','value'=>'first_token']);
        Setting::factory()->create(['name'=>'client_id','value'=>'123456']);

        $data = [
            'access_token'=>'test token',
            'client_id'=>'new'
        ];
        $response = $this->post('/settings/update',$data);
        $response->assertStatus(302);
        $this->assertDatabaseMissing('settings',[
            'name'=>'access_token',
            'value'=>'test token',
        ]);
        $this->assertDatabaseMissing('settings',[
            'name'=>'client_id',
            'value'=>'new',
        ]);
    }
    public function testAuthenticatedUserUpdateNonExistingSettings()
    {
        $user = User::factory()->create();
        $data = [
            'access_token'=>'test token',
            'client_id'=>'new'
        ];
        $response = $this->actingAs($user)->post('/settings/update',$data);
        $response->assertSessionHasErrors(['access_token','client_id']);
        $response->assertStatus(302);
        $this->assertDatabaseMissing('settings',[
            'name'=>'access_token',
            'value'=>'test token',
        ]);
        $this->assertDatabaseMissing('settings',[
            'name'=>'client_id',
            'value'=>'new',
        ]);
    }
    public function testAuthenticatedUserUpdateSettingsSuccessfully()
    {
        Setting::factory()->create(['name'=>'access_token','value'=>'first_token']);
        Setting::factory()->create(['name'=>'client_id','value'=>'123456']);
        $user = User::factory()->create();
        $data = [
            'access_token'=>'test token',
            'client_id'=>'new'
        ];
        $response = $this->actingAs($user)->post('/settings/update',$data);
        $response->assertOk();
        $response->assertSeeText('Settings Updated Successfully');
        $this->assertDatabaseHas('settings',[
            'name'=>'access_token',
            'value'=>'test token',
        ]);
        $this->assertDatabaseHas('settings',[
            'name'=>'client_id',
            'value'=>'new',
        ]);
    }
}
