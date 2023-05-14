<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function registerUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if($validator->fails())
        {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];

            return response()->json($response);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        $success['token'] = $user->createToken('apiKey')->plainTextToken;
        $success['name'] = $user->name;
        $success['user_id'] = $user->id;

        $response = [
            'success' => true,
            'data' => $success, 
            'message' => 'User registered successfully'
        ];

        return response()->json($response, 200);
    }

    public function loginUser(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validator->fails())
        {
            $response = [
                'success' => false,
                'message' => 'Both username and password is required'
            ];
        }

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password]))
        {
            $user = Auth::user();

            $success['token'] = $user->createToken('apiKey')->plainTextToken;
            $success['name'] = $user->name;
            $success['user_id'] = $user->id;

            $response = [
                'success' => true,
                'message' => 'Login successful',
                'data' => $success
            ];

            return response()->json($response, 200);
        }

        $response = [
            'success' => false,
            'message' => 'Invalid email or password'
        ];

        return response()->json($response);
    }


    public function logoutUser()
    {
        Auth::user()->tokens()->delete();
        $response = [
            'success' => true,
            'message' => 'User logged out'
        ];

        return response()->json($response, 200);
    }

    public function getUser(Request $request)
    {
        return $request->user();
    }
}
