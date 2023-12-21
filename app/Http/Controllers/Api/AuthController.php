<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use Illuminate\Http\Request;
//use Illuminate\Validation\Validator;
use App\Models\User;
use App\Models\Image;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;
use Auth;
use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
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

    public function getUserData(Request $request){
        $AuthUserInfo = $request->user();
        $UserInfo     = User::with('image')->find($AuthUserInfo['id']);
        $UserInfo     = $UserInfo->ToArray();
        $IistingInfo  = User::with('listing.single_image')->find($AuthUserInfo['id']);
        $IistingInfo  = $IistingInfo->ToArray();
        return ['userInfo'=> $UserInfo ,'listingInfo' => $IistingInfo];
    }

    public function profileSetting(Request $request){
        $AuthUserInfo = $request->user();
        $UserInfo     = User::with('image')->find($AuthUserInfo['id']);
        return ['userInfo'=>$UserInfo->ToArray()];
    }

    public function saveProfileSetting(Request $request){
        $AuthUserInfo = $request->all();
        $UserInfo     = User::find($AuthUserInfo['user_id']);
        $UserInfo->name  = $AuthUserInfo['name'];
        $UserInfo->email = $AuthUserInfo['email'];


        if ($AuthUserInfo['ChangePass'] !== null){
            $newPass = Hash::make($AuthUserInfo['ChangePass']);
            DB::table('users')->where('id', $AuthUserInfo['user_id'])->update(['password' => $newPass]);
        }
        $UserInfo->save();
        if(!empty($AuthUserInfo['updateAvatar'])){
            $imageInfo = Image::find($AuthUserInfo['image_id']);
            $imageInfo->path =  $AuthUserInfo['updateAvatar'];
            $imageInfo->save();
        }

        $response = [
            'success' => true,
            'message' => 'User Update Successfully ',
            'status' => 200,
        ];
        return response()->json($response,200);
    }
    public function doFeature(Request $request){
        $listingInfo     = Listing::find($request->id);
        $featureCheck    = $listingInfo->is_featured;
        if($featureCheck){
            $listingInfo->is_featured =0;
        }else{
            $listingInfo->is_featured =1;
        }
        $listingInfo->save();
        $response = [
            'success' => true,
            'message' => 'List Featured!!',
            'status' => 200,
        ];
        return response()->json($response,200);
    }
    public function getListingDetails(Request $request){
        $AuthUserInfo = $request->user();
        $UserInfo     = User::with('image')->find($AuthUserInfo['id']);
        $UserInfo     = $UserInfo->ToArray();

        $listingInfo     = Listing::with('image')->find($request->id);
        $listingInfo     = $listingInfo->ToArray();
        return ['userInfo'=> $UserInfo ,'listingInfo' => $listingInfo];
    }
}
