<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmailVerificationToken;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class ActivationController extends Controller
{
    public function verify($token)
    {
        $record = EmailVerificationToken::where('Token', $token)->first();

        if (!$record) {
            return view('auth.verification-failed');
        }

        if ($record->Used || now()->greaterThan($record->ExpireAt)) {
            return view('auth.verification-failed');
        }

        $user = $record->user;
        if (!$user) {
            return view('auth.verification-failed');
        }

        $user->user_active = 1;
        $user->save();

        $record->Used = true;
        $record->save();

        return view('auth.verification-success');
    }
}
