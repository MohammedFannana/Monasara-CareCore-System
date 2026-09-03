<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use App\Notifications\ResetPasswordNotification;


class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */


    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'guard' => ['required', 'string'],
        ]);

        // ربط guard مع broker
        $brokers = [
            'researcher'  => 'researchers',
            'association' => 'associations',
            'sponsor'     => 'sponsors',
            'orphan'      => 'orphans',
        ];

        $broker = $brokers[$request->guard] ?? 'users';

        // تحديد الموديل بناءً على guard
        $userModel = [
            'researcher'  => \App\Models\Researcher::class,
            'association' => \App\Models\Association::class,
            'sponsor'     => \App\Models\Sponsor::class,
            'orphan'      => \App\Models\Orphan::class,
        ][$request->guard] ?? \App\Models\User::class;

        $user = $userModel::where('email', $request->email)->first();

        if (!$user) {
            return back()->withInput($request->only('email'))
                        ->withErrors(['email' => 'البريد الإلكتروني غير موجود']);
        }

        // إنشاء token يدوياً
        $token = Password::broker($broker)->createToken($user);

        // إرسال Notification مع تمرير guard
        $user->notify(new ResetPasswordNotification($token, $request->guard));

        return back()->with('status', 'تم إرسال رابط استعادة كلمة المرور بنجاح!');
    }


}
