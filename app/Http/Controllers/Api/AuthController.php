<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//use Illuminate\Validation\Validator;
use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;
use Auth;
use Validator;
class AuthController extends Controller
{
    public function register(Request $request ){
        $validator = Validator::make($request->all(),[
           'name' => 'required',
           'email' => 'required|email',
           'password' => 'required|confirmed',
           'password_confirmation' => 'required',
           'role' => 'required',
        ]);

        if($validator->fails()){
            $response = [
                'success' => false,
                'message' => $validator->errors(),
                'status' =>201
            ];

            return response()->json($response,201);
        }
            $inputData = $request->all();
            $user = User::create($inputData);

            $success['token'] = $user->createToken('MyApp')->plainTextToken;
            $success['name'] = $user->name;

            $response = [
                'success' => true,
                'data' => $success,
                'message' => 'User Register Successfully',
                'status' =>200
            ];

            return response()->json($response,200);
    }

    public function login(Request $request ){
        if(Auth::attempt(['email' => $request->email,'password'=> $request->password])){
            $user = Auth::user();
            $success['token'] = $user->createToken('MyApp')->plainTextToken;
            $success['name'] = $user->name;

            $response = [
                'success' => true,
                'data' => $success,
                'message' => 'User Login Successfully',
                'status' => 200,
            ];
            return response()->json($response,200);
        }else{
            $response = [
                'success' => false,
                'message' => 'Unauthorized',
                'status' => 400,
            ];
            return response()->json($response);
        }

    }

    public function logout(){
                $user = Auth::user();
                $user->tokens()->delete();
                $response = [
                    'success' => true,
                    'message' => 'User Logout Successfully ',
                    'status' => 200,
                ];
                return response()->json($response,200);

    }

}
