<?php

namespace App\Services;

use App\Interfaces\UserRepositoryInterface;
use App\Traits\HttpResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserService
{
    use HttpResponseTrait;
    private  $userRepository;
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function generateApiToken()
    {
        try {
            $user = Auth::user();
            $token = $this->saveToken($user);
            return $this->success('Token generated successfully',['token' => $token]);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->error('Token generation failed');
        }
    }

    public function saveToken($user)
    {
        $token = $user->createToken($user->email)->plainTextToken;
        $this->userRepository->update($user->id,['api_token'=>$token]);
        return $token;
    }


}
