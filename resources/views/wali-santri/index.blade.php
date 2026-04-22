@extends('layouts.app')
@section('title', 'Wali Santri')
@section('page-title', 'Data Wali Santri')

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
                <div style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">Total Wali</div>
                <div style="font-size: 1.15rem; font-weight: 800; color: var(--primary);">{{ $waliList->total() }} Orang</div>
            </div>
        </div>
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="width: 45px; height: 45px; background: #E0F2FE; color: #0369A1; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 6.1H3"/><path d="M21 12.1H3"/><path d="M15.1 18.1H3"/></svg>
            </div>
            <div>
                <div style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">Rasul Santri/Wali</div>
                <div style="font-size: 1.15rem; font-weight: 800; color: #0369A1;">1 : {{ round($waliList->sum('students_count') / max(1, $waliList->total()), 1) }} Avg</div>
            </div>
        </div>
    </div>
    
    @can('create-wali')
    <a href="{{ route('wali-santri.create') }}" class="btn btn-primary" style="height: 45px; border-radius: 30px; padding: 0 1.5rem; display: flex; align-items: center; gap: 0.5rem; font-weight: 800; box-shadow: var(--shadow-lg);">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
        TAMBAH WALI
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
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, NIK, atau WhatsApp..." class="form-control" style="padding-left: 2.75rem; border-radius: 30px; height: 42px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            </div>
            <button type="submit" class="btn btn-outline" style="border-radius: 30px; height: 42px; font-weight: 700;">CARI WALI</button>
            @if($search)
                <a href="{{ route('wali-santri.index') }}" class="btn btn-outline" style="border-radius: 30px; height: 42px; display: flex; align-items: center;">✕ RESET</a>
            @endif
        </form>
    </div>

    <div class="table-wrap">
        <table style="width: 100%; border-collapse: separate; border-spacing: 0;">
            <thead>
                <tr style="background: white;">
                    <th style="padding: 1rem 1.5rem; font-size: 0.7rem; text-transform: uppercase; font-weight: 800; color: var(--text-muted); text-align: center; width: 60px;">#</th>
                    <th style="padding: 1rem 1.5rem; font-size: 0.7rem; text-transform: uppercase; font-weight: 800; color: var(--text-muted);">Informasi Wali</th>
                    <th style="padding: 1rem 1.5rem; font-size: 0.7rem; text-transform: uppercase; font-weight: 800; color: var(--text-muted);">Kependudukan</th>
                    <th style="padding: 1rem 1.5rem; font-size: 0.7rem; text-transform: uppercase; font-weight: 800; color: var(--text-muted);">Kontak & Hubungan</th>
                    <th style="padding: 1rem 1.5rem; font-size: 0.7rem; text-transform: uppercase; font-weight: 800; color: var(--text-muted); text-align: right;">Santri</th>
                    <th style="padding: 1rem 1.5rem; font-size: 0.7rem; text-transform: uppercase; font-weight: 800; color: var(--text-muted); text-align: center; width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($waliList as $wali)
                <tr style="border-top: 1px solid var(--border); transition: all 0.2s;">
                    <td style="padding: 1.25rem 1.5rem; text-align: center; color: var(--text-muted); font-size: 0.8rem; font-weight: 600;">
                        {{ $waliList->firstItem() + $loop->index }}
                    </td>
                    <td style="padding: 1.25rem 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="width: 42px; height: 42px; background: var(--bg-secondary); border: 2px solid white; box-shadow: 0 0 0 1px var(--border); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: 900; color: var(--primary); font-size: 0.9rem;">
                                {{ strtoupper(substr($wali->nama_lengkap, 0, 1)) }}
                            </div>
                            <div>
                                <a href="{{ route('wali-santri.show', $wali) }}" style="font-weight: 800; color: var(--primary); font-size: 0.9rem; text-decoration: none;">{{ $wali->nama_lengkap }}</a>
                                <div style="font-size: 0.725rem; color: var(--text-muted); font-weight: 600; margin-top: 2px;">{{ $wali->pekerjaan ?? 'Pekerjaan: –' }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 1.25rem 1.5rem;">
                        <code style="font-size: 0.8rem; background: var(--bg-secondary); padding: 0.3rem 0.5rem; border-radius: 6px; color: var(--primary); font-weight: 600;">{{ $wali->nik }}</code>
                    </td>
                    <td style="padding: 1.25rem 1.5rem;">
                        <div style="display: flex; flex-direction: column; gap: 4px;">
                            <div style="display: flex; align-items: center; gap: 6px;">
                                <span class="badge" style="background: #E0F2FE; color: #0369A1; font-weight: 800; padding: 0.2rem 0.6rem; border-radius: 6px; font-size: 0.65rem;">{{ strtoupper($wali->hubungan_keluarga) }}</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 4px; color: #059669; font-weight: 700; font-size: 0.8rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l2.19-2.19a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                {{ $wali->no_whatsapp }}
                            </div>
                        </div>
                    </td>
                    <td style="padding: 1.25rem 1.5rem; text-align: right;">
                        <span style="font-weight: 900; color: var(--primary); font-size: 1.1rem;">{{ $wali->students_count }}</span>
                        <span style="font-size: 0.7rem; color: var(--text-muted); font-weight: 700;">SANTRI</span>
                    </td>
                    <td style="padding: 1.25rem 1.5rem; text-align: center;">
                        <div style="display: flex; gap: 0.6rem; justify-content: center;">
                            <a href="{{ route('wali-santri.show', $wali) }}" class="btn-action-icon" style="background: white; border: 1px solid var(--border); color: var(--primary);" title="Cek Detail">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            </a>
                            @can('edit-wali')
                            <a href="{{ route('wali-santri.edit', $wali) }}" class="btn-action-icon" style="background: white; border: 1px solid var(--border); color: #F59E0B;" title="Edit Data">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                            </a>
                            @endcan
                            @can('delete-wali')
                            <form action="{{ route('wali-santri.destroy', $wali) }}" method="POST" onsubmit="return confirm('Hapus data wali santri?')">
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
                    <td colspan="6" style="text-align: center; padding: 4rem 2rem; color: var(--text-muted);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.3; margin-bottom: 1rem;"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        <div style="font-weight: 700; font-size: 1.1rem; color: var(--text); margin-bottom: 4px;">Data Wali Santri Kosong</div>
                        <p style="font-size: 0.85rem;">Belum ada wali yang terdaftar atau pencarian tidak ditemukan.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 1rem 1.5rem; background: #F9FAFB; border-top: 1px solid var(--border);">
        {{ $waliList->withQueryString()->links() }}
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
