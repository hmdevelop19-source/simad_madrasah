@extends('layouts.app')
@section('title', 'Data Santri')
@section('page-title', 'Data Santri')

@section('content')

{{-- ================================================================
     FILTER & SEARCH DASHBOARD
================================================================ --}}
<div style="background: white; border: 1px solid var(--border); border-radius: 20px; padding: 1.5rem; margin-bottom: 2rem; box-shadow: var(--shadow-sm);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <div style="width: 38px; height: 38px; background: var(--bg-secondary); color: var(--primary); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/></svg>
            </div>
            <div>
                <h3 style="font-size: 1rem; font-weight: 800; color: var(--primary); margin: 0;">Pusat Data Santri</h3>
                <p style="font-size: 0.75rem; color: var(--text-muted); margin: 0;">Kelola data induk dan status administrasi seluruh santri.</p>
            </div>
        </div>
        @can('create-santri')
        <a href="{{ route('students.create') }}" class="btn btn-primary" style="height: 42px; border-radius: 30px; padding: 0 1.5rem; display: flex; align-items: center; gap: 0.5rem; font-weight: 800; background: var(--primary); box-shadow: var(--shadow-lg);">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
            DAFTARKAN SANTRI
        </a>
        @endcan
    </div>

    <form method="GET" style="display: flex; gap: 0.75rem; flex-wrap: wrap; background: #F9FAFB; padding: 1rem; border-radius: 16px; border: 1px solid var(--border);">
        <div style="position: relative; flex: 1; min-width: 260px;">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, NISN, atau NIK..." class="form-control" style="padding-left: 2.75rem; border-radius: 12px; height: 42px; border: 1px solid var(--border);">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
        </div>
        
        <select name="education_level_id" class="form-control" style="width: 180px; border-radius: 12px; height: 42px; font-weight: 600;">
            <option value="">Semua Unit</option>
            @foreach($educationLevels as $level)
                <option value="{{ $level->id }}" {{ $levelFilter == $level->id ? 'selected' : '' }}>{{ $level->nama }}</option>
            @endforeach
        </select>

        <select name="status_aktif" class="form-control" style="width: 160px; border-radius: 12px; height: 42px; font-weight: 600;">
            <option value="">Semua Status</option>
            @foreach($statusOptions as $status)
                <option value="{{ $status }}" {{ $statusFilter === $status ? 'selected' : '' }}>{{ $status }}</option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-outline" style="border-radius: 12px; height: 42px; padding: 0 1.5rem; font-weight: 800; background: white;">APPLY FILTER</button>
        
        @if($search || $levelFilter || $statusFilter !== 'Aktif')
            <a href="{{ route('students.index') }}" class="btn btn-outline" style="border-radius: 12px; height: 42px; padding: 0 1rem; display: flex; align-items: center; justify-content: center; background: white;">✕</a>
        @endif
    </form>
</div>

