@extends('layouts.app')
@section('title', 'Nilai Kepribadian')
@section('page-title', 'Nilai Kepribadian')

@section('content')

{{-- Filter Panel --}}
<div class="card" style="margin-bottom:1rem;">
    <div style="padding:1rem 1.25rem;">
        <form method="GET" id="filterForm" style="display:flex;flex-wrap:wrap;gap:.75rem;">
            <div class="form-group" style="margin:0;min-width:180px;flex:1;">
                <label class="form-label" style="font-size:.78rem;">Tahun Ajaran</label>
                <select name="academic_year_id" class="form-control" onchange="submitFilter()">
                    @foreach($academicYears as $y)
                    <option value="{{ $y->id }}" {{ $yearId == $y->id ? 'selected' : '' }}>
                        {{ $y->nama }} {{ $y->is_active ? ' ⭐' : '' }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="margin:0;min-width:150px;flex:1;">
                <label class="form-label" style="font-size:.78rem;">Kuartal / Evaluasi</label>
                <select name="academic_term_id" class="form-control" onchange="submitFilter()">
                    @foreach($terms as $t)
                    <option value="{{ $t->id }}" {{ $termId == $t->id ? 'selected' : '' }}>
                        {{ $t->nama }} {{ $t->is_active ? ' (Aktif)' : '' }}
                    </option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group" style="margin:0;min-width:180px;flex:1;">
                <label class="form-label" style="font-size:.78rem;">Tingkatan Unit</label>
                <select name="grade_level_id" class="form-control" onchange="submitFilter()">
                    <option value="">-- Pilih Tingkatan --</option>
                    @foreach($gradeLevels as $gl)
                    <option value="{{ $gl->id }}" {{ $gradeLevelId == $gl->id ? 'selected' : '' }}>
                        [{{ $gl->educationLevel?->kode }}] {{ $gl->nama_tingkat }}
                    </option>
                    @endforeach
                </select>
            </div>

            @if($gradeLevelId)
            <div class="form-group" style="margin:0;min-width:180px;flex:1;">
                <label class="form-label" style="font-size:.78rem;">Kelas</label>
                <select name="class_id" class="form-control" onchange="submitFilter()">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($classrooms as $cls)
                    <option value="{{ $cls->id }}" {{ $classId == $cls->id ? 'selected' : '' }}>
                        {{ $cls->nama_kelas }} ({{ $cls->teacher?->nama_lengkap ?? 'Tanpa Wali' }})
                    </option>
                    @endforeach
                </select>
            </div>
            @endif
        </form>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success" style="margin-bottom:1rem;">{{ session('success') }}</div>
@endif

@if($classId && $yearId)
    @if($students->isEmpty())
    <div class="card">
        <div style="text-align:center;padding:2.5rem;color:var(--text-muted);">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom:1rem; opacity:0.3;"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            <p>Belum ada santri di kelas ini pada tahun ajaran yang dipilih.</p>
        </div>
    </div>
    @else
    
    <div style="background:var(--bg-secondary); border: 1px solid var(--border); padding: 1.25rem 1.5rem; border-radius: 12px; margin-bottom: 2rem; display: flex; align-items: flex-start; gap: 1rem;">
        <div style="color: var(--warning); padding-top: 2px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="16" y2="12"/><line x1="12" x2="12.01" y1="8" y2="8"/></svg>
        </div>
        <div style="font-size: 0.85rem; color: var(--text-secondary); line-height: 1.5;">
            <strong style="color: var(--text);">Panduan Pengisian:</strong> Biarkan pilihan pada <strong>[ – ]</strong> jika tidak ada catatan/nilai khusus untuk suatu aspek pada santri tertentu. Sistem akan menggunakan nilai fallback saat cetak E-Raport (misal: "Baik").
        </div>
    </div>

    <form action="{{ route('personality.store') }}" method="POST">
        @csrf
        <input type="hidden" name="academic_year_id" value="{{ $yearId }}">
        <input type="hidden" name="academic_term_id" value="{{ $termId }}">
        <input type="hidden" name="grade_level_id"   value="{{ $gradeLevelId }}">
        <input type="hidden" name="class_id"         value="{{ $classId }}">

        <div class="card" style="box-shadow: var(--shadow); border-radius: 20px; overflow: hidden;">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr style="background: var(--bg-secondary);">
                            <th style="width: 60px; text-align: center; padding-left: 1.5rem;">#</th>
                            <th style="min-width: 200px;">Data Santri</th>
                            @foreach($aspeks as $a)
                            <th style="min-width: 140px; text-align: center;">{{ $a }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $idx => $student)
                        <tr>
                            <td style="text-align: center; color:var(--text-muted);">{{ $idx + 1 }}</td>
                            <td style="font-weight: 500;">
                                {{ strtoupper($student->nama_lengkap) }}
                                <div style="font-size:.7rem;color:var(--text-muted);font-weight:normal;">NIS: {{ $student->nis ?? '-' }}</div>
                            </td>
                            
                            @foreach($aspeks as $a)
                                @php
                                    $currentVal = $existingMap[$student->id][$a] ?? '';
                                @endphp
                                <td style="text-align: center; padding: .5rem;">
                                    <select name="nilai[{{ $student->id }}][{{ $a }}]" class="form-control form-control-sm" style="font-size: .8rem; text-align: center;">
                                        <option value="">-- [ – ] --</option>
                                        @foreach($predikats as $p)
                                            <option value="{{ $p }}" {{ $currentVal === $p ? 'selected' : '' }}>
                                                {{ $p }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="padding:1rem; border-top:1px solid var(--border); text-align: right;">
                <button type="submit" class="btn btn-primary">💾 Simpan Nilai Kepribadian</button>
            </div>
        </div>
    </form>
    @endif
@else
<div class="card">
    <div style="text-align:center;padding:3rem;color:var(--text-muted);">
        <div style="font-size:3rem;margin-bottom:.5rem;opacity:0.5;">🤝</div>
        <p style="font-size:1.1rem;font-weight:500;">Cari Kelas untuk Menilai Kepribadian</p>
        <p style="font-size:.85rem;">Pilih tahun ajaran, tingkatan unit, dan kelas di atas.</p>
    </div>
</div>
@endif

<script>
function submitFilter() {
    document.getElementById('filterForm').submit();
}
</script>
@endsection
