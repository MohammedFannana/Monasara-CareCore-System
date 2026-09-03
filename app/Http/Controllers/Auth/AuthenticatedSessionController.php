<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cookie;


class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
   public function create()
    {
        $guards = ['web', 'sponsor', 'researcher', 'orphan', 'association'];

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // توجيه كل مستخدم على حسب الجارد الخاص به
                return match ($guard) {
                    'orphan' => redirect()->route('orphan.primary.index'),
                    'web' => redirect()->route('admin.association.index'),
                    'association' => redirect()->route('association.orphan.auditor'),
                    'researcher' => redirect()->route('researcher.orphan.index'),
                    'sponsor' => redirect()->route('sponsor.orphan.sponsor.index'),
                };
            }
        }

        return view('auth.login');
    }


    public function createAdmin()
    {

        return view('auth.login-admin');
    }


    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)

    {

        

        $guard = $request->input('guard' , 'association');

        if (empty($guard)) {
            return back()
                ->withErrors([
                    'guard' => 'يرجى اختيار الفئة'
                ])
                ->withInput();
        }


        $request->setGuard($guard)->authenticate();

        $request->session()->regenerate();


        return match ($guard) {
            'orphan' => redirect()->route('orphan.primary.index'),
            'web' => redirect()->route('admin.association.index'),
            'association' => redirect()->route('association.orphan.auditor'),
            'researcher' => redirect()->route('researcher.orphan.index'),
            'sponsor' => redirect()->route('sponsor.orphan.waiting.index')
            // default => redirect()->route('dashboard'),
        };

        // $request->authenticate();


        // return match ($request->guard) {
        //     'orphan' => redirect()->intended(route('orphan')),
        //     'admin'  => redirect()->intended(route('admin.dashboard')),
        //     default  => redirect()->intended(route('dashboard')),
        // };

        // return redirect()->intended(route('dashboard', absolute: false));

        // $request->setGuard('orphan')->authenticate();
        // $request->session()->regenerate();
        // return redirect()->intended(route('orphan'));

    }



    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {

        $guards = ['web', 'sponsor', 'researcher', 'orphan', 'association'];

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                Auth::guard($guard)->logout();
                Cookie::queue(Cookie::forget($guard . '_session'));
            }
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // الأفضل إعادة التوجيه إلى صفحة تسجيل الدخول
        return redirect('/');

    }
}
