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
        
        Log::debug('ClauService initialized', [
            'api_url' => $this->API_URL,
            'appid' => $this->APPID,
            'origin' => $this->ORIGIN
        ]);
    }

    public function login ($email, $password)
    {
        $headers = [
            'Content-Type' => 'application/json',
            'APPID' => $this->APPID,
            'apikey' => $this->API_AUTH_KEY,
            'apikeyProvider' => $this->API_KEY_PROVIDER,
        ];
        
        $url = config('clau.api_url') . '/ext/v2/iniciar_sesion_ext';
        
        Log::debug('ClauService login request', [
            'url' => $url,
            'email' => $email,
            'headers' => array_keys($headers)
        ]);

        try {
            $response = Http::withoutVerifying()
                ->withHeaders($headers)
                ->post($url, [
                    'email' => $email,
                    'password' => $password
                ]);
                
            Log::debug('ClauService login response', [
                'status' => $response->status(),
                'successful' => $response->successful(),
                'body_preview' => substr($response->body(), 0, 100) . '...'
            ]);
            
            return $response;
        } catch (\Exception $e) {
            Log::error('ClauService login exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
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
        
        $url = config('clau.api_url') . '/ext/registro/registro_api';
        
        Log::debug('ClauService register request', [
            'url' => $url,
            'email' => $register_data['email'],
            'headers' => array_keys($headers)
        ]);

        try {
            $response = Http::withoutVerifying()
                ->withHeaders($headers)
                ->post($url, $data);
                
            Log::debug('ClauService register response', [
                'status' => $response->status(),
                'successful' => $response->successful(),
                'body_preview' => substr($response->body(), 0, 100) . '...'
            ]);
            
            return $response;
        } catch (\Exception $e) {
            Log::error('ClauService register exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function getUserData ($token)
    {
        $headers = [
            'Content-Type' => 'application/json',
            'APPID' => $this->APPID,
            'apikey' => $this->API_AUTH_KEY,
        ];
        
        $url = config('clau.api_url') . '/ext/v2/consultar_informacion_usuario_ext';
        
        Log::debug('ClauService getUserData request', [
            'url' => $url,
            'token_length' => strlen($token),
            'headers' => array_keys($headers)
        ]);

        try {
            $response = Http::withoutVerifying()
                ->withHeaders($headers)
                ->post($url, [
                    'token' => $token
                ]);
                
            Log::debug('ClauService getUserData response', [
                'status' => $response->status(),
                'successful' => $response->successful(),
                'body_preview' => substr($response->body(), 0, 100) . '...'
            ]);
            
            return $response;
        } catch (\Exception $e) {
            Log::error('ClauService getUserData exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function getStorePoints ($token)
    {
        $headers = [
            'Content-Type' => 'application/json',
            'APPID' => $this->APPID,
            'apikey' => $this->API_AUTH_KEY,
        ];
        
        $url = config('clau.api_url') . '/ext/v2/consulta_tienda_de_puntos_ext';
        
        Log::debug('ClauService getStorePoints request', [
            'url' => $url,
            'token_length' => strlen($token),
            'headers' => array_keys($headers)
        ]);

        try {
            $response = Http::withoutVerifying()
                ->withHeaders($headers)
                ->post($url, [
                    'token' => $token
                ]);
                
            Log::debug('ClauService getStorePoints response', [
                'status' => $response->status(),
                'successful' => $response->successful(),
                'body_preview' => substr($response->body(), 0, 100) . '...'
            ]);
            
            return $response;
        } catch (\Exception $e) {
            Log::error('ClauService getStorePoints exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function buyProduct ($token, $productId)
    {
        $headers = [
            'Content-Type' => 'application/json',
            'APPID' => $this->APPID,
            'apikey' => $this->API_AUTH_KEY,
        ];
        
        $url = config('clau.api_url') . '/ext/v2/compra_tienda_puntos_ext';
        
        Log::debug('ClauService buyProduct request', [
            'url' => $url,
            'token_length' => strlen($token),
            'product_id' => $productId,
            'headers' => array_keys($headers)
        ]);

        try {
            $response = Http::withoutVerifying()
                ->withHeaders($headers)
                ->post($url, [
                    'token' => $token,
                    'regaloId' => $productId
                ]);
                
            Log::debug('ClauService buyProduct response', [
                'status' => $response->status(),
                'successful' => $response->successful(),
                'body_preview' => substr($response->body(), 0, 100) . '...'
            ]);
            
            return $response;
        } catch (\Exception $e) {
            Log::error('ClauService buyProduct exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function getGifts ($token)
    {
        $headers = [
            'Content-Type' => 'application/json',
            'APPID' => $this->APPID,
            'apikey' => $this->API_AUTH_KEY,
        ];
        
        $url = config('clau.api_url') . '/ext/v2/consultar_regalos_usuario_ext';
        
        Log::debug('ClauService getGifts request', [
            'url' => $url,
            'token_length' => strlen($token),
            'headers' => array_keys($headers)
        ]);

        try {
            $response = Http::withoutVerifying()
                ->withHeaders($headers)
                ->post($url, [
                    'token' => $token
                ]);
                
            Log::debug('ClauService getGifts response', [
                'status' => $response->status(),
                'successful' => $response->successful(),
                'body_preview' => substr($response->body(), 0, 100) . '...'
            ]);
            
            return $response;
        } catch (\Exception $e) {
            Log::error('ClauService getGifts exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function recovery ($email)
    {
        $headers = [
            'Content-Type' => 'application/json',
            'APPID' => $this->APPID,
            'apikey' => $this->API_AUTH_KEY,
        ];
        
        $url = config('clau.api_url') . '/ext/v2/solicitar_reset_password_ext';
        
        Log::debug('ClauService recovery request', [
            'url' => $url,
            'email' => $email,
            'headers' => array_keys($headers)
        ]);

        try {
            $response = Http::withoutVerifying()
                ->withHeaders($headers)
                ->post($url, [
                    'email' => $email
                ]);
                
            Log::debug('ClauService recovery response', [
                'status' => $response->status(),
                'successful' => $response->successful(),
                'body_preview' => substr($response->body(), 0, 100) . '...'
            ]);
            
            return $response;
        } catch (\Exception $e) {
            Log::error('ClauService recovery exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
