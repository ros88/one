<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Api\V1\UserStoreRequest;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $paginate = $request->count ? $request->count : 15;
        $users = User::paginate($paginate);
        return response($users);
    }

    /**
     * Создание пользователя.
     *
     * @param  App\Http\Requests\Api\V1\UserStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserStoreRequest $request)
    {
        $newUser = new User();
        $newUser->first_name = $request->first_name;
        $newUser->last_name = $request->last_name;
        $newUser->email = $request->email;
        $newUser->role_id = Role::USER;
        $newUser->password = bcrypt($request->password);

        if (isset($request->avatar)) {
            $newUser->addMedia($request->avatar)
            ->toMediaCollection();
        }

        $newUser->save();

        return response([
            'status' => true,
            'user_id' => $newUser->id
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::where('id', $id)->with(['role'])
        ->first();
        $mediaUrl = $user->getFirstMediaUrl();
        $user['avatar_url'] = $mediaUrl;
        unset($user['media']);

        return response($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Логин пользователя по email и паролю.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!empty($user) && Hash::check($request->password, $user->password)) {
            return response($user->createToken('user')->plainTextToken);
        } else {
            return response([
                'status' => false,
                'message' => 'User not found',
            ], 404);
        }
    }

    public function notAuthorization()
    {
        return response([
            'status' => false,
            'message' => 'please log in'
        ], 401);
    }

    public function delete($id, Request $request)
    { 
        $authUser = auth()->user();
        if (Gate::allows('delete') && $authUser->id != $id) {
            $deleteUser = User::findOrFail($id);
            $deleteUser->delete();

            return response([
                'status' => true,
                'message' => 'User deleted',
            ]);
        } else {
            return response([
                'status' => false,
                'message' => 'You have no promission',
            ], 401);
        }
    }
}
