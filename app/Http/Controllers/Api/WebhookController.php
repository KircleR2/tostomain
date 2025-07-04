<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserList;
use App\Services\ZohoCRMService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handleWebhook (Request $request)
    {
        try {
            if ($request->header('webhookkey') === config('clau.webhook_key')) {
                if ($request->input('origin') === config('clau.webhook_origin')) {
                    $this->validate($request, [
                        'name' => 'required',
                        'email' => 'required|email',
                        'phone' => 'nullable',
                        'birthday' => 'nullable|date'
                    ]);

                    $user_data = [
                        'name' => $request->input('name') . ' ' . $request->input('lastname'),
                        'email' => $request->input('email'),
                        'phone' => $request->input('phone'),
                        'birthday' => $request->input('Birthday')
                    ];

                    $zohoCRMService = new ZohoCRMService();
                    $response = $zohoCRMService->newLead($user_data);
                    Log::debug($response);

                    $existingUser = UserList::whereEmail($user_data['email'])->orWhere('phone', $user_data['phone'])->first();
                    if ($existingUser) {
                        $existingUser->update($user_data);
                        return response()->json(['message' => 'User updated successfully']);
                    }

                    UserList::create($user_data);

                    return response()->json([
                        'message' => 'User created successfully',
                        'zoho_response' => $response->successful()
                    ]);

                }
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => $e->getMessage()]);
        }

        return response('Webhook error');
    }
}
