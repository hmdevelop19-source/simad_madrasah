@extends('layouts.app')
@section('title', 'Katalog Kurikulum')
@section('page-title', 'Katalog Kurikulum')

@section('content')

{{-- ================================================================
     FILTER & ACTION BAR
================================================================ --}}
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; background: white; padding: 1.25rem 2rem; border-radius: 20px; border: 1px solid var(--border); box-shadow: var(--shadow-sm);">
    <form method="GET" style="display: flex; align-items: center; gap: 0.75rem;">
        <div style="position: relative;">
            <select name="academic_year_id" class="form-control" style="width: 280px; border-radius: 30px; height: 42px; padding-left: 2.75rem; font-weight: 700; border: 1px solid var(--border);" onchange="this.form.submit()">
                <option value="">📅 Pilih Tahun Ajaran</option>
                @foreach($academicYears as $year)
                <option value="{{ $year->id }}" {{ $yearFilter == $year->id ? 'selected' : '' }}>
                    {{ $year->nama }} — {{ $year->periode }}{{ $year->is_active ? ' (AKTIF)' : '' }}
                </option>
                @endforeach
            </select>
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--primary);"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
        </div>
        @if($yearFilter)
            <a href="{{ route('curriculums.index') }}" class="btn btn-outline" style="border-radius: 30px; height: 42px; width: 42px; padding: 0; display: flex; align-items: center; justify-content: center;">✕</a>
        @endif
    </form>
    
    <div style="display: flex; gap: 0.75rem;">
        <a href="{{ route('curriculums.create') }}" class="btn btn-outline" style="height: 42px; border-radius: 30px; font-weight: 800;">DUPLIKAT / BULK</a>
        <a href="{{ route('curriculums.create') }}" class="btn btn-primary" style="height: 42px; border-radius: 30px; font-weight: 800; background: var(--primary); box-shadow: var(--shadow-lg);">+ TAMBAH MAPEL</a>
    </div>
</div>

{{-- ================================================================
     DETAIL FOCUS ZONE (When view=detail is requested)
================================================================ --}}
@if($detailRows)
@php
    $detYear = $detailRows->first()?->academicYear;
    $detUnit = $activeGradeLevel?->educationLevel;
    $dc = match($detUnit?->kode) {
        'TK'   => '#EA580C', 'MI' => '#2563EB', 'MTS' => '#16A34A', 'ULYA' => '#7C3AED',
        default => '#475569'
    };
