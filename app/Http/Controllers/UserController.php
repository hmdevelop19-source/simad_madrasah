<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\EducationLevel;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        $users = User::with(['roles', 'educationLevel'])->latest()->get();
        return view('settings.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = Role::all();
        $educationLevels = EducationLevel::all();
        return view('settings.users.form', [
            'user' => new User(),
            'roles' => $roles,
            'educationLevels' => $educationLevels,
            'isEdit' => false
        ]);
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,name',
            'education_level_id' => 'nullable|exists:education_levels,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'education_level_id' => $request->education_level_id,
        ]);

        $user->syncRoles($request->roles);

        return redirect()->route('users.index')
            ->with('success', 'User ' . $user->name . ' berhasil didaftarkan.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $educationLevels = EducationLevel::all();
        return view('settings.users.form', [
            'user' => $user,
            'roles' => $roles,
            'educationLevels' => $educationLevels,
            'isEdit' => true
        ]);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,name',
            'education_level_id' => 'nullable|exists:education_levels,id',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'education_level_id' => $request->education_level_id,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        $user->syncRoles($request->roles);

        return redirect()->route('users.index')
            ->with('success', 'Data user ' . $user->name . ' berhasil diperbarui.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Akun ' . $userName . ' telah dihapus permanen.');
    }
}
