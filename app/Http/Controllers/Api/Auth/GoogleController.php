<?php

namespace App\Http\Controllers\Api\Auth;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Str;
class GoogleController extends Controller
{
    public function loginWithGoogle(Request $request)
    {
        $request->validate([
            'id_token' => 'required|string',
        ]);

        try {
            $googleUser = Socialite::driver('google')
                ->stateless()
                ->userFromToken($request->id_token);

            $user = User::firstOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'first_name' => $googleUser->getName(),
                    'last_name' => '',
                    'password' => bcrypt(Str::random(16)),
                    'email_verified_at' => now(),
                ]
            );

            $token = $user->createToken('google-login')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Google login failed',
                'error' => $e->getMessage(),
            ], 422);
        }
    }
}
