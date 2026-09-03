<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token'    => ['required'],
            'email'    => ['required', 'email'],
            'guard'    => ['required', 'string'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // ربط guard مع broker + model
        $map = [
            'researcher'  => ['broker' => 'researchers',  'model' => \App\Models\Researcher::class],
            'association' => ['broker' => 'associations', 'model' => \App\Models\Association::class],
            'sponsor'     => ['broker' => 'sponsors',     'model' => \App\Models\Sponsor::class],
            'orphan'      => ['broker' => 'orphans',      'model' => \App\Models\Orphan::class],
        ];

        $config = $map[$request->guard] ?? 'users';

        if (!$config) {
            return back()->withErrors(['guard' => 'نوع الحساب غير صالح']);
        }

        $status = Password::broker($config['broker'])->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
    }

}
