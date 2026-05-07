<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user()->fresh();
        return view('profile.settings', ['user' => $user]);
    }

    public function updateInfo(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'       => 'required|string|max:255',
            'username'   => 'nullable|string|max:255|unique:users,username,' . $user->id,
            'email'      => 'required|email|unique:users,email,' . $user->id,
            'phone'      => 'nullable|string|max:20',
            'department' => 'nullable|string|max:255',
        ]);

        DB::table('users')->where('id', $user->id)->update([
            'name'       => $request->name,
            'username'   => $request->username,
            'email'      => $request->email,
            'phone'      => $request->phone,
            'department' => $request->department,
            'updated_at' => now(),
        ]);

        return redirect()->route('profile.settings')->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->route('profile.settings')
                ->with('error', 'Current password is incorrect!');
        }

        DB::table('users')->where('id', $user->id)->update([
            'password'   => Hash::make($request->password),
            'updated_at' => now(),
        ]);

        return redirect()->route('profile.settings')->with('success', 'Password updated successfully!');
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = auth()->user();

        // Delete old photo
        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        // Store new photo
        $path = $request->file('profile_photo')->store('profile-photos', 'public');

        // Force update using DB query builder
        DB::table('users')->where('id', $user->id)->update([
            'profile_photo' => $path,
            'updated_at'    => now(),
        ]);

        return redirect()->route('profile.settings')->with('success', 'Photo uploaded successfully! Path: ' . $path);
    }

    public function deletePhoto()
    {
        $user = auth()->user();

        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);

            DB::table('users')->where('id', $user->id)->update([
                'profile_photo' => null,
                'updated_at'    => now(),
            ]);
        }

        return redirect()->route('profile.settings')->with('success', 'Profile photo removed!');
    }
}