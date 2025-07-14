<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ClauService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthController extends Controller
{
    private $clauService;
    public function __construct (ClauService $clauService)
    {
        $this->clauService = $clauService;
    }

    public function login (Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
        ]);

        $login_data = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];

        $response = $this->clauService->login($login_data['email'], $login_data['password']);
        if ($response->successful()) {
            $responseData = $response->json();

            if (isset($responseData['codigoRespuesta']) && $responseData['codigoRespuesta'] === 0) {
                $request->session()->put('clauToken', $responseData['token']);
                return response()->json([
                    'code' => 0,
                    'message' => 'Haz iniciado sesión correctamente',
                ])->setStatusCode(Response::HTTP_OK);

            }

            return response()->json([
                'code' => $responseData['codigoRespuesta'],
                'message' => $responseData['msj'],
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'message' => 'Error en la solicitud a la API',
        ])->setStatusCode($response->status());
    }

    public function register (Request $request)
    {
        $this->validate($request, [
            'fullname' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'birthday' => 'required|date',
            'password' => 'required',
            'ref' => 'nullable'
        ]);

        $nameExploded = explode(' ', $request->get('fullname'));
        $firstName = $nameExploded[0] ?? '';
        $lastName = $nameExploded[1] ?? '';

        $register_data = [
            'fullname' => $request->get('fullname'),
            'firstname' => $firstName,
            'lastname' => $lastName,
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'birthday' => $request->input('birthday'),
            'password' => $request->input('password'),
        ];

        if ($request->input('ref')) {
            $register_data['ref'] = $request->input('ref');
        }

        $response = $this->clauService->register($register_data);
        if ($response->successful()) {
            $responseData = $response->json();

            if (isset($responseData['CodRes']) && $responseData['CodRes'] === 0) {
                $responseLogin = $this->clauService->login($request->input('email'), $request->input('password'));
                if ($responseLogin->successful()) {
                    $responseLoginData = $responseLogin->json();

                    if (isset($responseLoginData['codigoRespuesta']) && $responseLoginData['codigoRespuesta'] === 0) {
                        $request->session()->put('clauToken', $responseLoginData['token']);

                        return response()->json([
                            'code' => 0,
                            'message' => 'Registro completado correctamente',
                        ])->setStatusCode(Response::HTTP_OK);
                    }
                }
            }

            return response()->json([
                'code' => $responseData['CodRes'],
                'message' => $responseData['Msj'],
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'message' => 'Error en la solicitud a la API',
        ])->setStatusCode($response->status());
    }

    public function recovery_password (Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
        ]);

        $response = $this->clauService->recovery($request->get('email'));
        if ($response->successful()) {
            $responseData = $response->json();

            if (isset($responseData['CodRes']) && $responseData['CodRes'] === 0) {
                return response()->json([
                    'code' => 0,
                    'message' => 'Correo de recuperación enviado',
                ])->setStatusCode(Response::HTTP_OK);
            }

            return response()->json([
                'code' => $responseData['CodRes'],
                'message' => $responseData['Msj'],
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'message' => 'Error en la solicitud a la API',
        ])->setStatusCode($response->status());
    }
}
