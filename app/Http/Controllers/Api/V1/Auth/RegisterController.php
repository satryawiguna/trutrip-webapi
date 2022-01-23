<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Role;
use App\Models\User;
use App\Notifications\WelcomeEmailNotification;
use App\Transformers\Auth\RegisterTransfomer;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function actionRegister(Request $request)
    {
        // Register request validation
        $validatedRegister = Validator::make($request->all(), [
            'username' => 'required|unique:users|max:255',
            'email' => 'required|unique:users|max:255|email',
            'password' => ['required', 'confirmed', Password::min(8)]
        ]);

        if ($validatedRegister->fails()) {
            return $this->responseUnprocessable($validatedRegister->errors());
        }

        // Get role as member
        $role = Role::where('name', '=', 'Member')->first();

        if (!$role) {
            return $this->responseUnprocessable((new MessageBag(['Role member is not found'])));
        }

        try {
            // Store into user
            $user = new User([
                'role_id' => $role->id,
                'username' => $request->input('username'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password'))
            ]);
            $user->save();

            // Store into contact
            $user->contact()->save(new Contact([
                'full_name' => $request->input('full_name'),
                'nick_name' => $request->input('nick_name'),
                'phone' => $request->input('phone') ?: null
            ]));

            // Send email verification
//            event(new Registered($user));

            // Send welcome email
//            $user->notify(new WelcomeEmailNotification($user));

            return fractal($user, new RegisterTransfomer())->toArray();
        } catch (Exception $e) {
            return $this->responseServerError($e->getMessage());
        }
    }
}
