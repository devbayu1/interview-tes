<?php

namespace App\Http\Controllers\API\Controllers;

use App\Http\Controllers\API\Controllers\Controller;
use App\Http\Requests\API\CodeCheckRequest;
use App\Models\ResetTokenPassword;

class CodeCheckController extends Controller
{
    public function __invoke($token)
    {
        $passwordReset = ResetTokenPassword::firstWhere('token', $token);

        if ($passwordReset->created_at > now()->addHour()) {
            $passwordReset->delete();
            return response(['status' => false, 'message' => 'Token Kedaluwarsa'], 422);
        }

        return response([
            'status' => true,
            'token' => $passwordReset->token,
            'message' => 'Token Valid'
        ], 200);
    }
}
