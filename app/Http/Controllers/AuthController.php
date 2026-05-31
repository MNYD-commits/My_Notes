<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Note;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    
    public function showRegister()
    {
        return view('register');
    }

    public function register(Request $request){

        // validate the request
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'gender' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        // create the user
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'gender' => $validated['gender'],
            'password' => Hash::make($validated['password']),
        ]);

        // redirect to login page with success message
        return redirect('/login')->with('success', 'Registration successful!');
    }

    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request){

        // validate the request
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        
        // find user by email
        $user = User::where('email', $validated['email'])->first();

        // check if user exists
        if (!$user) {
            return back()->with('error', 'User does not exist!');
        }

        // check if password is correct
        if (!Hash::check($validated['password'], $user->password)) {
            return back()->with('error', 'Password is incorrect!');
        }

        // start login session
        session(['user' => $user]);

        // login the user
        auth()->login($user);
        return redirect('/dashboard')->with('success', 'Login successful!');
    }

    public function showDashboard()
    {
        $user = auth()->user();
        $totalUsers = User::count();
        $totalNotes = Note::count();

        $period = collect(range(5, 0, -1))->map(function ($offset) {
            return now()->subMonths($offset);
        });

        $chartLabels = $period->map(fn ($date) => $date->format('M'))->toArray();
        $chartKeys = $period->map(fn ($date) => $date->format('Y-m'))->toArray();

        $usersByMonth = User::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total")
            ->whereBetween('created_at', [now()->subMonths(5)->startOfMonth(), now()->endOfMonth()])
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $notesByMonth = Note::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total")
            ->whereBetween('created_at', [now()->subMonths(5)->startOfMonth(), now()->endOfMonth()])
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $userChartData = collect($chartKeys)->map(fn ($month) => $usersByMonth[$month] ?? 0)->toArray();
        $noteChartData = collect($chartKeys)->map(fn ($month) => $notesByMonth[$month] ?? 0)->toArray();

        return view('dashboard', [
            'user' => $user,
            'totalUsers' => $totalUsers,
            'totalNotes' => $totalNotes,
            'chartLabels' => $chartLabels,
            'userChartData' => $userChartData,
            'noteChartData' => $noteChartData,
        ]);
    }

    public function showNotes()
    {
        $user = auth()->user();
        $notes = Note::with('user')->where('user_id', $user->id)->latest()->get();
        return view('notes', ['user' => $user, 'notes' => $notes]);
    }

    public function showUser()
    {
        $user = auth()->user();
        $users = User::all();
        return view('user', ['user' => $user, 'users' => $users]);
    }

    public function showProfile()
    {
        $user = auth()->user();
        return view('profile', ['user' => $user]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|string|in:male,female',
            'current_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:6|confirmed',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Check current password if changing password
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
            $user->password = Hash::make($validated['new_password']);
        }

        // Handle profile image — store as base64 for persistence across deployments
        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $mime = $file->getMimeType();
            $base64 = base64_encode(file_get_contents($file->getRealPath()));
            $user->profile_picture_base64 = 'data:' . $mime . ';base64,' . $base64;
            // Keep file path for backward compatibility
            $user->profile_photo_path = $file->store('profile_images', 'public');
        }

        $user->name = $validated['name'];
        $user->gender = $validated['gender'];
        $user->save();

        return back()->with('success', 'Profile updated successfully!');
    }

    public function logout()
    {
        auth()->logout();
        session()->flush();
        session()->regenerate();
        return redirect('/login')->with('success', 'Logged out successfully!');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('edit-user', ['user' => $user]);
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $id,
            'gender' => 'required|string',
        ]);

        $user->update($validated);
        return back()->with('success', 'User updated successfully!');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return back()->with('success', 'User deleted successfully!');
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'gender' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'gender' => $validated['gender'],
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'User added successfully!');
    }

    public function storeNote(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $user = auth()->user();

        Note::create([
            'user_id' => $user->id,
            'title' => $validated['title'],
            'content' => $validated['content'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Note added successfully!'
        ]);
    }

    public function getNote($id)
    {
        $note = Note::findOrFail($id);
        
        // Check if the note belongs to the authenticated user
        if ($note->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($note);
    }

    public function updateNote(Request $request, $id)
    {
        $note = Note::findOrFail($id);

        // Check if the note belongs to the authenticated user
        if ($note->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $note->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Note updated successfully!'
        ]);
    }

    public function deleteNote($id)
    {
        $note = Note::findOrFail($id);

        // Check if the note belongs to the authenticated user
        if ($note->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $note->delete();

        return response()->json([
            'success' => true,
            'message' => 'Note deleted successfully!'
        ]);
    }   
}
