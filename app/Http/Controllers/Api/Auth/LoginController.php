<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if(!$user || !Hash::check($request->password, $user->password)){
            throw ValidationException::withMessages([
                'email' => ['The credential you entered are incorrect.']
            ]);
        }

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('blog_api_token')->plainTextToken
        ]);
    }
}
