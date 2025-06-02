<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class OrangeSMSService
{
    protected $clientId;
    protected $clientSecret;
    protected $authHeader;
    protected $tokenCacheKey = 'orange_sms_token';

    public function __construct()
    {
        $this->clientId = config('services.orange.client_id');
        $this->clientSecret = config('services.orange.client_secret');
        $this->authHeader = config('services.orange.auth_header');
    }

    public function getAccessToken()
    {
        return Cache::remember($this->tokenCacheKey, 3500, function () { // 58 minutes
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode($this->authHeader),
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Accept' => 'application/json'
            ])->post('https://api.orange.com/oauth/v3/token', [
                'grant_type' => 'client_credentials'
            ]);

            if ($response->successful()) {
                return $response->json()['access_token'];
            }

            throw new \Exception('Failed to get Orange API token: ' . $response->body());
        });
    }

    public function refreshToken()
    {
        Cache::forget($this->tokenCacheKey);
        return $this->getAccessToken();
    }

    public function sendSMS($phoneNumber, $message)
    {
        $phoneNumber = $this->formatPhoneNumber($phoneNumber);
        $senderNumber = $this->formatPhoneNumber(appConfiguration()->contact_phone_1);

        try {
            $token = $this->getAccessToken();
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ])->post('https://api.orange.com/smsmessaging/v1/outbound/tel%3A%2B237'.$senderNumber.'/requests', [
                'outboundSMSMessageRequest' => [
                    'address' => 'tel:+237' . $phoneNumber,
                    'senderAddress' => 'tel:+237'.$senderNumber,
                    'outboundSMSTextMessage' => [
                        'message' => $message
                    ]
                ]
            ]);
            if ($response->status() === 401) {
                $token = $this->refreshToken();
                return $this->sendSMS($phoneNumber, $message);
            }
            if ($response->failed()) {
                throw new \Exception('Orange SMS API Error: ' . $response->body());
            }
            return true;
        } catch (\Exception $e) {
            throw new \Exception('SMS sending failed: ' . $e->getMessage());
        }
    }

    protected function formatPhoneNumber($phoneNumber)
    {
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
        return $phoneNumber;
    }
}