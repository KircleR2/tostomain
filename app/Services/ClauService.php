<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

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

    /**
     * Test the API connection and credentials
     */
    public function testConnection()
    {
        $headers = [
            'Content-Type' => 'application/json',
            'APPID' => $this->APPID,
            'apikey' => $this->API_AUTH_KEY,
            'apikeyProvider' => $this->API_KEY_PROVIDER,
        ];

        $endpoint = $this->API_URL . '/ext/v2/iniciar_sesion_ext';

        try {
            // First, try a simple GET request to the base URL to check if it's reachable
            $baseResponse = Http::withoutVerifying()->get($this->API_URL);
            
            $result = [
                'base_url_reachable' => $baseResponse->successful(),
                'base_url_status' => $baseResponse->status(),
                'base_url_response' => $baseResponse->body(),
            ];
            
            // Now try a POST request with minimal data to check authentication
            $testResponse = Http::withoutVerifying()
                ->withHeaders($headers)
                ->post($endpoint, [
                    'email' => 'test@example.com',
                    'password' => 'test123'
                ]);
                
            $result['auth_test_status'] = $testResponse->status();
            $result['auth_test_successful'] = $testResponse->successful();
            $result['auth_test_response'] = $testResponse->body();
            $result['auth_test_headers'] = $testResponse->headers();
            
            return $result;
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ];
        }
    }

    public function login ($email, $password)
    {
        $headers = [
            'Content-Type' => 'application/json',
            'APPID' => $this->APPID,
            'apikey' => $this->API_AUTH_KEY,
            'apikeyProvider' => $this->API_KEY_PROVIDER,
        ];

        $endpoint = config('clau.api_url') . '/ext/v2/iniciar_sesion_ext';

        try {
            $response = Http::withoutVerifying()
                ->withHeaders($headers)
                ->post($endpoint, [
                    'email' => $email,
                    'password' => $password
                ]);
                
            return $response;
        } catch (\Exception $e) {
            throw $e;
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
