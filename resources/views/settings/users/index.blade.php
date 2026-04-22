@extends('layouts.app')
@section('title', 'Manajemen User')
@section('page-title', 'Otoritas & Akun')

@section('content')
<style>
    .user-card { background: white; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); overflow: hidden; }
    .user-table { width: 100%; border-collapse: collapse; }
    .user-table th { background: #fff; padding: 1.25rem 2rem; color: #8a99af; font-weight: 800; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1.2px; border-bottom: 1.5px solid #edf2f7; text-align: left; }
    .user-table td { padding: 1.25rem 2rem; border-bottom: 1px solid #f1f5f9; color: #4b5563; font-size: 0.875rem; vertical-align: middle; }
    
    .avatar-circle { width: 42px; height: 42px; border-radius: 12px; background: #EEF2FF; color: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 0.9rem; border: 1.5px solid #E0E7FF; flex-shrink: 0; }
    .user-info { display: flex; align-items: center; gap: 1rem; }
    .user-name { font-weight: 800; color: #1e293b; font-size: 0.95rem; margin-bottom: 2px; }
    .user-email { font-size: 0.75rem; color: #94a3b8; font-weight: 500; }
    
    .role-badge { display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 6px; font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; border-width: 1px; border-style: solid; }
    .role-super { background: #fef2f2; color: #ef4444; border-color: #fecaca; }
    .role-staff { background: #eff6ff; color: #3b82f6; border-color: #dbeafe; }
    .role-guru { background: #f0fdf4; color: #22c55e; border-color: #dcfce7; }
    
    .unit-tag { background: #f8fafc; color: #64748b; padding: 4px 10px; border-radius: 6px; font-size: 0.7rem; font-weight: 700; border: 1px solid #e2e8f0; }

    .action-group { display: flex; gap: 8px; justify-content: flex-end; }
    .btn-action { width: 34px; height: 34px; border-radius: 10px; display: flex; align-items: center; justify-content: center; transition: all 0.2s; border: 1.5px solid #e2e8f0; background: white; color: #64748b; }
    .btn-action:hover { background: var(--primary); color: white; border-color: var(--primary); transform: translateY(-2px); }
    .btn-action.delete:hover { background: #ef4444; border-color: #ef4444; }
</style>

<div class="row justify-content-center">
    <div class="col-12">
        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-5 px-2">
            <div>
                <h4 style="font-weight: 950; color: var(--primary); margin: 0; letter-spacing: -1px;">Daftar Pengelola Sistem</h4>
                <p style="color: #64748b; margin: 0; font-size: 0.85rem; font-weight: 600;">Lihat dan atur siapa saja yang memiliki akses ke dashboard SIMAD.</p>
            </div>
            <a href="{{ route('users.create') }}" class="btn btn-primary" style="padding: 12px 28px; border-radius: 12px; font-weight: 900; background: var(--primary); border: none; box-shadow: 0 8px 20px rgba(0,0,82,0.15);">
                <i class="fa-solid fa-user-plus me-2"></i> TAMBAH USER BARU
            </a>
        </div>

        <div class="user-card">
            <table class="user-table">
                <thead>
                    <tr>
                        <th style="width: 320px;">IDENTITAS PENGGUNA</th>
                        <th>JABATAN / ROLE</th>
                        <th>UNIT TUGAS (SCOPE)</th>
                        <th>TERDAFTAR PADA</th>
                        <th style="text-align: right;">OPERASI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="user-info">
                                <div class="avatar-circle">
                                    {{ strtoupper(substr($user->name, 0, 1) . substr(strrchr($user->name, " "), 1, 1)) ?: strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="user-name">{{ $user->name }}</div>
                                    <div class="user-email">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($user->roles as $role)
                                    <span class="role-badge {{ $role->name === 'super_admin' ? 'role-super' : ($role->name === 'guru' ? 'role-guru' : 'role-staff') }}">
                                        {{ str_replace('_', ' ', $role->name) }}
                                    </span>
                                @endforeach
                            </div>
                        </td>
                        <td>
                            @if($user->educationLevel)
                                <span class="unit-tag">
                                    <i class="fa-solid fa-building-columns me-1" style="opacity: 0.5;"></i>
                                    {{ $user->educationLevel->nama }}
                                </span>
                            @else
                                <span style="font-size: 0.75rem; color: #94a3b8; font-weight: 700; letter-spacing: 0.5px;">SEMUA UNIT (GLOBAL)</span>
                            @endif
                        </td>
                        <td style="color: #94a3b8; font-weight: 600; font-size: 0.75rem;">
                            {{ $user->created_at->format('d M Y') }}
                        </td>
                        <td>
                            <div class="action-group">
                                <a href="{{ route('users.edit', $user->id) }}" class="btn-action" title="Edit Profil & Akses">
                                    <i class="fa-solid fa-user-pen"></i>
                                </a>
                                @if(auth()->id() !== $user->id)
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus user ini selamanya?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-action delete" title="Hapus Akun">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="padding: 5rem; text-align: center;">
                           <div style="opacity: 0.2;">
                               <i class="fa-solid fa-users-slash fa-4x mb-3"></i>
                           </div>
                           <p style="font-weight: 800; color: #94a3b8;">Belum ada user pengelola yang terdaftar.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if(session('success'))
<div style="position: fixed; bottom: 30px; right: 30px; z-index: 1000; animation: slideUp 0.5s ease;">
    <div style="background: #22c55e; color: white; padding: 1.25rem 2.5rem; border-radius: 16px; box-shadow: 0 10px 30px rgba(34, 197, 94, 0.3); font-weight: 950; display: flex; align-items: center; gap: 15px;">
        <i class="fa-solid fa-circle-check" style="font-size: 1.5rem;"></i>
        {{ session('success') }}
    </div>
</div>
@endif
@if(session('error'))
<div style="position: fixed; bottom: 30px; right: 30px; z-index: 1000; animation: slideUp 0.5s ease;">
    <div style="background: #ef4444; color: white; padding: 1.25rem 2.5rem; border-radius: 16px; box-shadow: 0 10px 30px rgba(239, 68, 68, 0.3); font-weight: 950; display: flex; align-items: center; gap: 15px;">
        <i class="fa-solid fa-triangle-exclamation" style="font-size: 1.5rem;"></i>
        {{ session('error') }}
    </div>
</div>
@endif

<style> @keyframes slideUp { from { transform: translateY(100%); opacity: 0; } to { transform: translateY(0); opacity: 1; } } </style>
@endsection
