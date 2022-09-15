<?php

namespace App\Http\Controllers\API\Controllers;

use App\Http\Controllers\API\Controllers\Controller;
use App\Http\Requests\API\ForgotPasswordRequest;
use App\Mail\SendCodeResetPassword;
use App\Models\ResetTokenPassword;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    public function __invoke(ForgotPasswordRequest $request)
    {
        ResetTokenPassword::where('email', $request->email)->delete();
        $token = md5($request->email);

        $codeData = ResetTokenPassword::create([
            'email' => $request->email,
            'link' => env('APP_URL').'/api/password/token/'.$token,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);


        Mail::to($request->email)->send(new SendCodeResetPassword(env('APP_URL').'/api/password/token/'.$token));
        return response(['status' => true, 'message' => 'Token terkirim'], 200);
    }
}
