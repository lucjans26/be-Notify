<?php

namespace App\Http\Controllers;


use App\Classes\Responses\InvalidResponse;
use App\Classes\Responses\ValidResponse;
use App\Models\User;
use App\Traits\IdGenerator;
use App\Traits\OathTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return OathTrait::login('google');
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();
        $pat = OathTrait::loggedInUser($googleUser);
        $response = new ValidResponse([$googleUser, 'accessToken'=>$pat]);
        return response()->json($response, 200);
    }
}
