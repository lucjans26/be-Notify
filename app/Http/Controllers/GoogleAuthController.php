<?php

namespace App\Http\Controllers;


use App\Classes\Responses\InvalidResponse;
use App\Classes\Responses\ValidResponse;
use App\Models\User;
use App\Traits\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::where('email', '=', $googleUser->email)->first();
        if(!$user)
        {
           $newUser = new User(['email' => $googleUser->email, 'name' => $googleUser->name, 'password' => Hash::make(random_bytes(24))]);
           $newUser->save();
           Auth::login($newUser);
           $response = new ValidResponse($newUser);
           return response()->json($response, 200);
        }
        Auth::login($user, true);
        $pat = $user->createToken(IdGenerator::requestTokenId(), ['artist', 'album'])->plainTextToken;
        $response = new ValidResponse([$user, $pat]);
        return response()->json($response, 200);
    }
}
