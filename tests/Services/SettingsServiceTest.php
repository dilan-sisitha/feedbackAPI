<?php

namespace Tests\Services;

use App\Models\Setting;
use App\Models\User;
use App\Services\FeedbackService;
use App\Services\SettingsService;
use Database\Seeders\SettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class SettingsServiceTest extends TestCase
{
    use RefreshDatabase;
    private $settingsService;
    public function setUp() :void
    {
        parent::setUp();
        $this->settingsService = $this->app->make(SettingsService::class);
    }
    public function testUpdateExistingSettings()
    {
        Setting::factory()->create(['name'=>'access_token','value'=>'first_token']);
        Setting::factory()->create(['name'=>'sheet_id','value'=>'123456']);
        $data = [
            'access_token'=>'test token',
            'sheet_id'=>'6789101112'
        ];
        $response = $this->settingsService->updateSettings(new Request($data));
        $this->assertDatabaseHas('settings',[
            'name'=>'access_token',
            'value'=>'test token',
        ]);
        $this->assertDatabaseHas('settings',[
            'name'=>'sheet_id',
            'value'=>'6789101112',
        ]);
    }
    public function testUpdateNonExistingSettings()
    {
        $data = [
            'access_token'=>'test token',
            'sheet_id'=>'6789101112'
        ];
        $response = $this->settingsService->updateSettings(new Request($data));
        $this->assertDatabaseMissing('settings',[
            'name'=>'access_token',
            'value'=>'test token',
        ]);
        $this->assertDatabaseMissing('settings',[
            'name'=>'sheet_id',
            'value'=>'6789101112',
        ]);
    }
}
