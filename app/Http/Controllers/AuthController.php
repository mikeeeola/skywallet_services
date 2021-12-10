<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Keygen;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'phone' => 'required',
                'password' => 'required|min:5|max:30',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'validation_errors' => $validator->errors(),
            ]);
        } else {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'payment_id' => Keygen::alphanum(7)->prefix('PAY')->generate()
                //Keygen::numeric(7)->prefix('PAY')->generate()
            ]);

            $token = $user->createToken('sample-token')->plainTextToken;

            return response()->json([
                'status' => 200,
                'message' => 'User created successfully',
                'id' => $user->id,
                'email' => $user->email,
                'paymentId' => $user->payment_id,
                'token' => $token
            ]);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'password' => 'required|min:5|max:30',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'validation_errors' => $validator->errors(),
            ]);
        } else {
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Invalid credentials'
                ]);
            } else {
                $token = $user->createToken('sample-token')->plainTextToken;
                return response()->json([
                    'status' => 200,
                    'message' => 'Logged in successfully',
                    'id' => $user->id,
                    'email' => $user->email,
                    'paymentId' => $user->payment_id,
                    'token' => $token
                ]);
            }
        }
    }
}
