<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register', [
            'registrationRoute' => route('register'),
            'willCreateAdmin' => ! $this->hasAdminUser(),
        ]);
    }

    public function createAdmin(Request $request): RedirectResponse|View
    {
        if ($this->hasAdminUser()) {
            return redirect()
                ->route($request->user()?->isAdmin() ? 'admin.users.create' : 'login')
                ->with('status', 'An admin account already exists.');
        }

        return view('auth.register', [
            'adminBootstrapUser' => $request->user(),
            'registrationRoute' => route('admin.register.store'),
            'willCreateAdmin' => true,
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $this->hasAdminUser() ? 'customer' : 'admin',
        ]);

        event(new Registered($user));

        Auth::login($user);

        $redirectRoute = $user->isAdmin() ? 'admin.dashboard' : 'dashboard';

        return redirect(route($redirectRoute, absolute: false));
    }

    public function storeAdmin(Request $request): RedirectResponse
    {
        if ($this->hasAdminUser()) {
            return redirect()
                ->route($request->user()?->isAdmin() ? 'admin.users.create' : 'login')
                ->with('status', 'An admin account already exists.');
        }

        if ($request->user()) {
            $request->user()->forceFill([
                'role' => 'admin',
            ])->save();

            return redirect()->route('admin.dashboard');
        }

        return $this->store($request);
    }

    private function hasAdminUser(): bool
    {
        return User::where('role', 'admin')->exists();
    }
}
