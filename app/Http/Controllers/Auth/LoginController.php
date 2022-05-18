<?php

namespace App\Http\Controllers\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class LoginController{

    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleProviderCallback()
    {
        try {
            $user = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            return redirect('/login');
        }
        if(explode("@", $user->email)[1] !== 'edu.unifil.br'){
            if(explode("@", $user->email)[1] !== 'unifil.br' ){
                return redirect()->to('/');
            }
        }
        $existingUser = User::where('email', $user->email)->first();
        if($existingUser){
            auth()->login($existingUser, true);
        } else {
            $newUser                  = new User;
            $newUser->name            = $user->name;
            $newUser->email           = $user->email;
            $newUser->google_id       = $user->id;
            $newUser->avatar_original = $user->avatar_original;
            $newUser->save();

            auth()->login($newUser, true);
        }
        return redirect()->to('/dashboard');
    }
}



