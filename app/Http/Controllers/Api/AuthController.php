<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//use Illuminate\Validation\Validator;
use App\Models\User;
use Auth;
use Validator;
class AuthController extends Controller
{
    public function register(Request $request ){
        $validator = Validator::make($request->all(),[
           'name' => 'required',
           'email' => 'required|email',
           'password' => 'required',
           'role' => 'required',
        ]);

        if($validator->fails()){
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];

            return response()->json($response,400);
        }
            $inputData = $request->all();
            $user = User::create($inputData);

            $success['token'] = $user->createToken('MyApp')->plainTextToken;
            $success['name'] = $user->name;

            $response = [
                'success' => true,
                'data' => $success,
                'message' => 'User Register Successfully',
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
            ];
            return response()->json($response,200);
        }else{
            $response = [
                'success' => false,
                'message' => 'Unauthorized',
            ];
            return response()->json($response,400);
        }

    }

}
