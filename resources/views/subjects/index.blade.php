@extends('layouts.app')
@section('title', 'Mata Pelajaran')
@section('page-title', 'Mata Pelajaran')

@section('content')

{{-- ================================================================
     HEADER & ACTION TOOLBAR
================================================================ --}}
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; background: white; padding: 1.25rem 2rem; border-radius: 20px; border: 1px solid var(--border); box-shadow: var(--shadow-sm);">
    <div style="display: flex; align-items: center; gap: 1rem;">
        <div style="width: 48px; height: 48px; background: var(--bg-secondary); color: var(--primary); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/><path d="M8 7h6"/><path d="M8 11h8"/></svg>
        </div>
        <div>
            <h2 style="font-size: 1.15rem; font-weight: 800; color: var(--primary); margin: 0;">Katalog Mata Pelajaran</h2>
            <p style="font-size: 0.75rem; color: var(--text-muted); margin: 0;">Daftar referensi mata pelajaran lintas unit pendidikan.</p>
        </div>
    </div>
    
    @can('create-mapel')
    <a href="{{ route('subjects.create') }}" class="btn btn-primary" style="height: 45px; border-radius: 30px; padding: 0 1.75rem; display: flex; align-items: center; gap: 0.5rem; font-weight: 800; background: var(--primary); box-shadow: var(--shadow-lg);">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
        TAMBAH MAPEL
    </a>
    @endcan
</div>

{{-- ================================================================
     SEARCH & DATA TABLE
================================================================ --}}
<div class="card" style="border-radius: 20px; border: 1px solid var(--border); box-shadow: var(--shadow-sm); overflow: hidden;">
    <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border); background: #F9FAFB;">
        <form method="GET" style="display: flex; gap: 0.75rem;">
            <div style="position: relative; width: 100%; max-width: 400px;">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari kode atau nama mapel..." class="form-control" style="padding-left: 2.75rem; border-radius: 12px; height: 42px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            </div>
            <button type="submit" class="btn btn-outline" style="border-radius: 12px; height: 42px; font-weight: 700;">CARI</button>
            @if($search)
                <a href="{{ route('subjects.index') }}" class="btn btn-outline" style="border-radius: 12px; height: 42px; display: flex; align-items: center;">✕</a>
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
            <thead>
                <tr style="background: white;">
                    <th style="padding: 1rem 1.5rem; font-size: 0.7rem; text-transform: uppercase; font-weight: 800; color: var(--text-muted); text-align: center; width: 60px;">#</th>
                    <th style="padding: 1rem 1.5rem; font-size: 0.7rem; text-transform: uppercase; font-weight: 800; color: var(--text-muted); width: 140px;">Kode Mapel</th>
                    <th style="padding: 1rem 1.5rem; font-size: 0.7rem; text-transform: uppercase; font-weight: 800; color: var(--text-muted);">Nama Mata Pelajaran</th>
                    <th style="padding: 1rem 1.5rem; font-size: 0.7rem; text-transform: uppercase; font-weight: 800; color: var(--text-muted); text-align: center; width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subjects as $subject)
                <tr style="border-top: 1px solid var(--border); transition: all 0.2s;">
                    <td style="padding: 1.25rem 1.5rem; text-align: center; color: var(--text-muted); font-size: 0.8rem; font-weight: 600;">
                        {{ $subjects->firstItem() + $loop->index }}
                    </td>
                    <td style="padding: 1.25rem 1.5rem;">
                        <span style="font-family: 'JetBrains Mono', monospace; font-size: 0.75rem; background: var(--bg-secondary); color: var(--primary); padding: 0.4rem 0.75rem; border-radius: 8px; font-weight: 800; border: 1px solid var(--border);">
                            {{ $subject->kode_mapel }}
                        </span>
                    </td>
                    <td style="padding: 1.25rem 1.5rem;">
                        <div style="font-weight: 700; color: var(--primary); font-size: 0.95rem;">{{ $subject->nama_mapel }}</div>
                    </td>
                    <td style="padding: 1.25rem 1.5rem; text-align: center;">
                        <div style="display: flex; gap: 0.6rem; justify-content: center;">
                            @can('edit-mapel')
                            <a href="{{ route('subjects.edit', $subject) }}" class="btn-action-icon" style="background: white; border: 1px solid var(--border); color: #F59E0B;" title="Edit Mapel">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                            </a>
                            @endcan
                            
                            @can('delete-mapel')
                            <form action="{{ route('subjects.destroy', $subject) }}" method="POST" onsubmit="return confirm('Hapus mata pelajaran {{ $subject->nama_mapel }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-action-icon" style="background: white; border: 1px solid var(--border); color: #EF4444;" title="Hapus Mapel">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                </button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 4rem 2rem; color: var(--text-muted);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.3; margin-bottom: 1rem;"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/><path d="M8 7h6"/><path d="M8 11h8"/></svg>
                        <div style="font-weight: 700; font-size: 1.1rem; color: var(--text); margin-bottom: 4px;">Katalog Mapel Kosong</div>
                        <p style="font-size: 0.85rem;">Belum ada mata pelajaran yang terdaftar.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 1.25rem 1.5rem; background: #F9FAFB; border-top: 1px solid var(--border);">
        {{ $subjects->withQueryString()->links() }}
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
</style>

@endsection
