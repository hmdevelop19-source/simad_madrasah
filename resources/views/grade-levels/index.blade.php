@extends('layouts.app')
@section('title', 'Tingkat Kelas')
@section('page-title', 'Tingkat Kelas')

@section('content')

{{-- ================================================================
     HEADER & STATS
================================================================ --}}
<div style="background: white; border: 1px solid var(--border); border-radius: 20px; padding: 1.5rem 2rem; margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between; box-shadow: var(--shadow-sm);">
    <div style="display: flex; align-items: center; gap: 1rem;">
        <div style="width: 48px; height: 48px; background: var(--bg-secondary); color: var(--primary); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
        </div>
        <div>
            <h2 style="font-size: 1.15rem; font-weight: 800; color: var(--primary); margin: 0;">Konfigurasi Tingkat</h2>
            <p style="font-size: 0.75rem; color: var(--text-muted); margin: 0;">Pengaturan jenjang kelas (misal: VII, VIII, IX) per unit.</p>
        </div>
    </div>
    
    @can('create-tingkat')
    <a href="{{ route('grade-levels.create') }}" class="btn btn-primary" style="height: 45px; border-radius: 30px; padding: 0 1.75rem; display: flex; align-items: center; gap: 0.5rem; font-weight: 800; background: var(--primary); box-shadow: var(--shadow-lg);">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
        TAMBAH TINGKAT
    </a>
    @endcan
</div>

{{-- ================================================================
     FILTER & DATA TABLE
================================================================ --}}
<div class="card" style="border-radius: 20px; border: 1px solid var(--border); box-shadow: var(--shadow-sm); overflow: hidden;">
    <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border); background: #F9FAFB;">
        <form method="GET" style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
            <div style="position: relative; width: 280px;">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama tingkat..." class="form-control" style="padding-left: 2.75rem; border-radius: 12px; height: 42px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            </div>
            
            <select name="education_level_id" class="form-control" style="width: 200px; border-radius: 12px; height: 42px; font-weight: 600;">
                <option value="">Semua Unit</option>
                @foreach($educationLevels as $level)
                    <option value="{{ $level->id }}" {{ $levelFilter == $level->id ? 'selected' : '' }}>{{ $level->nama }}</option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-outline" style="border-radius: 12px; height: 42px; font-weight: 800;">APPLY FILTER</button>
            
            @if($search || $levelFilter)
                <a href="{{ route('grade-levels.index') }}" class="btn btn-outline" style="border-radius: 12px; height: 42px; display: flex; align-items: center; justify-content: center;">✕</a>
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
                    <th style="padding: 1rem 1.5rem; font-size: 0.7rem; text-transform: uppercase; font-weight: 800; color: var(--text-muted);">Nama Tingkat / Kelas</th>
                    <th style="padding: 1rem 1.5rem; font-size: 0.7rem; text-transform: uppercase; font-weight: 800; color: var(--text-muted);">Unit Pendidikan</th>
                    <th style="padding: 1rem 1.5rem; font-size: 0.7rem; text-transform: uppercase; font-weight: 800; color: var(--text-muted); text-align: right;">Jumlah Rombel</th>
                    <th style="padding: 1rem 1.5rem; font-size: 0.7rem; text-transform: uppercase; font-weight: 800; color: var(--text-muted); text-align: center; width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($gradeLevels as $gl)
                <tr style="border-top: 1px solid var(--border); transition: all 0.2s;">
                    <td style="padding: 1.25rem 1.5rem; text-align: center; color: var(--text-muted); font-size: 0.8rem; font-weight: 600;">
                        {{ $gradeLevels->firstItem() + $loop->index }}
                    </td>
                    <td style="padding: 1.25rem 1.5rem;">
                        <div style="font-weight: 800; color: var(--primary); font-size: 1rem;">Kelas {{ $gl->nama_tingkat }}</div>
                    </td>
                    <td style="padding: 1.25rem 1.5rem;">
                        <span class="badge" style="background: var(--primary-light); color: var(--primary); font-weight: 800; padding: 0.35rem 0.75rem; border-radius: 8px; font-size: 0.7rem; border: 1px solid var(--primary-light);">
                            {{ $gl->educationLevel->kode }} • {{ $gl->educationLevel->nama }}
                        </span>
                    </td>
                    <td style="padding: 1.25rem 1.5rem; text-align: right;">
                        <span style="font-weight: 900; color: var(--primary); font-size: 1.1rem;">{{ $gl->classrooms_count }}</span>
                        <span style="font-size: 0.7rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Rombel</span>
                    </td>
                    <td style="padding: 1.25rem 1.5rem; text-align: center;">
                        <div style="display: flex; gap: 0.6rem; justify-content: center;">
                            @can('edit-tingkat')
                            <a href="{{ route('grade-levels.edit', $gl) }}" class="btn-action-icon" style="background: white; border: 1px solid var(--border); color: #F59E0B;" title="Edit Tingkat">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                            </a>
                            @endcan
                            @can('delete-tingkat')
                            <form action="{{ route('grade-levels.destroy', $gl) }}" method="POST" onsubmit="return confirm('Hapus tingkat {{ $gl->nama_tingkat }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-action-icon" style="background: white; border: 1px solid var(--border); color: #EF4444;" title="Hapus Tingkat">
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
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.3; margin-bottom: 1rem;"><circle cx="12" cy="12" r="10"/><path d="M8 12h8"/></svg>
                        <div style="font-weight: 700; font-size: 1.1rem; color: var(--text); margin-bottom: 4px;">Data Tingkat Kosong</div>
                        <p style="font-size: 0.85rem;">Belum ada tingkat kelas yang dikonfigurasi.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 1.25rem 1.5rem; background: #F9FAFB; border-top: 1px solid var(--border);">
        {{ $gradeLevels->withQueryString()->links() }}
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
