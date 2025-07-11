<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ClauService
{
    public string $API_URL;
    public string $API_POS_KEY;
    public string $API_AUTH_KEY;
    public string $API_KEY_PROVIDER;
    public string $APPID;
    public string $ORIGIN;

    public function __construct ()
    {
        $this->API_URL = config('clau.api_url');
        $this->API_POS_KEY = config('clau.api_pos_key');
        $this->API_AUTH_KEY = config('clau.api_auth_key');
        $this->API_KEY_PROVIDER = config('clau.apikey_provider');
        $this->APPID = config('clau.appid');
        $this->ORIGIN = config('clau.origin');
    }

    public function login ($email, $password)
    {
        $headers = [
            'Content-Type' => 'application/json',
            'APPID' => $this->APPID,
            'apikey' => $this->API_AUTH_KEY,
            'apikeyProvider' => $this->API_KEY_PROVIDER,
        ];

        Log::info('Login attempt', [
            'email' => $email,
            'api_url' => config('clau.api_url'),
            'appid' => $this->APPID,
            'has_auth_key' => !empty($this->API_AUTH_KEY),
            'has_provider_key' => !empty($this->API_KEY_PROVIDER),
        ]);

        try {
            $response = Http::withoutVerifying()
                ->withHeaders($headers)
                ->post(config('clau.api_url') . '/ext/v2/iniciar_sesion_ext', [
                    'email' => $email,
                    'password' => $password
                ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Login response', [
                    'status' => $response->status(),
                    'code' => $data['codigoRespuesta'] ?? 'missing',
                    'message' => $data['msj'] ?? 'No message',
                    'has_token' => isset($data['token']),
                ]);
            } else {
                Log::error('Login failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
            }

            return $response;
        } catch (\Exception $e) {
            Log::error('Exception during login', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Create a manual response to maintain the expected interface
            return Http::response(['msj' => 'Error connecting to authentication service: ' . $e->getMessage()], 500);
        }
    }

    public function register($register_data) {
        $headers = [
            'Content-Type' => 'application/json',
            'apikey' => $this->API_POS_KEY,
            'APPID' => $this->APPID,
            'origen' => $this->ORIGIN,
        ];

        $data = [
            'usuarioCorreo' => $register_data['email'],
            'usuarioName' => $register_data['firstname'],
            'usuarioLastName' => $register_data['lastname'],
            'usuarioTelefono' => $register_data['phone'],
            'usuarioBirth' => $register_data['birthday'],
            'usuarioPassword' => $register_data['password'],
        ];

        if (isset($register_data['ref'])) {
            $data['usuarioCodigoReferido'] = $register_data['ref'];
        }

        return Http::withoutVerifying()
            ->withHeaders($headers)
            ->post(config('clau.api_url') . '/ext/registro/registro_api', $data);
    }

    public function getUserData ($token)
    {
        $headers = [
            'Content-Type' => 'application/json',
            'APPID' => $this->APPID,
            'apikey' => $this->API_AUTH_KEY,
        ];

        return Http::withoutVerifying()
            ->withHeaders($headers)
            ->post(config('clau.api_url') . '/ext/v2/consultar_informacion_usuario_ext', [
                'token' => $token
            ]);
    }

    public function getStorePoints ($token)
    {
        $headers = [
            'Content-Type' => 'application/json',
            'APPID' => $this->APPID,
            'apikey' => $this->API_AUTH_KEY,
        ];

        return Http::withoutVerifying()
            ->withHeaders($headers)
            ->post(config('clau.api_url') . '/ext/v2/consulta_tienda_de_puntos_ext', [
                'token' => $token
            ]);
    }

    public function buyProduct ($token, $productId)
    {
        $headers = [
            'Content-Type' => 'application/json',
            'APPID' => $this->APPID,
            'apikey' => $this->API_AUTH_KEY,
        ];

        return Http::withoutVerifying()
            ->withHeaders($headers)
            ->post(config('clau.api_url') . '/ext/v2/compra_tienda_puntos_ext', [
                'token' => $token,
                'regaloId' => $productId
            ]);
    }

    public function getGifts ($token)
    {
        $headers = [
            'Content-Type' => 'application/json',
            'APPID' => $this->APPID,
            'apikey' => $this->API_AUTH_KEY,
        ];

        return Http::withoutVerifying()
            ->withHeaders($headers)
            ->post(config('clau.api_url') . '/ext/v2/consultar_regalos_usuario_ext', [
                'token' => $token
            ]);
    }

    public function recovery ($email)
    {
        $headers = [
            'Content-Type' => 'application/json',
            'APPID' => $this->APPID,
            'apikey' => $this->API_AUTH_KEY,
        ];

        return Http::withoutVerifying()
            ->withHeaders($headers)
            ->post(config('clau.api_url') . '/ext/v2/solicitar_reset_password_ext', [
                'email' => $email
            ]);
    }
}
