<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreUserRequest;
use App\Http\Requests\V1\UpdateUserRequest;
use App\Http\Resources\V1\UserCollection;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function Index(){
        $users = User::paginate();

        return new UserCollection($users);
    }

    public function store(StoreUserRequest $request){
        return new UserResource(User::create($request->all()));
    }

    public function edit($id){
        $user = User::where('id', $id)->first();
        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, $id){
        $user = User::findOrFail($id);
        $user->update($request->all());

        return new UserResource($user);
    }

    public function delete($id){
        User::findOrFail($id)->delete();
    }

    public function reset($id){
        $characters = '3479abcdefghijkmnpqrstuvwxyzACDEFHJKLMNPQRTUVWXY!@#$%^&*-_+=';
        $charactersLength = strlen($characters);
        $newPassword = '';
        for ($i = 0; $i < 8; $i++) {
            $newPassword .= $characters[rand(0, $charactersLength - 1)];
        }

        $user = User::findOrFail($id);
        $user->password = Hash::make($newPassword);
        $user->save();

        return $newPassword;
    }
}