@extends('layouts.app')
@section('title', 'Rombel Kelas')
@section('page-title', 'Rombel Kelas')

@section('content')

{{-- ================================================================
     HEADER & ACTION BAR
================================================================ --}}
<div style="background: white; border: 1px solid var(--border); border-radius: 20px; padding: 1.5rem 2rem; margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between; box-shadow: var(--shadow-sm);">
    <div style="display: flex; align-items: center; gap: 1rem;">
        <div style="width: 48px; height: 48px; background: var(--bg-secondary); color: var(--primary); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9Z"/><path d="m3 9 2.45-4.91A2 2 0 0 1 7.24 3h9.52a2 2 0 0 1 1.79 1.09L21 9"/><path d="M12 3v6"/></svg>
        </div>
        <div>
            <h2 style="font-size: 1.15rem; font-weight: 800; color: var(--primary); margin: 0;">Manajemen Rombel</h2>
            <p style="font-size: 0.75rem; color: var(--text-muted); margin: 0;">Daftar pembagian kelas dan penetapan wali kelas aktif.</p>
        </div>
    </div>
    
    @can('create-rombel')
    <a href="{{ route('classrooms.create') }}" class="btn btn-primary" style="height: 45px; border-radius: 30px; padding: 0 1.75rem; display: flex; align-items: center; gap: 0.5rem; font-weight: 800; background: var(--primary); box-shadow: var(--shadow-lg);">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
        TAMBAH ROMBEL
    </a>
    @endcan
</div>

{{-- ================================================================
     FILTER & DATA TABLE
================================================================ --}}
<div class="card" style="border-radius: 20px; border: 1px solid var(--border); box-shadow: var(--shadow-sm); overflow: hidden;">
    <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border); background: #F9FAFB;">
        <form method="GET" style="display: flex; gap: 0.75rem;">
            <select name="education_level_id" class="form-control" style="width: 240px; border-radius: 12px; height: 42px; font-weight: 600;">
                <option value="">Semua Unit Pendidikan</option>
                @foreach($educationLevels as $level)
                    <option value="{{ $level->id }}" {{ $levelFilter == $level->id ? 'selected' : '' }}>{{ $level->nama }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-outline" style="border-radius: 12px; height: 42px; font-weight: 800;">APPLY FILTER</button>
            @if($levelFilter)
                <a href="{{ route('classrooms.index') }}" class="btn btn-outline" style="border-radius: 12px; height: 42px; display: flex; align-items: center; justify-content: center;">✕</a>
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
                    <th style="padding: 1rem 1.5rem; font-size: 0.7rem; text-transform: uppercase; font-weight: 800; color: var(--text-muted);">Nama Rombel / Kelas</th>
                    <th style="padding: 1rem 1.5rem; font-size: 0.7rem; text-transform: uppercase; font-weight: 800; color: var(--text-muted);">Tingkat & Unit</th>
                    <th style="padding: 1rem 1.5rem; font-size: 0.7rem; text-transform: uppercase; font-weight: 800; color: var(--text-muted);">Wali Kelas</th>
                    <th style="padding: 1rem 1.5rem; font-size: 0.7rem; text-transform: uppercase; font-weight: 800; color: var(--text-muted); text-align: center; width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($classrooms as $classroom)
                <tr style="border-top: 1px solid var(--border); transition: all 0.2s;">
                    <td style="padding: 1.25rem 1.5rem; text-align: center; color: var(--text-muted); font-size: 0.8rem; font-weight: 600;">
                        {{ $classrooms->firstItem() + $loop->index }}
                    </td>
                    <td style="padding: 1.25rem 1.5rem;">
                        <div style="font-weight: 800; color: var(--primary); font-size: 1rem;">{{ $classroom->nama_kelas }}</div>
                    </td>
                    <td style="padding: 1.25rem 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 0.6rem;">
                            <span class="badge" style="background: var(--bg-secondary); color: var(--primary); font-weight: 800; padding: 0.35rem 0.6rem; border-radius: 6px; font-size: 0.65rem; border: 1px solid var(--border);">
                                KELAS {{ $classroom->gradeLevel->nama_tingkat }}
                            </span>
                            <span style="font-size: 0.7rem; font-weight: 700; color: var(--highlight);">{{ $classroom->gradeLevel->educationLevel->kode }}</span>
                        </div>
                    </td>
                    <td style="padding: 1.25rem 1.5rem;">
                        @if($classroom->waliKelas)
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <div style="width: 32px; height: 32px; background: var(--primary-light); color: var(--primary); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 0.75rem;">
                                    {{ strtoupper(substr($classroom->waliKelas->nama_lengkap, 0, 1)) }}
                                </div>
                                <span style="font-size: 0.85rem; font-weight: 600; color: var(--text);">{{ $classroom->waliKelas->nama_lengkap }}</span>
                            </div>
                        @else
                            <span style="font-size: 0.8rem; color: var(--text-muted); font-style: italic;">Belum ditentukan</span>
                        @endif
                    </td>
                    <td style="padding: 1.25rem 1.5rem; text-align: center;">
                        <div style="display: flex; gap: 0.6rem; justify-content: center;">
                            @can('edit-rombel')
                            <a href="{{ route('classrooms.edit', $classroom) }}" class="btn-action-icon" style="background: white; border: 1px solid var(--border); color: #F59E0B;" title="Edit Rombel">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                            </a>
                            @endcan

                            @can('delete-rombel')
                            <form action="{{ route('classrooms.destroy', $classroom) }}" method="POST" onsubmit="return confirm('Hapus rombel kelas {{ $classroom->nama_kelas }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-action-icon" style="background: white; border: 1px solid var(--border); color: #EF4444;" title="Hapus Rombel">
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
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.3; margin-bottom: 1rem;"><path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9Z"/></svg>
                        <div style="font-weight: 700; font-size: 1.1rem; color: var(--text); margin-bottom: 4px;">Rombel Kosong</div>
                        <p style="font-size: 0.85rem;">Belum ada rombel kelas yang terdaftar.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 1.25rem 1.5rem; background: #F9FAFB; border-top: 1px solid var(--border);">
        {{ $classrooms->withQueryString()->links() }}
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
