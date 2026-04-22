<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::withCount('permissions')->get();
        return view('settings.roles', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::all();
        $matrix = $this->prepareMatrix();
        
        return view('settings.role-form', [
            'role' => new Role(),
            'permissions' => $permissions,
            'matrix' => $matrix,
            'isEdit' => false
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'array'
        ]);

        DB::transaction(function () use ($request) {
            $role = Role::create(['name' => $request->name]);
            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            }
        });

        return redirect()->route('roles.index')->with('success', 'Role berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $permissions = Permission::all();
        $matrix = $this->prepareMatrix();
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('settings.role-form', [
            'role' => $role,
            'permissions' => $permissions,
            'matrix' => $matrix,
            'rolePermissions' => $rolePermissions,
            'isEdit' => true
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        // Prevent editing super_admin name for safety
        if ($role->name === 'super_admin') {
            $request->merge(['name' => 'super_admin']);
        }

        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'array'
        ]);

        DB::transaction(function () use ($request, $role) {
            $role->update(['name' => $request->name]);
            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            }
        });

        return redirect()->route('roles.index')->with('success', 'Role berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        if ($role->name === 'super_admin') {
            return redirect()->back()->with('error', 'Role Super Admin tidak dapat dihapus.');
        }

        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role berhasil dihapus.');
    }

    /**
     * Prepare the permission matrix structure based on sidebar modules.
     */
    private function prepareMatrix()
    {
        return [
            'Dashboard' => [
                'group' => false,
                'modules' => [
                    'Pusat Informasi' => 'dashboard',
                ]
            ],
            'Master Utama' => [
                'group' => true,
                'modules' => [
                    'Unit Pendidikan' => 'unit',
                    'Data Master Guru' => 'guru',
                    'Data Master Santri' => 'santri',
                    'Data Wali Santri' => 'wali',
                    'Tingkat Kelas' => 'tingkat',
                    'Mata Pelajaran' => 'mapel',
                    'Rombel Kelas' => 'rombel',
                ]
            ],
            'Akademik' => [
                'group' => true,
                'modules' => [
                    'Tahun Ajaran' => 'tahun',
                    'Manajemen Kuartal' => 'kuartal',
                    'Katalog Kurikulum' => 'katalog',
                    'Distribusi Kelas' => 'distribusi',
                    'Wizard Rollover' => 'rollover',
                ]
            ],
            'Evaluasi & Nilai' => [
                'group' => true,
                'modules' => [
                    'Presensi Santri' => 'presensi',
                    'Input Nilai Mapel' => 'nilai',
                    'Nilai Kepribadian' => 'kepribadian',
                    'Cetak E-Raport' => 'raport',
                ]
            ],
            'Sistem & Keamanan' => [
                'group' => true,
                'modules' => [
                    'Hak Akses' => 'hak-akses',
                    'Manajemen Role' => 'role',
                    'Manajemen User' => 'user',
                    'Profil Induk' => 'profil-induk',
                    'Profil Aplikasi' => 'profil-app',
                ]
            ]
        ];
    }
}
