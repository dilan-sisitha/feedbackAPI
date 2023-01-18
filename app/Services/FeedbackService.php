<?php

namespace App\Services;

use App\Helpers\FileStorageHelper;
use App\Http\Resources\SettingsResource;
use App\Interfaces\FeedbackRepositoryInterface;
use App\Interfaces\SettingsRepositoryInterface;
use App\Interfaces\SiteDataRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Traits\HttpResponseTrait;
use Carbon\Carbon;
use Facade\FlareClient\Http\Exceptions\BadResponse;
use GuzzleHttp\Exception\BadResponseException;
use http\Exception\UnexpectedValueException;
use http\Url;
use Illuminate\Http\ResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FeedbackService
{
    use HttpResponseTrait;

    private $feedbackRepository;
    private $siteDataRepository;
    private $userDataRepository;
    private $settingsRepository;
    private $fileStorage;

    public function __construct(
        FeedbackRepositoryInterface $feedbackRepository,
        SiteDataRepositoryInterface $siteDataRepository,
        UserRepositoryInterface     $userRepository,
        SettingsRepositoryInterface $settingsRepository
    )
    {
        $this->feedbackRepository = $feedbackRepository;
        $this->siteDataRepository = $siteDataRepository;
        $this->userDataRepository = $userRepository;
        $this->settingsRepository = $settingsRepository;
    }

    public function getFileStorage()
    {
        if (!$this->fileStorage) {
            return new FileStorageHelper();
        }
        return $this->fileStorage;
    }

    public function setFileStorage($fileStorage): void
    {
        $this->fileStorage = $fileStorage;
    }

    public function createFeedback($request)
    {
        try {
            $url = null;
            $site_data = $this->getUserSiteData();
            if (!$site_data) {
                return $this->error('User site data unavailable');
            }
            if ($request->filled('screenshot')) {
                $url = $this->storeScreenshot($site_data->site, base64_decode($request->input('screenshot')));
            }
            $feedback = $this->storeFeedback($request, $site_data, $url);
             $response = $this->updateFeedbackSheet($feedback);
            if (method_exists($response, 'status') && $response->status() == 200) {
                return $this->success('Feedback recorded');
            }
            return $this->error('Creating feedback failed');
        } catch (\Throwable $e) {
            report($e);
            return $this->error('Creating feedback failed');
        }
    }

    public function storeScreenshot($siteName, $image): string
    {
        $path = strtolower(preg_replace("/[^a-zA-Z0-9]+/", "", $siteName));
        $name = Str::random(10) . time() . '.png';
        return $this->getFileStorage()->createFile($path, $image, $name);
    }

    public function storeFeedback($request, $site_data, $url)
    {
        $data = [
            'user_id' => Auth::id(),
            'site_name' => ($request->site) ?? $site_data->site_name,
            'site_section' => ($request->site_section) ?? $site_data->site_section,
            'email' => $request->email,
            'comment' => $request->comment,
            'screenshot' => $url,
        ];
        return $this->feedbackRepository->create($data);
    }

    public function updateFeedbackSheet($feedback)
    {
        $response = $this->sendGoogleSheetRequest($feedback);
        if ($response->status() != 200) {
            return $this->processRequest($response, $feedback);
        }
        return $response;
    }

    public function sendGoogleSheetRequest($feedback)
    {
        $data = [
            $feedback->site_name,
            $feedback->site_section,
            $feedback->email,
            $feedback->comment,
            $feedback->screenshot,
            Carbon::parse($feedback->created_at)->format('Y/m/d h:i:s')
        ];
        $token = $this->settingsRepository->getSetting('access_token');
        return Http::accept('application/json')
            ->withToken($token)
            ->post($this->getSheetUrl(), ['values' => [$data]]);
    }

    public function processRequest($response, $feedback)
    {
        $decoded_response = json_decode($response, true);
        if (isset($decoded_response['error']) && $decoded_response['error']['status'] == 'UNAUTHENTICATED') {
            $newToken = $this->refreshToken();
            if ($newToken) {
                return $this->sendGoogleSheetRequest($feedback);
            }
        }
        return $response;
    }

    public function refreshToken(): bool
    {
        $settings = $this->settingsRepository->getSettings();
        $data = [
            'client_id' => $settings->client_id,
            'client_secret' => $settings->client_secret,
            'refresh_token' => $settings->refresh_token,
            'grant_type' => 'refresh_token'
        ];
        $response = Http::accept('application/x-www-form-urlencoded')
            ->post(env('REFRESH_TOKEN_URL'), $data);
        $decoded_response = json_decode($response, true);
        if ($response->status()==200 && isset($decoded_response['access_token'])) {
            return $this->settingsRepository->updateSetting('access_token', $decoded_response['access_token']);
        }
        return false;
    }

    public function getSheetUrl(): string
    {
        $sheetId = $this->getUserSiteData()->sheet_id;
        $url = env('SHEET_URL');
        return Str::replace(':sheet_id', $sheetId, $url);
    }

    private function getUserSiteData()
    {
        $user = Auth::user();
        return $this->userDataRepository->getSiteData($user);
    }


}
