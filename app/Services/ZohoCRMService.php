<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class ZohoCRMService
{
    public string $zohoAuthToken;
    public Carbon $zohoAuthTokenExpiration;

    public function __construct ()
    {
        $this->zohoAuthToken = config('zoho.auth_token');
        $this->zohoAuthTokenExpiration = Carbon::now(); // Establecer una fecha/hora inicial
    }

    public function newLead($data)
    {
        if ($this->zohoAuthTokenExpiration->isPast()) {
            $this->refreshToken(); // Método para renovar el token
        }

        try {
            $headers = [
                'Content-Type' => 'application/json',
                'Authorization' => 'Zoho-oauthtoken ' . $this->zohoAuthToken,
            ];

            $response = Http::withoutVerifying()
                ->withHeaders($headers)
                ->post(config('zoho.api_url') . '/Leads/upsert', [
                    'data' => [
                        [
                            "Lead_Source" => "INVU Webhook",
                            "Last_Name" => $data['name'],
                            "Email" => $data['email'],
                            "Mobile" => $data['phone'],
                        ]
                    ]
                ]);

            if ($response->successful()) {
                return $response;
            }

            throw new \Exception('Zoho CRM API request failed: ' . $response->body());
        } catch (\Exception $e) {
            // Manejo de excepciones
            return $e->getMessage();
        }
    }

    private function refreshToken()
    {
        try {
            $response = Http::withoutVerifying()
                ->withQueryParameters([
                    'client_id' => config('zoho.client_id'),
                    'grant_type' => 'refresh_token',
                    'client_secret' => config('zoho.client_secret'),
                    'refresh_token' => config('zoho.refresh_token')
                ])
                ->post(config('zoho.api_refresh_url'));

            if ($response->successful()) {
                $this->zohoAuthToken = $response->json('access_token');
                $this->zohoAuthTokenExpiration = Carbon::now()->addHour(); // Establecer la nueva fecha/hora de expiración
            } else {
                throw new \Exception('Zoho CRM token refresh failed: ' . $response->body());
            }
        } catch (\Exception $e) {
            // Manejo de excepciones
            return $e->getMessage();
        }
    }
}
