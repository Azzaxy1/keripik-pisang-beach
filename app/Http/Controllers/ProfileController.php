<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\UserAddress;
use App\Models\User;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the user profile page
     */
    public function index()
    {
        $user = Auth::user();
        $addresses = $user->addresses()->get();
        
        return view('frontend.profile.index', compact('user', 'addresses'));
    }

    /**
     * Update user profile information
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return redirect()->route('profile.index')->with('success', 'Profile updated successfully!');
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.index')->with('success', 'Password updated successfully!');
    }

    /**
     * Store a new address
     */
    public function storeAddress(Request $request)
    {
        $request->validate([
            'type' => 'required|in:home,office,other',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'country' => 'required|string|max:255',
        ]);

        // Set all addresses as non-default first if this is being set as default
        if ($request->is_default) {
            Auth::user()->addresses()->update(['is_default' => false]);
        }

        Auth::user()->addresses()->create($request->all());

        return redirect()->route('profile.index')->with('success', 'Address added successfully!');
    }

    /**
     * Update an address
     */
    public function updateAddress(Request $request, UserAddress $address)
    {
        // Check if address belongs to authenticated user
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'type' => 'required|in:home,office,other',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'country' => 'required|string|max:255',
        ]);

        // Set all addresses as non-default first if this is being set as default
        if ($request->is_default) {
            Auth::user()->addresses()->update(['is_default' => false]);
        }

        $address->update($request->all());

        return redirect()->route('profile.index')->with('success', 'Address updated successfully!');
    }

    /**
     * Delete an address
     */
    public function deleteAddress(UserAddress $address)
    {
        // Check if address belongs to authenticated user
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $address->delete();

        return redirect()->route('profile.index')->with('success', 'Address deleted successfully!');
    }
}
