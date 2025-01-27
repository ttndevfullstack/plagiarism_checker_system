<?php

namespace App\Http\Controllers;

use Filament\Http\Controllers\Auth\LogoutController as BaseLoginController;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FilamentLoginController extends BaseLoginController
{
    public function store(\Illuminate\Http\Request $request)
    {
        // Use the parent login logic
        $response = parent::store($request);

        // Update last_login timestamp
        if (Auth::check()) {
            $user = Auth::user();
            $user->last_login = Carbon::now();
            $user->save();
        }

        return $response;
    }
}
