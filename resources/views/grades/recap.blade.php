@extends('layouts.app')
@section('title', 'Rekap Nilai')
@section('page-title', 'Rekap Nilai')

@section('content')

{{-- Filter --}}
<div class="card" style="margin-bottom:1rem;">
    <div style="padding:1rem 1.25rem;">
        <form method="GET" style="display:flex;flex-wrap:wrap;gap:.75rem;align-items:flex-end;">
            <div class="form-group" style="margin:0;min-width:180px;flex:1;">
                <label class="form-label" style="font-size:.78rem;">Tahun Ajaran</label>
                <select name="academic_year_id" class="form-control" onchange="this.form.submit()">
                    @foreach($academicYears as $y)
                    <option value="{{ $y->id }}" {{ $yearId == $y->id ? 'selected' : '' }}>
                        {{ $y->nama }} {{ $y->is_active ? ' ⭐' : '' }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="margin:0;min-width:150px;flex:1;">
                <label class="form-label" style="font-size:.78rem;">Kuartal / Evaluasi</label>
                <select name="academic_term_id" class="form-control" onchange="this.form.submit()">
                    @foreach($terms as $t)
                    <option value="{{ $t->id }}" {{ $termId == $t->id ? 'selected' : '' }}>
                        {{ $t->nama }} {{ $t->is_active ? ' (Aktif)' : '' }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="margin:0;min-width:180px;flex:1;">
                <label class="form-label" style="font-size:.78rem;">Tingkatan Kelas</label>
                <select name="grade_level_id" class="form-control" onchange="this.form.submit()">
                    <option value="">-- Pilih Tingkatan --</option>
                    @foreach($gradeLevels as $gl)
                    <option value="{{ $gl->id }}" {{ $gradeLevelId == $gl->id ? 'selected' : '' }}>
                        [{{ $gl->educationLevel?->kode }}] {{ $gl->nama_tingkat }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="margin:0;min-width:130px;">
                <label class="form-label" style="font-size:.78rem;">Jenis Nilai</label>
                <select name="jenis_nilai" class="form-control" onchange="this.form.submit()">
                    @foreach(\App\Models\Grade::JENIS_NILAI as $j)
                    <option value="{{ $j }}" {{ $jenisNilai == $j ? 'selected' : '' }}>{{ $j }}</option>
                    @endforeach
                </select>
            </div>
            <a href="{{ route('grades.index', ['academic_year_id'=>$yearId,'grade_level_id'=>$gradeLevelId]) }}"
               class="btn btn-outline btn-sm" style="align-self:flex-end;">✏️ Input Nilai</a>
        </form>
    </div>
</div>

@if($gradeLevelId && $students->isNotEmpty() && $curricula->isNotEmpty())
<div class="card">
    <div class="card-header">
        <span class="card-title" style="font-size:.88rem;">
            📊 Rekap Nilai {{ $jenisNilai }} — {{ $gradeLevels->firstWhere('id', $gradeLevelId)?->nama_tingkat }}
        </span>
        <span style="font-size:.78rem;color:var(--text-muted);">{{ $students->count() }} santri · {{ $curricula->count() }} mapel</span>
    </div>
    <div class="table-wrap" style="overflow-x:auto;">
        <table style="min-width:{{ max(600, 160 + $curricula->count() * 90) }}px;">
            <thead>
                <tr>
                    <th style="position:sticky;left:0;background:var(--bg-secondary);z-index:2;min-width:40px;">#</th>
                    <th style="position:sticky;left:40px;background:var(--bg-secondary);z-index:2;min-width:160px;">Nama Santri</th>
                    @foreach($curricula as $cur)
                    <th style="text-align:center;min-width:85px;font-size:.72rem;">
                        <span style="display:block;font-weight:700;">{{ $cur->subject?->kode_mapel }}</span>
                        <span style="font-size:.65rem;color:var(--text-muted);font-weight:400;">KKM {{ $cur->kkm }}</span>
                    </th>
                    @endforeach
                    <th style="text-align:center;min-width:75px;">Rata-rata</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $i => $student)
                @php
                    $nilaiList = [];
                    foreach($curricula as $cur) {
                        $nilaiList[] = $gradeMap[$student->id][$cur->id] ?? null;
                    }
                    $filled = array_filter($nilaiList, fn($v) => $v !== null);
                    $rataRata = count($filled) > 0 ? round(array_sum($filled) / count($filled), 1) : null;
                @endphp
                <tr>
                    <td style="position:sticky;left:0;background:var(--bg);color:var(--text-muted);">{{ $i+1 }}</td>
                    <td style="position:sticky;left:40px;background:var(--bg);font-weight:500;white-space:nowrap;">{{ $student->nama_lengkap }}</td>
                    @foreach($curricula as $ci => $cur)
                    @php
                        $n = $gradeMap[$student->id][$cur->id] ?? null;
                        $below = $n !== null && $n < $cur->kkm;
                    @endphp
                    <td style="text-align:center;{{ $below ? 'background:#FFF1F2;' : '' }}">
                        @if($n !== null)
                        <span style="font-weight:700;font-size:.88rem;color:{{ $below ? '#DC2626' : '#16A34A' }};">{{ $n }}</span>
                        @else
                        <span style="color:var(--border);font-size:.78rem;">–</span>
                        @endif
                    </td>
                    @endforeach
                    <td style="text-align:center;font-weight:700;color:var(--primary);">
                        {{ $rataRata ?? '–' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@else
<div class="card">
    <div style="text-align:center;padding:2.5rem;color:var(--text-muted);">
        <div style="font-size:2.5rem;margin-bottom:.5rem;">📊</div>
        <p>Pilih tahun ajaran dan tingkatan kelas untuk melihat rekap nilai.</p>
    </div>
</div>
@endif

@endsection
