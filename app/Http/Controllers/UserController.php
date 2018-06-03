<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Train;
use JWTAuth;
use Validator, Hash;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    protected $user;

    public function __construct(User $user) {
        $this->user = $user;
    }

    public function login(Request $request) {
        $credentials = $request->only(['email', 'password']);

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        return response()->json(compact('token'));
    }

    public function register(Request $request) {
        $credentials = $request->only(['name', 'email', 'password']);

        $credentials = [
            'name' => $credentials['name'],
            'email' => $credentials['email'],
            'password' => Hash::make($credentials['password'])
        ];

        try {
            $user = $this->user->create($credentials);
        } catch(Exception $e) {
            return response()->json(['error' => 'User already exists'], 409);
        }

        return response()->json(['success' => 'User successfully registered'], 201);
    }

    public function validateToken(Request $request) {
        $this->validate($request, ['token' => 'required']);

        return response()->json(['success' => true, 'message' => 'Token still valid'], 200);
    }

    public function logout(Request $request) {
        $this->validate($request, ['token' => 'required']);

        // set train to empty (no driver)
        $user = JWTAuth::parseToken()->authenticate();
        $train = $user->train;
        $train->driver_id = NULL;
        $train->save();

        try {
            JWTAuth::invalidate($request->input('token'));
            return response()->json(['success' => true, 'message' => 'You have successfully logged out.']);
        } catch(JWTException $e) {
            return response()->json(['success' => false, 'error' => 'Failed to logout, please try again.'], 500);
        }
    }
}
