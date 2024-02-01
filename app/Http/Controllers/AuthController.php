<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    function Register(RegisterRequest $request) {
        $payload = $request->validated();

        if (User::firstWhere("email", $payload["email"])) {
            return response()->json([
                "message" => "Akun sudah terdaftar"
            ], 401);
        }

        Hash::make($payload["password"]);

        $user = User::create($payload);

        if ($user) {
            return response()->json(["message" => "Register sukses"], 201);
        }
    }

    function Login(LoginRequest $request) {

        $payload = $request->validated();

         $user = User::firstWhere("email", $payload["email"]);

         if ($user) {
            if (Hash::check($payload["password"], $user->password)) {
                $token = $user->createToken("auth_token")->plainTextToken;

                return response()->json([
                    "message" => "Login berhasil",
                    "user" => $user,
                    "token" => $token
                ], 200);
            } else {
                return response()->json([
                    "message" => "Password salah"
                ], 401);
            }
         } else {
            return response()->json([
                "message" => "Akun tidak terdaftar"
            ], 404);
         }
    }

    function User() {
        $user = Auth::user();

        return response()->json($user, 200);
    }
}
