<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class TeacherController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:view-guru', only: ['index', 'show']),
            new Middleware('can:create-guru', only: ['create', 'store']),
            new Middleware('can:edit-guru', only: ['edit', 'update', 'toggleActive']),
            new Middleware('can:delete-guru', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $search = $request->get('search');

        $teachers = Teacher::with('user')
            ->when($search, fn($q) => $q->where('nama_lengkap', 'like', "%{$search}%")
                                        ->orWhere('nip', 'like', "%{$search}%"))
            ->orderByDesc('is_active')
            ->orderBy('nama_lengkap')
            ->paginate(20);

        return view('teachers.index', compact('teachers', 'search'));
    }

    public function create()
    {
        $roles = \Spatie\Permission\Models\Role::whereIn('name', ['guru', 'wali_kelas', 'kepala_sekolah'])->get();
        $educationLevels = \App\Models\EducationLevel::all();
        return view('teachers.create', compact('roles', 'educationLevels'));
    }

    public function store(Request $request)
    {
        $rules = [
            'nip'          => 'nullable|string|max:20|unique:teachers,nip',
            'nama_lengkap' => 'required|string|max:100',
            'email'        => 'nullable|email|unique:teachers,email|unique:users,email',
            'alamat'       => 'nullable|string',
            'no_hp'        => 'nullable|string|max:20',
            'is_active'    => 'boolean',
        ];

        // Jika user memilih untuk membuat akun login sekaligus
        if ($request->filled('email')) {
            $rules['email']    = 'required|email|unique:users,email';
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $validated = $request->validate($rules);

        DB::transaction(function () use ($validated, $request) {
            $userId = null;

            // Buat akun User jika email diisi
            if ($request->filled('email')) {
                $user = User::create([
                    'name'               => $validated['nama_lengkap'],
                    'email'              => $validated['email'],
                    'password'           => Hash::make($request->password ?? $validated['nip'] ?? 'password123'),
                    'education_level_id' => $request->education_level_id,
                ]);
                
                $user->syncRoles($request->roles ?? ['guru']);
                $userId = $user->id;
            }

            Teacher::create([
                'user_id'            => $userId,
                'education_level_id' => $request->education_level_id,
                'nip'                => $validated['nip'] ?? null,
                'nama_lengkap'       => $validated['nama_lengkap'],
                'email'              => $validated['email'] ?? null,
                'alamat'             => $validated['alamat'] ?? null,
                'no_hp'              => $validated['no_hp'] ?? null,
                'is_active'          => $request->boolean('is_active', true),
            ]);
        });

        return redirect()->route('teachers.index')
            ->with('success', "Data Guru '{$validated['nama_lengkap']}' berhasil ditambahkan.");
    }

    public function edit(Teacher $teacher)
    {
        $teacher->load('user.roles');
        $roles = \Spatie\Permission\Models\Role::whereIn('name', ['guru', 'wali_kelas', 'kepala_sekolah'])->get();
        $educationLevels = \App\Models\EducationLevel::all();
        return view('teachers.edit', compact('teacher', 'roles', 'educationLevels'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'nip'          => "nullable|string|max:20|unique:teachers,nip,{$teacher->id}",
            'nama_lengkap' => 'required|string|max:100',
            'email'        => "nullable|email|unique:teachers,email,{$teacher->id}|unique:users,email," . ($teacher->user_id ?? 'NULL'),
            'alamat'       => 'nullable|string',
            'no_hp'        => 'nullable|string|max:20',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $teacher->update($validated);

        // Sync User if exists
        if ($teacher->user) {
            $teacher->user->update([
                'name'               => $validated['nama_lengkap'],
                'email'              => $request->email ?? $teacher->user->email,
                'education_level_id' => $request->education_level_id,
            ]);

            if ($request->has('roles')) {
                $teacher->user->syncRoles($request->roles);
            }
        }
    }

    /** Toggle status aktif/nonaktif guru */
    public function toggleActive(Teacher $teacher)
    {
        $teacher->update(['is_active' => !$teacher->is_active]);
        $status = $teacher->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Guru '{$teacher->nama_lengkap}' berhasil {$status}.");
    }

    public function destroy(Teacher $teacher)
    {
        if ($teacher->assignments()->exists()) {
            return back()->with('error', "Tidak dapat menghapus: guru ini memiliki riwayat penugasan.");
        }

        $nama = $teacher->nama_lengkap;
        $teacher->delete(); // SoftDelete

        return redirect()->route('teachers.index')
            ->with('success', "Data Guru '{$nama}' berhasil dihapus.");
    }
}
