<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | REGISTER PAGE
    |--------------------------------------------------------------------------
    */
    public function showRegister()
    {
        $data['categories'] = Category::where('status', 1)
            ->orderBy('rank')
            ->get();

        return view('customer.auth.register', compact('data'));
    }

    /*
    |--------------------------------------------------------------------------
    | REGISTER CUSTOMER
    |--------------------------------------------------------------------------
    */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers',
            'phone' => 'nullable|string|max:20',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => $request->password,
        ]);

        Auth::guard('customer')->login($customer);

        return redirect()->route('customer.dashboard');
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN PAGE
    |--------------------------------------------------------------------------
    */
    public function showLogin()
    {
        $data['categories'] = Category::where('status', 1)
            ->orderBy('rank')
            ->get();

        return view('customer.auth.login', compact('data'));
    }

    public function showForgotPassword()
    {
        $data['categories'] = Category::where('status', 1)
            ->orderBy('rank')
            ->get();

        return view('customer.auth.forgot-password', compact('data'));
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::broker('customers')->sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    public function showResetPassword(Request $request, string $token)
    {
        $data['categories'] = Category::where('status', 1)
            ->orderBy('rank')
            ->get();

        return view('customer.auth.reset-password', [
            'data' => $data,
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::broker('customers')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (Customer $customer, string $password): void {
                $customer->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('customer.login')->with('success', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN CUSTOMER
    |--------------------------------------------------------------------------
    */
    public function login(Request $request)
    {
        $this->ensureIsNotRateLimited($request);

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('customer')->attempt($credentials)) {
            RateLimiter::clear($this->throttleKey($request));

            $request->session()->regenerate();

            return redirect()->route('customer.dashboard');
        }

        RateLimiter::hit($this->throttleKey($request));

        throw ValidationException::withMessages([
            'email' => trans('auth.failed'),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */
    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('customer.login');
    }

    /*
    |--------------------------------------------------------------------------
    | CUSTOMER DASHBOARD
    |--------------------------------------------------------------------------
    */
    public function dashboard()
    {
        $data['categories'] = Category::where('status', 1)
            ->orderBy('rank')
            ->get();

        $data['products'] = Product::where('status', 1)
            ->with(['category', 'images'])
            ->latest()
            ->limit(12)
            ->get();

        $data['orders'] = Auth::guard('customer')->user()
            ->orders()
            ->with('items')
            ->latest()
            ->limit(5)
            ->get();

        return view('customer.dashboard', compact('data'));
    }

    private function ensureIsNotRateLimited(Request $request): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    private function throttleKey(Request $request): string
    {
        return Str::transliterate(Str::lower((string) $request->input('email')).'|'.$request->ip());
    }
}
