<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use App\User;
use Hash;
use Auth;
use Validator;

class RegisterController extends Controller
{
    /**
     * Register User
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        if($validator->fails()) {
            return response()->json(['status' => 'fail', 'data' => $validator->errors()], 422);
        }

        event(new Registered($user = $this->store($request)));

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
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'required_with:password_confirmation', 'same:password_confirmation'],
        ];

        $validator = Validator::make($data, $rules);

        return $validator;
    }

    /**
     * Store data from registered user 
     * 
     * @param mixed $data
     * @return \Illuminate\Http\Response
     */
    protected function store($data)
    {
        $user = User::create(['password' => Hash::make($data->password)] + $data->all());
        $user->assignRole('Member');
        return $user;
    }
}
