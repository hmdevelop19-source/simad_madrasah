@extends('layouts.app')
@section('title', 'Manajemen Role')
@section('page-title', 'Otoritas & Jabatan')

@section('content')
<style>
    .role-list-container { background: white; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); overflow: hidden; }
    .role-table { width: 100%; border-collapse: collapse; }
    .role-table th { background: #fff; padding: 1.25rem 2rem; color: #8a99af; font-weight: 800; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1.2px; border-bottom: 1.5px solid #edf2f7; text-align: left; }
    .role-table td { padding: 1.5rem 2rem; border-bottom: 1px solid #f1f5f9; color: #4b5563; font-size: 0.9rem; vertical-align: middle; }
    
    .role-name { font-weight: 800; color: #1e293b; font-size: 1rem; text-transform: uppercase; letter-spacing: 0.5px; line-height: 1.1; margin-bottom: 2px; }
    .perm-badge { background: #eff6ff; color: #3b82f6; padding: 6px 14px; border-radius: 8px; font-size: 0.7rem; font-weight: 800; border: 1px solid #dbeafe; }
    
    .action-group { display: flex; align-items: center; justify-content: flex-end; gap: 8px; }
    .btn-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; border: 1.5px solid #e2e8f0; background: white; color: #64748b; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); cursor: pointer; text-decoration: none; }
    .btn-icon:hover { background: #f8fafc; color: var(--primary); border-color: var(--primary); transform: translateY(-1px); }
    .btn-icon.delete:hover { background: #fef2f2; color: #ef4444; border-color: #fecaca; }
    .btn-icon i { font-size: 0.9rem; }

    .empty-state { padding: 5rem; text-align: center; color: #94a3b8; }
</style>

<div class="row justify-content-center">
    <div class="col-12">
        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-5 px-2">
            <div>
                <h4 style="font-weight: 950; color: var(--primary); margin: 0; letter-spacing: -1px;">Daftar Peran & Otoritas</h4>
                <p style="color: #64748b; margin: 0; font-size: 0.85rem; font-weight: 600;">Kelola batasan fitur untuk setiap jajaran staff Madrasah.</p>
            </div>
            <a href="{{ route('roles.create') }}" class="btn btn-primary" style="padding: 12px 28px; border-radius: 12px; font-weight: 900; background: var(--primary); border: none; box-shadow: 0 8px 20px rgba(0,0,82,0.2); letter-spacing: 0.5px;">
                <i class="fa-solid fa-plus-circle me-2"></i> TAMBAH ROLE BARU
            </a>
        </div>

        <div class="role-list-container">
            <table class="role-table">
                <thead>
                    <tr>
                        <th style="width: 80px;">#</th>
                        <th>NAMA PERAN / JABATAN</th>
                        <th>TOTAL IZIN AKSES</th>
                        <th>STATUS KEAMANAN</th>
                        <th style="text-align: right;">AKSI OPERASI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $index => $role)
                    <tr>
                        <td style="color: #cbd5e1; font-weight: 800; font-family: 'Monaco', monospace; font-size: 0.8rem;">
                            {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                        </td>
                        <td>
                            <div class="role-name">{{ str_replace('_', ' ', $role->name) }}</div>
                            <span style="font-size: 0.725rem; color: #94a3b8; font-weight: 600; letter-spacing: 0.5px;">SYSTEM ID: #0{{ $role->id }}</span>
                        </td>
                        <td>
                            <span class="perm-badge">
                                <i class="fa-solid fa-key me-1" style="font-size: 0.6rem; opacity: 0.5;"></i>
                                {{ $role->permissions_count }} AKTIF
                            </span>
                        </td>
                        <td>
                            @if($role->name === 'super_admin')
                                <span style="color: #059669; font-weight: 800; font-size: 0.75rem; letter-spacing: 0.5px;">
                                    <i class="fa-solid fa-shield-check me-1"></i> PROTECTED SYSTEM
                                </span>
                            @else
                                <span style="color: #64748b; font-weight: 800; font-size: 0.75rem; letter-spacing: 0.5px;">
                                    <i class="fa-solid fa-fingerprint me-1"></i> CUSTOMIZABLE
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="action-group">
                                <a href="{{ route('roles.edit', $role->id) }}" class="btn-icon" title="Edit Hak Akses">
                                    <i class="fa-solid fa-user-gear"></i>
                                </a>
                                @if($role->name !== 'super_admin')
                                <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus role ini? User terkait akan kehilangan akses.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-icon delete" title="Hapus Role">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="empty-state">
                            <i class="fa-solid fa-user-lock fa-3x mb-3" style="opacity: 0.15;"></i>
                            <p class="mb-0 fw-bold">Belum ada role yang terdaftar di basis data.</p>
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
    <div style="background: #22c55e; color: white; padding: 1rem 2rem; border-radius: 12px; box-shadow: 0 10px 30px rgba(34, 197, 94, 0.3); font-weight: 800; display: flex; align-items: center; gap: 15px;">
        <i class="fa-solid fa-circle-check"></i>
        {{ session('success') }}
    </div>
</div>
<style> @keyframes slideUp { from { transform: translateY(100%); opacity: 0; } to { transform: translateY(0); opacity: 1; } } </style>
@endif
@endsection
