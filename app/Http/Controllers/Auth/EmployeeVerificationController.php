<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class EmployeeVerificationController extends Controller
{
    public function verify(Request $request, $id, $hash)
{
    $user = User::findOrFail($id);

    // Verify the hash matches the user's email
    if (sha1($user->email) !== $hash) {
        return redirect()->route('login')->withErrors('Invalid verification link.');
    }

    // Redirect to reset credentials interface
    return redirect()->route('emails.reset_credentials', ['id' => $user->id, 'hash' => $hash]);
}
public function resetCredentials(Request $request, $id, $hash)
{
    $user = User::findOrFail($id);

    // Verify the hash matches the user's email
    if (sha1($user->email) !== $hash) {
        return redirect()->route('login')->withErrors('Invalid or expired verification link.');
    }

    // Show the reset credentials page
    return view('emails.reset_credentials', ['user' => $user]);
}

public function updateCredentials(Request $request, $id)
{
    // Find the user by ID
    $user = User::findOrFail($id);

    // Validate inputs
    $validated = $request->validate([
        'username' => 'required|string|max:255|unique:users,username,' . $user->id,
        'password' => 'required|string|min:8|confirmed',
    ]);

    // Debugging output to verify the data being passed
    \Log::info('Updating credentials for user', [
        'user_id' => $user->id,
        'username' => $request->username,
        'password' => $request->password,
    ]);

    // Attempt to update user credentials
    try {
        $user->update([
            'username' => $request->username,
            'password' => Hash::make($request->password), // Hash the password before saving
            'status' => 'member', // Update status to 'member'
        ]);

        // Log success
        \Log::info('User credentials updated successfully', ['user_id' => $user->id]);

        // Return a success message
        return redirect()->route('login')->with('success', 'Your credentials have been reset. Please log in with the new credentials.');
    } catch (\Exception $e) {
        // Log any errors during the update
        \Log::error('Error updating user credentials', ['error' => $e->getMessage()]);

        return redirect()->back()->with('error', 'Failed to update credentials. Please try again.');
    }
}




}
