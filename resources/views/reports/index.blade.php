@extends('layouts.app')
@section('title', 'E-Raport')
@section('page-title', 'E-Raport')

@section('content')

{{-- Filter --}}
<div class="card" style="margin-bottom:1rem;">
    <div style="padding:1rem 1.25rem;">
        <form method="GET" style="display:flex;flex-wrap:wrap;gap:.75rem;align-items:flex-end;">
            <div class="form-group" style="margin:0;min-width:200px;flex:1;">
                <label class="form-label" style="font-size:.78rem;">Tahun Ajaran</label>
                <select name="academic_year_id" class="form-control" onchange="this.form.submit()">
                    @foreach($academicYears as $y)
                    <option value="{{ $y->id }}" {{ $yearId == $y->id ? 'selected' : '' }}>
                        {{ $y->nama }} — {{ $y->periode }}{{ $y->is_active ? ' ⭐' : '' }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="margin:0;min-width:200px;flex:1;">
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
        </form>
    </div>
</div>

@if($gradeLevelId && $histories->isNotEmpty())
<div class="card">
    <div class="card-header">
        <span class="card-title">📄 Daftar Santri — {{ $gradeLevels->firstWhere('id',$gradeLevelId)?->nama_tingkat }}</span>
        <span style="font-size:.78rem;color:var(--text-muted);">{{ $histories->count() }} santri</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Santri</th>
                    <th>Kelas</th>
                    <th style="text-align:center;">Status Kenaikan</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($histories as $i => $h)
                @php
                    $student = $h->student;
                    $statusColor = match($h->status_kenaikan ?? '') {
                        'Naik Kelas'   => ['bg'=>'#DCFCE7','color'=>'#166534'],
                        'Tinggal Kelas'=> ['bg'=>'#FEF9C3','color'=>'#854D0E'],
                        'Lulus'        => ['bg'=>'#EDE9FE','color'=>'#5B21B6'],
                        'Mutasi'       => ['bg'=>'#FEE2E2','color'=>'#991B1B'],
                        default        => ['bg'=>'#F1F5F9','color'=>'#475569'],
                    };
                @endphp
                <tr>
                    <td style="color:var(--text-muted);">{{ $i+1 }}</td>
                    <td style="font-weight:500;">{{ $student?->nama_lengkap }}</td>
                    <td style="font-size:.82rem;">{{ $h->classroom?->nama_kelas }}</td>
                    <td style="text-align:center;">
                        <span style="background:{{ $statusColor['bg'] }};color:{{ $statusColor['color'] }};padding:2px 10px;border-radius:999px;font-size:.72rem;font-weight:700;">
                            {{ $h->status_kenaikan ?? 'Belum Ditentukan' }}
                        </span>
                    </td>
                    <td style="text-align:center;">
                        <div style="display:inline-flex;gap:.35rem;">
                            <a href="{{ route('reports.show', [$student, 'academic_year_id'=>$yearId]) }}"
                               class="btn btn-outline btn-sm" style="font-size:.72rem;">👁️ Lihat Raport</a>
                            <a href="{{ route('reports.print', [$student, 'academic_year_id'=>$yearId]) }}"
                               target="_blank"
                               class="btn btn-sm" style="font-size:.72rem;background:#EFF6FF;color:#2563EB;border:1px solid #BFDBFE;">🖨️ Cetak</a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@else
<div class="card">
    <div style="text-align:center;padding:3rem;color:var(--text-muted);">
        <div style="font-size:3rem;margin-bottom:.75rem;">📄</div>
        <p style="font-weight:600;margin-bottom:.4rem;">E-Raport Digital</p>
        <p style="font-size:.85rem;">Pilih tahun ajaran dan tingkatan kelas untuk melihat daftar santri.</p>
    </div>
</div>
@endif

@endsection
