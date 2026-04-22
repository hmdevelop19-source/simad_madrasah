@extends('layouts.app')
@section('title', 'Data Guru')
@section('page-title', 'Data Guru')

@section('content')

{{-- ================================================================
     SUMMARY STATS STRIP
================================================================ --}}
<div style="background: white; border: 1px solid var(--border); border-radius: 20px; padding: 1.25rem 2rem; margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between; box-shadow: var(--shadow-sm);">
    <div style="display: flex; gap: 3rem;">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="width: 45px; height: 45px; background: var(--bg-secondary); color: var(--primary); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div>
                <div style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">Total Guru</div>
                <div style="font-size: 1.15rem; font-weight: 800; color: var(--primary);">{{ $teachers->total() }} Personel</div>
            </div>
        </div>
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="width: 45px; height: 45px; background: #ECFDF5; color: #059669; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
            <div>
                <div style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">Guru Aktif</div>
                <div style="font-size: 1.15rem; font-weight: 800; color: #059669;">{{ $teachers->where('is_active', true)->count() }} Aktif</div>
            </div>
        </div>
    </div>
    
    @can('create-guru')
    <a href="{{ route('teachers.create') }}" class="btn btn-primary" style="height: 45px; border-radius: 30px; padding: 0 1.5rem; display: flex; align-items: center; gap: 0.5rem; font-weight: 800; box-shadow: var(--shadow-lg);">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
        TAMBAH GURU
    </a>
    @endcan
</div>

