<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleOAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $user = Socialite::driver('google')->stateless()->user();
        $findUser = User::where('google_id', $user->id)->first();
        if ($findUser) {
            Auth::login($findUser);
            return redirect()->route('covid.index');
        } else {
            $newUser = new User;
            $newUser->name = $user->name;
            $newUser->email = $user->email;
            $newUser->google_id = $user->id;
            $newUser->password = encrypt('');
            $newUser->save();
            Auth::login($newUser);
            return redirect()->route('covid.index');
        }
    }
}
