<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    // Profile View
    public function viewProfile()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user_id = Auth::id();
        $role_id = Auth::user()->role_id;

        // Get basic user info
        $user = DB::selectOne("SELECT * FROM users WHERE user_id = ?", [$user_id]);

        if (!$user) {
            abort(404, 'User not found');
        }

        // Get role-specific data
        $role_data = [];
        $role_name = '';

        switch ($role_id) {
            case 1: // Admin
                $role_data = DB::selectOne("SELECT * FROM admin WHERE user_id = ?", [$user_id]);
                $role_name = 'Admin';
                break;
            case 2: // Doctor
                $role_data = DB::selectOne("SELECT * FROM doctor WHERE user_id = ?", [$user_id]);
                $role_name = 'Doctor';
                break;
            case 3: // Volunteer
                $role_data = DB::selectOne("SELECT * FROM volunteer WHERE user_id = ?", [$user_id]);
                $role_name = 'Volunteer';
                break;
            case 4: // Adopter
                $role_data = DB::selectOne("SELECT * FROM adopter WHERE user_id = ?", [$user_id]);
                $role_name = 'Adopter';
                break;
            case 5: // Donor
                $role_data = DB::selectOne("SELECT * FROM donor WHERE user_id = ?", [$user_id]);
                $role_name = 'Donor';
                break;
            case 6: // Employee
                $role_data = DB::selectOne("SELECT * FROM employee WHERE user_id = ?", [$user_id]);
                $role_name = 'Employee';
                break;
            case 7: // Cleaner
                $role_data = DB::selectOne("SELECT * FROM cleaner WHERE user_id = ?", [$user_id]);
                $role_name = 'Cleaner';
                break;
            case 17: // Delivery
                $role_data = DB::selectOne("SELECT * FROM delivery WHERE user_id = ?", [$user_id]);
                $role_name = 'Delivery';
                break;
            default:
                $role_name = 'User';
        }

        return view('profile_view', [
            'user' => $user,
            'role_data' => $role_data,
            'role_name' => $role_name,
            'role_id' => $role_id
        ]);
    }

    // Edit Profile
    public function editProfile()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user_id = Auth::id();
        $role_id = Auth::user()->role_id;

        // Get basic user info
        $user = DB::selectOne("SELECT * FROM users WHERE user_id = ?", [$user_id]);

        if (!$user) {
            abort(404, 'User not found');
        }

        // Get role-specific data
        $role_data = [];
        $role_name = '';

        switch ($role_id) {
            case 1: // Admin
                $role_data = DB::selectOne("SELECT * FROM admin WHERE user_id = ?", [$user_id]);
                $role_name = 'Admin';
                break;
            case 2: // Doctor
                $role_data = DB::selectOne("SELECT * FROM doctor WHERE user_id = ?", [$user_id]);
                $role_name = 'Doctor';
                break;
            case 3: // Volunteer
                $role_data = DB::selectOne("SELECT * FROM volunteer WHERE user_id = ?", [$user_id]);
                $role_name = 'Volunteer';
                break;
            case 4: // Adopter
                $role_data = DB::selectOne("SELECT * FROM adopter WHERE user_id = ?", [$user_id]);
                $role_name = 'Adopter';
                break;
            case 5: // Donor
                $role_data = DB::selectOne("SELECT * FROM donor WHERE user_id = ?", [$user_id]);
                $role_name = 'Donor';
                break;
            case 6: // Employee
                $role_data = DB::selectOne("SELECT * FROM employee WHERE user_id = ?", [$user_id]);
                $role_name = 'Employee';
                break;
            case 7: // Cleaner
                $role_data = DB::selectOne("SELECT * FROM cleaner WHERE user_id = ?", [$user_id]);
                $role_name = 'Cleaner';
                break;
            case 17: // Delivery
                $role_data = DB::selectOne("SELECT * FROM delivery WHERE user_id = ?", [$user_id]);
                $role_name = 'Delivery';
                break;
            default:
                $role_name = 'User';
        }

        return view('profile_edit', [
            'user' => $user,
            'role_data' => $role_data,
            'role_name' => $role_name,
            'role_id' => $role_id
        ]);
    }

    // Update Profile
    public function updateProfile(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user_id = Auth::id();
        $role_id = Auth::user()->role_id;

        // Validate basic info
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update basic user info
        DB::update(
            "UPDATE users SET name = ?, email = ?, phone_number = ?, date_of_birth = ? WHERE user_id = ?",
            [
                $request->name,
                $request->email,
                $request->phone_number,
                $request->date_of_birth,
                $user_id
            ]
        );

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('storage/profile_images'), $imageName);
            $imagePath = 'profile_images/' . $imageName;

            DB::update(
                "UPDATE users SET image = ? WHERE user_id = ?",
                [$imagePath, $user_id]
            );
        }

        // Update role-specific data
        switch ($role_id) {
            case 2: // Doctor
                DB::update(
                    "UPDATE doctor SET specialization = ?, experience_year = ? WHERE user_id = ?",
                    [
                        $request->specialization ?? '',
                        $request->experience_year ?? 0,
                        $user_id
                    ]
                );
                break;
            case 3: // Volunteer
                DB::update(
                    "UPDATE volunteer SET responsibility = ? WHERE user_id = ?",
                    [
                        $request->responsibility ?? '',
                        $user_id
                    ]
                );
                break;
            case 6: // Employee
                DB::update(
                    "UPDATE employee SET responsibility = ?, type_of_work = ? WHERE user_id = ?",
                    [
                        $request->responsibility ?? '',
                        $request->type_of_work ?? '',
                        $user_id
                    ]
                );
                break;
        }

        return redirect()->route('profile_view')->with('success', 'Profile updated successfully');
    }

    // Change Password Form
    public function changePasswordForm()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        return view('change_password');
    }

    // Change Password
    public function changePassword(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|same:new_password',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('error', 'Current password is incorrect');
        }

        // Update password
        DB::update(
            "UPDATE users SET password = ? WHERE user_id = ?",
            [Hash::make($request->new_password), $user->user_id]
        );

        return redirect()->route('profile_view')->with('success', 'Password changed successfully');
    }
}