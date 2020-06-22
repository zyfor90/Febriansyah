<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Helper;
use App\User;
use Hash;
use Auth;

class LoginController extends Controller
{
    use Helper;

    /**
     * Login User
     *
     * @return Illuminate\Http\Response
     */
    public function login()
    {
        $attempt = Auth::attempt([
            'email' => request('email'),
            'password' => request('password'),
        ]);
        
        if (!$attempt) {
            return response()->json(['status' => 'fail', 'message' => 'Email/Password salah.'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('appToken')->accessToken;

        $response = response()->json([
            'status' => 'success',
            'data' => [
                'token' => $token,
                'user' => $user,
            ]], 200);

        return $response;
    }

    /**
     * Logout
     *
     * @param Illuminate\Http\Request $request
     * @return Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        if ($request->user()) {
            $user = $request->user()->token();
            $user->revoke();

            return response()->json(['status' => 'success', 'message' => 'Berhasil Log out'], 200);
        }
        return response()->json(['status' => 'fail', 'data' => ['message' => 'Terjadi Kesalahan.']], 400);
    }
}
