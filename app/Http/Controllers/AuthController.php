<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\VerificationMail;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $user = User::create([

            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'verification_code' => (string) Str::uuid(),
            'is_verified' => false,
        ]);


        $this->sendVerificationEmail($user);

        return response()->json(['message' => 'User registered successfully. Please check your email for verification.']);
    }

    private function sendVerificationEmail(User $user)
    {

        $verificationCode = $user->verification_code;

        \Log::info("Verification Code: $verificationCode");

        Mail::to($user->email)->send(new VerificationMail($verificationCode));
    }


    public function verify(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'verification_code' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 400);
        }

        $code = $request->input('verification_code');

        $user = User::where('verification_code', $code)->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid verification code.'], 400);
        }

        $user->update(['is_verified' => true]);

        return response()->json(['message' => 'User verified successfully.']);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            $user = auth()->user();

            if ($user->is_verified) {
                $token = JWTAuth::fromUser($user);

                return response()->json(['user' => $user, 'token' => $token]);
            } else {
                return response()->json(['message' => 'User not verified.'], 401);
            }
        }

        return response()->json(['message' => 'Invalid credentials.'], 401);
    }
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'User logged out successfully.']);
    }
}