{{-- ================================================================
     SEARCH & TABLE
================================================================ --}}
<div class="card" style="border-radius: 20px; border: 1px solid var(--border); box-shadow: var(--shadow-sm); overflow: hidden;">
    <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border); background: #F9FAFB;">
        <form method="GET" style="display: flex; gap: 0.75rem;">
            <div style="position: relative; width: 100%; max-width: 400px;">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau NIP guru..." class="form-control" style="padding-left: 2.75rem; border-radius: 30px; height: 42px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            </div>
            <button type="submit" class="btn btn-outline" style="border-radius: 30px; height: 42px; font-weight: 700;">FILTER</button>
            @if($search)
                <a href="{{ route('teachers.index') }}" class="btn btn-outline" style="border-radius: 30px; height: 42px; display: flex; align-items: center;">✕ RESET</a>
            @endif
        </form>
    </div>

    @if(session('success'))
        <div style="margin: 1.25rem 1.5rem 0 1.5rem; padding: 0.75rem 1.25rem; background: #ECFDF5; border: 1px solid #10B981; border-radius: 12px; color: #065F46; font-size: 0.85rem; font-weight: 600;">
            ✅ {{ session('success') }}
        </div>
    @endif

    <div class="table-wrap">
        <table style="width: 100%; border-collapse: separate; border-spacing: 0;">
            <thead style="background: white;">
                <tr>
                    <th style="padding: 1rem 1.5rem; font-size: 0.7rem; text-transform: uppercase; font-weight: 800; color: var(--text-muted); text-align: center; width: 60px;">#</th>
                    <th style="padding: 1rem 1.5rem; font-size: 0.7rem; text-transform: uppercase; font-weight: 800; color: var(--text-muted);">Informasi Personel</th>
                    <th style="padding: 1rem 1.5rem; font-size: 0.7rem; text-transform: uppercase; font-weight: 800; color: var(--text-muted);">Kontak & Email</th>
                    <th style="padding: 1rem 1.5rem; font-size: 0.7rem; text-transform: uppercase; font-weight: 800; color: var(--text-muted); text-align: center;">Status Akun</th>
                    <th style="padding: 1rem 1.5rem; font-size: 0.7rem; text-transform: uppercase; font-weight: 800; color: var(--text-muted); text-align: center; width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody style="background: white;">
                @forelse($teachers as $teacher)
                <tr style="border-top: 1px solid var(--border); transition: all 0.2s;">
                    <td style="padding: 1.25rem 1.5rem; text-align: center; color: var(--text-muted); font-size: 0.8rem; font-weight: 600;">
                        {{ $teachers->firstItem() + $loop->index }}
                    </td>
                    <td style="padding: 1.25rem 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="width: 42px; height: 42px; background: var(--bg-secondary); border: 2px solid white; box-shadow: 0 0 0 1px var(--border); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: 900; color: var(--primary); font-size: 0.9rem;">
                                {{ strtoupper(substr($teacher->nama_lengkap, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight: 700; color: var(--primary); font-size: 0.9rem; line-height: 1.2;">{{ $teacher->nama_lengkap }}</div>
                                <div style="font-size: 0.7rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 4px;">{{ $teacher->nip ?? 'NIP: –' }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 1.25rem 1.5rem;">
                        <div style="font-weight: 700; color: #475569; font-size: 0.85rem;">{{ $teacher->no_hp ?: '-' }}</div>
                        <div style="font-size: 0.75rem; color: #94A3B8; font-weight: 600;">{{ $teacher->email ?: 'Email Belum Diatur' }}</div>
                    </td>
                    <td style="padding: 1.25rem 1.5rem; text-align: center;">
                        @if($teacher->user)
                            <div style="display: inline-flex; align-items: center; gap: 6px; background: #F0FDF4; color: #166534; padding: 4px 10px; border-radius: 20px; font-size: 0.65rem; font-weight: 800; border: 1px solid #BBF7D0;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/></svg>
                                TERHUBUNG
                            </div>
                        @elseif($teacher->email)
                            <div style="display: inline-flex; align-items: center; gap: 6px; background: #FFFBEB; color: #92400E; padding: 4px 10px; border-radius: 20px; font-size: 0.65rem; font-weight: 800; border: 1px solid #FEF3C7;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                                BELUM ADA AKUN
                            </div>
                        @else
                            <div style="display: inline-flex; align-items: center; gap: 6px; background: #F8FAFC; color: #64748B; padding: 4px 10px; border-radius: 20px; font-size: 0.65rem; font-weight: 800; border: 1px solid #E2E8F0;">
                                OFFLINE
                            </div>
                        @endif
                    </td>
                    <td style="padding: 1.25rem 1.5rem; text-align: center;">
                        <div style="display: flex; gap: 0.6rem; justify-content: center;">
                            @can('edit-guru')
                            <form action="{{ route('teachers.toggle-active', $teacher) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn-action-icon" style="background: white; border: 1px solid var(--border); color: {{ $teacher->is_active ? '#F59E0B' : '#10B981' }};" title="{{ $teacher->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                    @if($teacher->is_active)
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                                    @endif
                                </button>
                            </form>
                            @endcan

                            @can('edit-guru')
                            <a href="{{ route('teachers.edit', $teacher) }}" class="btn-action-icon" style="background: white; border: 1px solid var(--border); color: #3B82F6;" title="Edit Data">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                            </a>
                            @endcan

                            @can('delete-guru')
                            <form action="{{ route('teachers.destroy', $teacher) }}" method="POST" onsubmit="return confirm('Hapus data guru?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-action-icon" style="background: white; border: 1px solid var(--border); color: #EF4444;" title="Hapus Permanen">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                </button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 4rem 2rem; color: var(--text-muted);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.3; margin-bottom: 1rem;"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        <div style="font-weight: 700; font-size: 1.1rem; color: var(--text); margin-bottom: 4px;">Data Guru Kosong</div>
                        <p style="font-size: 0.85rem;">Belum ada guru yang terdaftar atau pencarian tidak ditemukan.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 1rem 1.5rem; background: #F9FAFB; border-top: 1px solid var(--border);">
        {{ $teachers->withQueryString()->links() }}
    </div>
</div>

<style>
    .table-wrap tbody tr:hover {
        background: #F8FAFC !important;
    }
    .btn-action-icon {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: var(--shadow-sm);
    }
    .btn-action-icon:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow);
    }
</style>

@endsection
