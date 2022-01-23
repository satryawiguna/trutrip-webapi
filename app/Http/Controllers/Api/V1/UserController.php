<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\User;
use App\Transformers\Auth\RegisterTransfomer;
use App\Transformers\UserTransformer;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function actionUsers(Request $request)
    {
        if (!Auth::user()->can('view', [User::class])) {
            return $this->responseUnauthorized();
        }

        $search = $request->input('search');
        $role_id = $request->input('role_id');
        $status = $request->input('status') ?: 'active';

        $users = new User();

        if ($search) {
            $users = $users->where([
                ['email','LIKE','%'. $search .'%']
            ])->orWhereHas('contact', function($query) use($search) {
                $query->where('full_name', 'LIKE', '%'. $search . '%')
                    ->orWhere('country', 'LIKE', '%'. $search . '%')
                    ->orWhere('state', 'LIKE', '%'. $search . '%')
                    ->orWhere('city', 'LIKE', '%'. $search . '%');
            });
        }

        if ($role_id) {
            $users = $users->where([
                ['role_id','=',$role_id]
            ]);
        }

        if ($status) {
            $users = $users->where([
                ['status','=',$status]
            ]);
        }

        $users = $users->with(['role', 'contact'])->get();

        return fractal($users, new UserTransformer())->toArray();
    }

    public function actionUser($id)
    {
        if (!Auth::user()->can('view', [User::class])) {
            return $this->responseUnauthorized();
        }

        $user = User::with(['role', 'contact'])
            ->find($id);

        if (!$user)
            return $this->responseUnprocessable(new MessageBag(['User is not found']));

        return fractal($user, new UserTransformer())->toArray();
    }

    public function actionUserStore(Request $request)
    {
        if (!Auth::user()->can('create', [User::class])) {
            return $this->responseUnauthorized();
        }

        // Register request validation
        $validatedUserStore = Validator::make($request->all(), [
            'username' => 'required|unique:users|max:255',
            'email' => 'required|unique:users|max:255|email',
            'password' => ['required', 'confirmed', Password::min(8)]
        ]);

        if ($validatedUserStore->fails()) {
            return $this->responseUnprocessable($validatedUserStore->errors());
        }

        try {
            // Store into user
            $user = new User([
                'role_id' => $request->input('role_id'),
                'username' => $request->input('username'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password'))
            ]);
            $user->save();

            // Store into contact
            $user->contact()->save(new Contact([
                'full_name' => $request->input('full_name') ?: null,
                'nick_name' => $request->input('nick_name') ?: null,
                'phone' => $request->input('phone') ?: null
            ]));

            // Send email verification
            event(new Registered($user));

            return fractal($user, new RegisterTransfomer())->toArray();
        } catch (Exception $e) {
            return $this->responseServerError($e->getMessage());
        }
    }

    public function actionUserUpdate(Request $request)
    {
        if (!Auth::user()->can('update', [User::class])) {
            return $this->responseUnauthorized();
        }

        $validatedUserUpdate = Validator::make($request->all(), [
            'username' => 'required|unique:users|max:255',
            'email' => 'required|unique:users|max:255|email',
            'password' => ['required', 'confirmed', Password::min(8)]
        ]);

        if ($validatedUserUpdate->fails()) {
            return $this->responseUnprocessable($validatedUserUpdate->errors());
        }

        $user = User::find($request->input('id'));

        if (!$user)
            return $this->responseUnprocessable(new MessageBag(['User is not found']));

        try {
            $user->update([
                "status" => $request->input('status') ?: $user->status,
                "role_id" => $request->input('role_id') ?: $user->role_id
            ]);

            return fractal($user, new UserTransformer())->toArray();
        } catch (Exception $e) {
            return $this->responseServerError($e->getMessage());
        }
    }

    public function actionUserDelete($id)
    {
        if (!Auth::user()->can('delete', [User::class])) {
            return $this->responseUnauthorized();
        }

        $user = User::find($id);

        if (!$user)
            return $this->responseUnprocessable(new MessageBag(['User is not found']));

        $user->delete();

        return $this->responseSuccess('User deleted');
    }
}
