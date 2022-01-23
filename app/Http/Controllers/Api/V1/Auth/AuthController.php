<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;

class AuthController extends Controller
{
    public function actionMe(Request $request)
    {
        return $request->user();
    }

    public function actionLogin(Request $request)
    {
        // Login request validation
        if (filter_var($request->input('identity'), FILTER_VALIDATE_EMAIL)) {
            $identity = [
                'identity' => 'required|email'
            ];
        } else {
            $identity = [
                'identity' => 'required'
            ];
        }

        $validatedLogin = Validator::make($request->all(), $identity);

        if ($validatedLogin->fails()) {
            return $this->responseUnprocessable($validatedLogin->errors());
        }

        // Check identity which used to login
        if (filter_var($request->input('identity'), FILTER_VALIDATE_EMAIL)) {
            $credential = [
                'email' => $request->input('identity'),
                'password' => $request->input('password')
            ];
        } else {
            $credential = [
                'username' => $request->input('identity'),
                'password' => $request->input('password')
            ];
        }

        // Attemp to login
        if (!Auth::attempt($credential)) {
            return $this->responseUnauthorized($validatedLogin->errors());
        }

        // Provide access token
        $user = Auth::user();
        $token = $user->createToken('token')->accessToken;

//        return response([
//            'message' => 'Success',
//            'token' => $token,
//            'role' => $user->role->name,
//            'email' => $user->email,
//            'nick_name' => $user->contact->nick_name,
//            'full_name' => $user->contact->full_name
//        ]);

        $cookie = cookie('token', $token, 60*24);
        return response(['message' => 'Success'])->withCookie($cookie);
    }

    public function actionLogout(Request $request)
    {
        // Get user by email
        $user = User::where('email', $request->input('email'))->first();

        if (!$user)
            return $this->responseServerError(new MessageBag(['Logout failed']));

        // Remove the access token
        $oAuthAccessTokens = $user->oAuthAccessTokens();
        $oAuthAccessTokens->delete();

        $request->user()->token()->revoke();

//        return $this->responseSuccess('Logout succeed');

        $cookie = Cookie::forget('token');
        return response(['message' => 'Success'])->withCookie($cookie);
    }
}
