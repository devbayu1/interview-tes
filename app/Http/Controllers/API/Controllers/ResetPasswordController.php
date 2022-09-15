<?php

namespace App\Http\Controllers\API\Controllers;

use App\Http\Controllers\API\Controllers\Controller;
use App\Http\Requests\API\ResetPasswordRequest;
use App\Models\ResetTokenPassword;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    public function __invoke(ResetPasswordRequest $request)
    {
        $request->validate([
            'token' => 'required|string|exists:reset_token_passwords',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $passwordReset = ResetTokenPassword::firstWhere('token', $request->token);

        if ($passwordReset->created_at > now()->addHour()) {
            $passwordReset->delete();
            return response(['status' => false, 'message' => 'Token Kedaluwarsa'], 422);
        }

        $user = User::firstWhere('email', $passwordReset->email);
        $user->update(['password'=> Hash::make($request->password)]);
        $passwordReset->delete();

        return response(['status' => true, 'message' => 'Password Berhasil Diubah'], 200);
    }
}
