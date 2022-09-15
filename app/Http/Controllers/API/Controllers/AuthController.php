<?php

namespace App\Http\Controllers\API\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\UserRequest;
use App\Models\User;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Validator;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{

    public function __construct()
    {

        //

    }

    public function register(UserRequest $request)
    {

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()
            ->json(['status'=> true, 'data' => $user, 'access_token' => $token, 'token_type' => 'Bearer']);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()
                ->json(['status'=> false, 'message' => 'Wrong Email or Password'], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()
            ->json(['status'=> true, 'message' => 'Hi ' . $user->name . ', welcome to home', 'access_token' => $token, 'token_type' => 'Bearer']);
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();

        return [
            'status'=> true,
            'message' => 'You have successfully logged out and the token was successfully deleted'
        ];
    }

    public function profile()
    {
        return Auth::user();
    }

    public function redirectSocial($social)
    {
        $validated = $this->validateSocial($social);
        if(!is_null($validated)){
            return $validated;
        }

        return Socialite::driver($social)->stateless()->redirect();
    }

    public function handleSocial($social)
    {
        $validated = $this->validateSocial($social);
        if(!is_null($validated)){
            return $validated;
        }

        try {
            $user = Socialite::driver($social)->stateless()->user();
        } catch (ClientException $exception){
            return response()->json(['error' => 'Invalid Credential'], 422);
        }

        $getUser = User::firstOrCreate([
            'email' => $user->getEmail()
        ],[
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'password' => Hash::make('password')
        ]);

        $getUser->linkedSocialAccounts()->create([
            'provider_name' => $social,
            'provider_id' => $user->getId(),
            'user_id' => $user->getId()
        ]);

        $token = $getUser->createToken('auth_token')->plainTextToken;

        return response()
            ->json(['status'=> true, 'data' => $getUser, 'access_token' => $token, 'token_type' => 'Bearer']);


    }

    protected function validateSocial($social)
    {
        if(!in_array($social, ['google'])){
            return response()->json([
                'error' => 'Please Login Using Google'
            ], 422);
        }
    }

}
