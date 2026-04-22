@extends('layouts.app')
@section('title', 'Hak Akses Sistem')
@section('page-title', 'Pusat Otoritas')

@section('content')
<style>
    .perm-container { background: white; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); overflow: hidden; }
    .perm-table { width: 100%; border-collapse: collapse; }
    .perm-table th { background: #fff; padding: 1.25rem 2rem; color: #8a99af; font-weight: 800; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1.2px; border-bottom: 1.5px solid #edf2f7; text-align: left; }
    .perm-table td { padding: 1.25rem 2rem; border-bottom: 1px solid #f1f5f9; color: #4b5563; font-size: 0.85rem; vertical-align: middle; }
    
    .perm-slug { font-family: 'Monaco', 'Consolas', monospace; font-weight: 700; color: #1e3a8a; background: #eff6ff; padding: 4px 12px; border-radius: 8px; border: 1px solid #dbeafe; font-size: 0.8rem; letter-spacing: 0.5px; }
    .group-header { background: #fafbfc !important; font-weight: 900; color: var(--primary); text-transform: uppercase; font-size: 0.75rem; letter-spacing: 1.5px; padding: 1rem 2rem !important; }
    
    .btn-delete { width: 34px; height: 34px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s; border: 1.5px solid #fee2e2; background: #fff; color: #ef4444; cursor: pointer; }
    .btn-delete:hover { background: #ef4444; color: white; border-color: #ef4444; transform: scale(1.05); }

    .quick-add-box { background: white; border-radius: 16px; padding: 2rem; margin-bottom: 2rem; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 2.5rem; box-shadow: var(--shadow-sm); }
</style>

<div class="row justify-content-center">
    <div class="col-12">
        {{-- QUICK ADD FORM --}}
        <div class="quick-add-box mb-5">
            <div style="flex: 1;">
                <h5 style="font-weight: 950; color: var(--primary); margin: 0; letter-spacing: -0.5px;">Registrasi Hak Akses</h5>
                <p style="color: #64748b; margin: 0; font-size: 0.825rem; font-weight: 600;">Definisikan *slug* izin baru untuk memperluas kapabilitas sistem.</p>
            </div>
            <form action="{{ route('permissions.store') }}" method="POST" class="d-flex gap-2" style="flex: 1.2;">
                @csrf
                <input type="text" name="name" class="form-control" placeholder="Contoh: approve-transaksi-keuangan" style="border-radius: 12px; font-weight: 700; border: 1.5px solid #e2e8f0; padding: 12px 20px;" required>
                <button type="submit" class="btn btn-primary" style="padding: 12px 30px; border-radius: 12px; font-weight: 900; background: var(--primary); border: none; box-shadow: 0 5px 15px rgba(0,0,82,0.15);">
                    <i class="fa-solid fa-plus me-2"></i> DAFTARKAN
                </button>
            </form>
        </div>

        <div class="perm-container">
            <table class="perm-table">
                <thead>
                    <tr>
                        <th style="width: 250px;">KATEGORI AKSI</th>
                        <th>SLUG IDENTIFIKASI SISTEM (PERMISSION)</th>
                        <th>DIPERBARUI PADA</th>
                        <th style="text-align: right;">OPSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($permissionsByGroup as $group => $perms)
                    <tr>
                        <td colspan="4" class="group-header">
                            <i class="fa-solid fa-folder-tree me-2 text-warning"></i> MODUL: {{ strtoupper($group) }}
                        </td>
                    </tr>
                    @foreach($perms as $permission)
                    <tr>
                        <td style="padding-left: 4.5rem; font-weight: 800; color: #94a3b8; font-size: 0.75rem; letter-spacing: 0.5px;">
                            {{ strtoupper($group) }}
                        </td>
                        <td>
                            <span class="perm-slug">{{ $permission->name }}</span>
                        </td>
                        <td style="color: #94a3b8; font-size: 0.75rem; font-weight: 600;">
                            <i class="fa-regular fa-clock me-1"></i> {{ $permission->updated_at->diffForHumans() }}
                        </td>
                        <td style="text-align: right;">
                            <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST" onsubmit="return confirm('Hapus izin ini dari sistem? Ini dapat mempengaruhi akses staff.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-delete" title="Hapus Izin">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@if(session('success'))
<div style="position: fixed; bottom: 30px; right: 30px; z-index: 1000; animation: slideUp 0.5s ease;">
    <div style="background: #22c55e; color: white; padding: 1.25rem 2.5rem; border-radius: 16px; box-shadow: 0 10px 30px rgba(34, 197, 94, 0.3); font-weight: 950; display: flex; align-items: center; gap: 15px; letter-spacing: 0.5px;">
        <i class="fa-solid fa-circle-check" style="font-size: 1.5rem;"></i>
        {{ session('success') }}
    </div>
</div>
<style> @keyframes slideUp { from { transform: translateY(100%); opacity: 0; } to { transform: translateY(0); opacity: 1; } } </style>
@endif
@endsection
