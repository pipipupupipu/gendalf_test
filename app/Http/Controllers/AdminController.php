<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('name', 'password');

        $user = User::query()->where('name', $credentials['name'])->first();
        $isAdmin = $user->group_id === 1;

        if ($user && $isAdmin && $user->checkPassword($credentials['password'])) {
            $session = new Session();
            $session->set('access_granted', true);
            return response()->json(['redirect' => '/app']);
        }
        abort(401);
    }

    public function logout(Request $request)
    {

        $session = new Session();
        $session->clear();

        return response()->json(['redirect' => '/']);
    }
}
