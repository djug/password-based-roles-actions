<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use Auth;

class AuthController extends Controller
{
    public function getLogin()
    {
        return view('auth.login');
    }

    public function postLogin(Request $request)
    {
        $data = $request->all();

        $email = $data['email'];
        $password = $data['password'];

        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            return redirect('/home');
        } else {
           return redirect()->route('login')->with('authentication-issue', true);
        }
    }


    public function getRegister()
    {
        return view('auth.register');
    }

    public function postRegister(Request $request)
    {
        $data = $request->all();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        $user->master_account_id = $user->id;
        $user->save();
        return redirect('/');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

}
