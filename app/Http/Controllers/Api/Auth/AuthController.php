<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendSmsMessage;
use App\User;
use App\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $this->validate($request, [
            'phone_number' => ['required']
        ]);
        $user = User::where('phone_number', $request->phone_number)->first(['id', 'title', 'code', 'first_name', 'last_name', 'phone_number', 'email']);
        $code = rand(1000, 9999);
        if ($user) {
            User::where('phone_number', $request->phone_number)->update([
                'code' => $code
            ]);
            dispatch_now(new SendSmsMessage($request->phone_number, "Your reset code is $code kindly use it to login"));
            return response()->json(['user' => User::where('phone_number', $request->phone_number)->first(['id', 'title', 'code', 'first_name', 'last_name', 'phone_number', 'email']), 'status' => 'existing']);
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
            return response()->json(['user' => User::where('id', $new->id)->first(['id', 'title', 'code', 'first_name', 'last_name', 'phone_number', 'email']), 'status' => 'new']);
        }

    }

    public function codeValidation(Request $request)
    {
        $this->validate($request, [
            'code' => ['required']
        ]);
        $user = User::where('phone_number', $request->phone_number)->first(['id', 'code', 'first_name', 'last_name', 'phone_number', 'email']);
        if ($user->code == $request->code) {
            $token = $user->createToken('MyApp')->accessToken;
            return response()->json(['user' => $user, 'token' => $token]);
        } else {
            return response()->json(['message' => 'Invalid code'])->setStatusCode(404);
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
