<?php

namespace Tests\Services;

use App\Models\Feedback;
use App\Models\Setting;
use App\Models\SiteData;
use App\Models\User;
use App\Repositories\FeedbackRepository;
use App\Services\FeedbackService;
use Database\Seeders\SettingSeeder;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FeedbackServiceTest extends TestCase
{
    use RefreshDatabase;
    protected $feedbackService;

    public function setUp() :void
    {
        parent::setUp();
        $this->feedbackService = $this->app->make(FeedbackService::class);
    }
    public function testStoreFeedback()
    {
        $user = User::factory()->create();
        $site_data = SiteData::factory(['user_id'=>$user->id])->create();
        Sanctum::actingAs($user);
        $data = (object)[
          'email'=>'test@email.com',
          'comment'=>Str::random(500),
      ];
      $response = $this->feedbackService->storeFeedback($data,$site_data,'www.testimageurl.com');
      $this->assertDatabaseCount('feedback',1);
      $this->assertDatabaseHas('feedback',[
         'email'=>$data->email,
         'comment'=>$data->comment,
         'screenshot'=>'www.testimageurl.com'
      ]);
    }
    public function testStoreFeedbackWithRequestSiteData()
    {
        $user = User::factory()->create();
        $site_data = SiteData::factory(['user_id'=>$user->id])->create();
        Sanctum::actingAs($user);
        $data = (object)[
            'email'=>'test@email.com',
            'comment'=>Str::random(500),
            'site'=>'test site',
            'site_section'=>'test frontend'
        ];
        $response = $this->feedbackService->storeFeedback($data,$site_data,'www.testimageurl.com');
        $this->assertDatabaseCount('feedback',1);
        $this->assertDatabaseHas('feedback',[
            'email'=>$data->email,
            'comment'=>$data->comment,
            'screenshot'=>'www.testimageurl.com',

        ]);

    }
    public function testStoreScreenShot()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        Storage::fake('images');
        $image = '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';
        $response = $this->feedbackService->storeScreenshot('Europe Express',$image);
        Storage::disk('images')->assertExists(Str::after($response,'storage'));
    }
    public function testSendSheetsRequestWithInvalidToken()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $feedback = Feedback::factory()->create(['user_id'=>$user->id]);
        $site_data = SiteData::factory(['user_id'=>$user->id])->create();
        $setting = Setting::factory()->create(['name'=>'access_token','value'=>'test']);
        $sheetUrl = $this->feedbackService->getSheetUrl();
        Http::fake([
            $sheetUrl=>Http::response(['error'=>['status'=>'UNAUTHENTICATED']],401)
        ]);
        $response = $this->feedbackService->sendGoogleSheetRequest($feedback);
        $decoded_response = json_decode($response,true);
        $this->assertEquals('401',$response->status());
        $this->assertEquals('UNAUTHENTICATED',$decoded_response['error']['status']);
    }
    public function testSendSheetsRequestWithValidToken()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $feedback = Feedback::factory()->create(['user_id'=>$user->id]);
        $site_data = SiteData::factory(['user_id'=>$user->id,'sheet_id'=>'test_sheet'])->create();
        $setting = Setting::factory()->create(['name'=>'access_token','value'=>'valid token']);
        $sheetUrl = $this->feedbackService->getSheetUrl();
        Http::fake([
            $sheetUrl=>Http::response(['success'=>true],200)
        ]);
        $response = $this->feedbackService->sendGoogleSheetRequest($feedback);
        $this->assertEquals('200',$response->status());
    }
    public function testRefreshTokenSuccessfully()
    {
        $this->seed(SettingSeeder::class);
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $feedback = Feedback::factory()->create(['user_id'=>$user->id]);
        Http::fake([
            env('REFRESH_TOKEN_URL')=>Http::response(['access_token'=>123],200)
        ]);
        $response= $this->feedbackService->refreshToken($feedback);
        $this->assertTrue($response);
        $this->assertDatabaseHas('settings',[
            'name'=>'access_token',
            'value'=>123
            ]);
    }
    public function testRefreshTokenWithErrorResponse()
    {
        $this->seed(SettingSeeder::class);
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $feedback = Feedback::factory()->create(['user_id'=>$user->id]);
        $old_access_token = Setting::where('name','access_token')->first()->value;
        Http::fake([
            env('REFRESH_TOKEN_URL')=>Http::response(['access_token'=>'error'],400)
        ]);
        $response= $this->feedbackService->refreshToken($feedback);
        $this->assertFalse($response);
        $this->assertDatabaseHas('settings',[
            'name'=>'access_token',
            'value'=>$old_access_token
        ]);
    }
}