{{-- ================================================================
     DATA TABLE CARD
================================================================ --}}
<div class="card" style="border-radius: 20px; border: 1px solid var(--border); box-shadow: var(--shadow-sm); overflow: hidden;">
    <div class="table-wrap">
        <table style="width: 100%; border-collapse: separate; border-spacing: 0;">
            <thead>
                <tr style="background: #F9FAFB;">
                    <th style="padding: 1rem 1.5rem; font-size: 0.7rem; text-transform: uppercase; font-weight: 800; color: var(--text-muted); text-align: center; width: 60px;">#</th>
                    <th style="padding: 1rem 1.5rem; font-size: 0.7rem; text-transform: uppercase; font-weight: 800; color: var(--text-muted);">Informasi Santri</th>
                    <th style="padding: 1rem 1.5rem; font-size: 0.7rem; text-transform: uppercase; font-weight: 800; color: var(--text-muted);">Administrasi</th>
                    <th style="padding: 1rem 1.5rem; font-size: 0.7rem; text-transform: uppercase; font-weight: 800; color: var(--text-muted); text-align: center;">Status</th>
                    <th style="padding: 1rem 1.5rem; font-size: 0.7rem; text-transform: uppercase; font-weight: 800; color: var(--text-muted); text-align: center; width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                <tr style="transition: all 0.2s;">
                    <td style="padding: 1.25rem 1.5rem; text-align: center; color: var(--text-muted); font-size: 0.8rem; font-weight: 600;">
                        {{ $students->firstItem() + $loop->index }}
                    </td>
                    <td style="padding: 1.25rem 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="width: 44px; height: 44px; background: var(--bg-secondary); border: 2px solid white; box-shadow: 0 0 0 1px var(--border); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: 900; color: var(--primary); font-size: 1rem;">
                                {{ strtoupper(substr($student->nama_lengkap, 0, 1)) }}
                            </div>
                            <div>
                                <a href="{{ route('students.show', $student) }}" style="font-weight: 800; color: var(--primary); font-size: 0.95rem; text-decoration: none;">{{ $student->nama_lengkap }}</a>
                                <div style="font-size: 0.725rem; color: var(--text-muted); font-weight: 600; margin-top: 2px;">NISN: {{ $student->nisn ?? '–' }} • NIK: {{ $student->nik }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 1.25rem 1.5rem;">
                        <div style="display: flex; flex-direction: column; gap: 4px;">
                            <span class="badge" style="background: var(--primary-light); color: var(--primary); font-weight: 800; padding: 0.3rem 0.6rem; border-radius: 6px; align-self: flex-start; font-size: 0.65rem; letter-spacing: 0.5px;">{{ $student->educationLevel?->kode ?? '–' }}</span>
                            <span style="font-size: 0.775rem; color: var(--text-muted); font-weight: 600;">Wali: {{ $student->wali?->nama_lengkap ?? '–' }}</span>
                        </div>
                    </td>
                    <td style="padding: 1.25rem 1.5rem; text-align: center;">
                        @php 
                            $statusMap = [
                                'Aktif' => ['color' => '#10B981', 'bg' => '#ECFDF5', 'label' => 'AKTIF'],
                                'Lulus' => ['color' => '#3B82F6', 'bg' => '#EFF6FF', 'label' => 'LULUS'],
                                'Mutasi' => ['color' => '#8B5CF6', 'bg' => '#F5F3FF', 'label' => 'MUTASI'],
                                'Keluar' => ['color' => '#6B7280', 'bg' => '#F9FAFB', 'label' => 'KELUAR']
                            ];
                            $st = $statusMap[$student->status_aktif] ?? $statusMap['Keluar'];
                        @endphp
                        <div style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.4rem 0.8rem; border-radius: 30px; font-size: 0.725rem; font-weight: 800; background: {{ $st['bg'] }}; color: {{ $st['color'] }}; border: 1px solid {{ $st['color'] }}40;">
                            <span style="width: 6px; height: 6px; border-radius: 50%; background: {{ $st['color'] }};"></span>
                            {{ $st['label'] }}
                        </div>
                    </td>
                    <td style="padding: 1.25rem 1.5rem; text-align: center;">
                        <div style="display: flex; gap: 0.5rem; justify-content: center;">
                            <a href="{{ route('students.show', $student) }}" class="btn-action-icon" style="background: white; border: 1px solid var(--border); color: var(--primary);" title="Cek Detail">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            </a>
                            @can('edit-santri')
                            <a href="{{ route('students.edit', $student) }}" class="btn-action-icon" style="background: white; border: 1px solid var(--border); color: #F59E0B;" title="Edit Data">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </a>
                            @endcan

                            @can('delete-santri')
                            <form action="{{ route('students.destroy', $student) }}" method="POST" onsubmit="return confirm('Hapus data santri permanent?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-action-icon" style="background: white; border: 1px solid var(--border); color: #EF4444;" title="Hapus Data">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                </button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 5rem 2rem; color: var(--text-muted);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.3; margin-bottom: 1rem;"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/></svg>
                        <div style="font-weight: 700; font-size: 1.1rem; color: var(--text); margin-bottom: 4px;">Data Santri Kosong</div>
                        <p style="font-size: 0.85rem;">Gunakan filter atau pencarian lain, atau klik Daftarkan Santri Baru.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 1.25rem 1.5rem; background: #F9FAFB; border-top: 1px solid var(--border);">
        {{ $students->withQueryString()->links() }}
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
        border-radius: 10px;
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