@endphp
<div id="detail-focus" class="card" style="margin-bottom: 2.5rem; border-top: 4px solid {{ $dc }}; border-radius: 20px; box-shadow: var(--shadow-lg); z-index: 100; position: relative; animation: slideIn 0.3s ease-out; overflow: hidden;">
    <div style="padding: 1.75rem 2.5rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: {{ $dc }}05;">
        <div style="display: flex; align-items: center; gap: 1.25rem;">
            <div style="width: 46px; height: 46px; background: {{ $dc }}; color: white; border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px {{ $dc }}44;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/></svg>
            </div>
            <div>
                <h4 style="font-size: 1.15rem; font-weight: 900; color: var(--primary); margin: 0;">Mata Pelajaran &mdash; {{ $activeGradeLevel?->nama_tingkat }}</h4>
                <p style="font-size: 0.8rem; color: var(--text-muted); margin: 3px 0 0 0; font-weight: 600;">Kurikulum TP {{ $detYear?->nama }} &middot; Unit {{ $detUnit?->nama }}</p>
            </div>
        </div>
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <a href="{{ route('curriculums.create') }}?academic_year_id={{ $detYear?->id }}&grade_level_id={{ $activeGradeLevel?->id }}" class="btn btn-primary" style="height: 38px; border-radius: 30px; font-size: 0.75rem; font-weight: 800; background: var(--primary); border: none; padding: 0 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg> TAMBAH MAPEL
            </a>
            <a href="{{ route('curriculums.index', ['academic_year_id' => $yearFilter]) }}" class="btn btn-outline" style="border-radius: 30px; height: 38px; padding: 0 1.25rem; font-size: 0.75rem; font-weight: 800; display: flex; align-items: center; gap: 0.5rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg> KEMBALI
            </a>
        </div>
    </div>

    <div class="table-wrap">
        <table style="width: 100%; border-collapse: separate; border-spacing: 0;">
            <thead>
                <tr style="background: #F8FAFC;">
                    <th style="padding: 1.25rem 1.5rem; font-size: 0.7rem; text-transform: uppercase; font-weight: 900; color: var(--text-muted); text-align: center; width: 60px;">No</th>
                    <th style="padding: 1.25rem 1.5rem; font-size: 0.7rem; text-transform: uppercase; font-weight: 900; color: var(--text-muted); width: 150px;">Kode Item</th>
                    <th style="padding: 1.25rem 1.5rem; font-size: 0.7rem; text-transform: uppercase; font-weight: 900; color: var(--text-muted);">Nama Mata Pelajaran</th>
                    <th style="padding: 1.25rem 1.5rem; font-size: 0.7rem; text-transform: uppercase; font-weight: 900; color: var(--text-muted); text-align: center; width: 120px;">Standar KKM</th>
                    <th style="padding: 1.25rem 1.5rem; font-size: 0.7rem; text-transform: uppercase; font-weight: 900; color: var(--text-muted); text-align: center; width: 140px;">Aksi Kelola</th>
                </tr>
            </thead>
            <tbody>
                @forelse($detailRows as $i => $cur)
                <tr style="border-top: 1px solid var(--border); transition: 0.2s;">
                    <td style="padding: 1.15rem 1.5rem; text-align: center; color: var(--text-muted); font-weight: 700; font-size: 0.85rem;">{{ $i+1 }}</td>
                    <td style="padding: 1.15rem 1.5rem;">
                        <span style="font-family: 'JetBrains Mono', monospace; font-size: 0.75rem; background: {{ $dc }}11; color: {{ $dc }}; padding: 0.4rem 0.75rem; border-radius: 8px; font-weight: 800; border: 1px solid {{ $dc }}22;">{{ $cur->subject?->kode_mapel }}</span>
                    </td>
                    <td style="padding: 1.15rem 1.5rem;">
                        <div style="font-weight: 800; color: var(--primary); font-size: 1rem;">{{ $cur->subject?->nama_mapel }}</div>
                        <div style="font-size: 0.65rem; color: var(--text-muted); font-weight: 700; letter-spacing: 0.5px; margin-top: 2px;">{{ $detUnit?->nama }} &middot; {{ $activeGradeLevel?->nama_tingkat }}</div>
                    </td>
                    <td style="padding: 1.15rem 1.5rem; text-align: center;">
                        <div style="background: {{ $dc }}; color: white; display: inline-flex; width: 44px; height: 32px; align-items: center; justify-content: center; border-radius: 8px; font-weight: 900; font-size: 0.95rem; box-shadow: 0 4px 8px {{ $dc }}33;">{{ $cur->kkm }}</div>
                    </td>
                    <td style="padding: 1.15rem 1.5rem; text-align: center;">
                        <div style="display: flex; gap: 0.65rem; justify-content: center;">
                            <a href="{{ route('curriculums.edit', $cur) }}" class="btn-action-icon" style="background: #FFFBEB; border: 1px solid #FEF3C7; color: #D97706; width: 36px; height: 36px; border-radius: 10px;" title="Ubah Standar KKM">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                            </a>
                            <form action="{{ route('curriculums.destroy', $cur) }}" method="POST" onsubmit="return confirm('Hapus mapel ini dari kurikulum kelas {{ $activeGradeLevel?->nama_tingkat }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-action-icon" style="background: #FEF2F2; border: 1px solid #FEE2E2; color: #EF4444; width: 36px; height: 36px; border-radius: 10px;" title="Hapus dari Kurikulum">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 4rem 2rem; text-align: center;">
                        <div style="font-size: 0.85rem; color: var(--text-muted); font-weight: 600;">Belum ada mata pelajaran terdaftar untuk kelas ini.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- ================================================================
     CURRICULUM GROUPS (BY YEAR)
================================================================ --}}
@forelse($grouped as $yearId => $unitGroups)
@php
    $academicYear  = $unitGroups->flatten(1)->first()->academicYear;
    $totalMapel    = $unitGroups->flatten(1)->sum('jumlah_mapel');
    $totalTingkat  = $unitGroups->flatten(1)->count();
@endphp


@empty
{{-- ================================================================
     EMPTY STATE
================================================================ --}}
<div style="background: white; border: 2px dashed var(--border); border-radius: 20px; padding: 6rem 2rem; text-align: center;">
    <div style="width: 80px; height: 80px; background: var(--bg-secondary); border-radius: 30px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem auto;">
        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--primary); opacity: 0.4;"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/><path d="M8 7h6"/><path d="M8 11h8"/></svg>
    </div>
    <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--primary); margin-bottom: 0.5rem;">Tidak Ada Kurikulum Terdaftar</h3>
    <p style="font-size: 0.85rem; color: var(--text-muted); max-width: 400px; margin: 0 auto 2rem auto;">Silakan pilih tahun ajaran lain atau mulai memetakan mata pelajaran ke tingkatan kelas untuk tahun ajaran aktif.</p>
    <a href="{{ route('curriculums.create') }}" class="btn btn-primary" style="height: 48px; padding: 0 2rem; border-radius: 30px; font-weight: 800; background: var(--primary); box-shadow: var(--shadow-lg); display: inline-flex; align-items: center; gap: 0.6rem;">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
        BUAT KURIKULUM BARU
    </a>
</div>
@endforelse

<style>
    @keyframes slideIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .grade-card:hover { transform: translateY(-3px); border-color: var(--primary) !important; box-shadow: var(--shadow-lg); }
    .grade-card-add:hover { border-color: var(--primary) !important; background: var(--bg-secondary) !important; color: var(--primary) !important; }
    .btn-grade-action:hover { opacity: 0.9; transform: translateY(-1px); }
    .btn-action-icon { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 8px; cursor: pointer; transition: all 0.2s; box-shadow: var(--shadow-sm); }
    .btn-action-icon:hover { transform: translateY(-2px); box-shadow: var(--shadow); }
</style>

@endsection
