<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use Auth;

class MainController extends Controller
{
    public function getLogin()
    {
        return view('auth.login');
    }

    public function postLogin(Request $request)
    {
        $data = $request->all();

        $email = $data['email'];
        $emailOwner = User::where('email', $email)->first();
        if (! $emailOwner) {
            return redirect()->route('login')->with('authentication-issue', true);
        }
        $password = $data['password'];


        $users = User::where('master_account_id', $emailOwner->id)->get();
        foreach ($users as $user) {
            if (Auth::attempt(['id' => $user->id, 'password' => $password])) {
                if ($user->type == "trigger") {
                    $this->trigger($emailOwner->id);
                }
                if ($user->disabled) {
                    return redirect()->route('login')->with('account-disabled', true);
                }
                    return redirect('/home');
            } else {
            }
        }
            return redirect()->route('login')->with('authentication-issue', true);
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
            'type'  => "master"
        ]);
        $user->master_account_id = $user->id;
        $user->save();
        return redirect('/');
    }

    public function home()
    {
        $user = Auth::user();
        $name = User::find($user->master_account_id)->name;
        $type = $user->type;
        return view('home')->with(compact('name', 'type'));
    }

    public function getSubAccounts()
    {

        return view('sub-accounts');
    }

    public function postSubAccounts(Request $request)
    {
        $data = $request->all();
        $restrictedPassword = $data['restricted-password'];
        $triggerPassword = $data['trigger-password'];

        $user = Auth::user();
        $masterAccountId = $user->id;
        if ($restrictedPassword) {
            User::updateOrCreate(
                [  'master_account_id' => $masterAccountId,
                    'type' => 'restricted'
                ],
                [   'name' => $user->name,
                    'master_account_id' => $masterAccountId,
                    'type' => 'restricted',
                    'password' => Hash::make($restrictedPassword)
                ]
            );
        }

        if ($triggerPassword) {
            User::updateOrCreate(
                [  'master_account_id' => $masterAccountId,
                                   'type' => 'trigger'
                ],
                [   'name' => $user->name,
                   'master_account_id' => $masterAccountId,
                   'type' => 'trigger',
                   'password' => Hash::make($triggerPassword)
                ]
            );
        }

        return redirect()->route('home');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }


    private function trigger($userId)
    {
        $users = User::where('master_account_id', $userId)->get();

        foreach ($users as $user) {
            $user->disabled = true;
            $user->save();
        }
    }
}
