<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'grade_level' => ['string', 'max:255', 'nullable'],
            'contact' => ['string', 'nullable'],
            'gender' => ['string', 'nullable'],
            'id_number' => ['required', 'string', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'image' => '|image|mimes:jpeg,png,jpg,gif|max:2048',

        ]);

        $is_admin = ($request->id_number === '00001') ? true : false;

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'grade_level' => $request->grade_level ?: null,
            'id_number' => $request->id_number,
            'contact' => $request->contact ?: null,
            'gender' => $request->gender ?: null,
            'password' => Hash::make($request->password),
        ]);

        $user->is_admin = $is_admin ? 1 : 0; // Set is_admin to 1 if true, 0 if false


        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('profile_images', 'public');
            $user->image = $imagePath;
        } else {
            // Set a default image path based on gender
            $gender = $request->input('gender'); // Assuming 'gender' is the name of the select input
            if ($gender === 'Male') {
                $user->image = 'Male.png'; // Replace with the actual path for the male default image
            } elseif ($gender === 'Female') {
                $user->image = 'Female.png'; // Replace with the actual path for the female default image
            } else {
                $user->image = 'Other.png';
            }
        }




        $user->save();

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
