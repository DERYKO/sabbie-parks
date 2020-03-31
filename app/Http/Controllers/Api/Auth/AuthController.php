<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\User;
use App\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $this->validate($request,[
           'phone_number' => ['required']
        ]);
        $user = User::where('phone_number', $request->phone_number)->first(['first_name', 'last_name', 'phone_number', 'email']);
        $code = rand(1000, 9999);
        if ($user) {
            $user->update([
                'code' => $code
            ]);
            $token = $user->createToken('auth_token')->accessToken;
            return response()->json(['user' => $user, 'token' => $token, 'status' => 'existing']);
        } else {
            $new = User::create([
                'phone_number' => $request->phone_number,
                'code' => $code,
                'password' => Hash::make($request->phone_number)
            ]);
            Wallet::create([
                'user_id' => $new->id,
                'transaction_type' => 'Account Initialization',
                'debit' => 0.0,
                'credit' => 0.0,
                'balance' => 0.0
            ]);
            $token = $new->createToken('auth_token')->accessToken;
            return response()->json(['user' => $new, 'token' => $token, 'status' => 'new customer']);
        }

    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}
