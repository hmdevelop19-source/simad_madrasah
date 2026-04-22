@extends('layouts.app')
@section('title', 'Rekap Presensi')
@section('page-title', 'Rekap Presensi')

@section('content')

{{-- Filter --}}
<div class="card" style="margin-bottom:1rem;">
    <div style="padding:1rem 1.25rem;">
        <form method="GET" style="display:flex;flex-wrap:wrap;gap:.75rem;align-items:flex-end;">
            <div class="form-group" style="margin:0;flex:1;min-width:180px;">
                <label class="form-label" style="font-size:.78rem;">Kelas</label>
                <select name="class_id" class="form-control">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($classrooms as $cl)
                    <option value="{{ $cl->id }}" {{ $classId == $cl->id ? 'selected' : '' }}>
                        [{{ $cl->gradeLevel?->educationLevel?->kode }}] {{ $cl->nama_kelas }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="margin:0;min-width:150px;">
                <label class="form-label" style="font-size:.78rem;">Bulan</label>
                <select name="bulan" class="form-control">
                    @foreach($bulanOptions as $val => $lbl)
                    <option value="{{ $val }}" {{ $bulan == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="margin:0;min-width:100px;">
                <label class="form-label" style="font-size:.78rem;">Tahun</label>
                <input type="number" name="tahun" class="form-control" value="{{ $tahun }}" min="2020" max="2035">
            </div>
            <button type="submit" class="btn btn-primary btn-sm">🔍 Tampilkan</button>
            <a href="{{ route('attendances.index', ['class_id' => $classId]) }}"
               class="btn btn-outline btn-sm">➕ Input Presensi</a>
        </form>
    </div>
</div>

@if($classId && $students->isNotEmpty())
<div class="card">
    <div class="card-header">
        <span class="card-title">
            📊 Rekap — {{ $classroom?->nama_kelas }}
            ({{ $bulanOptions[$bulan] ?? $bulan }} {{ $tahun }})
        </span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Santri</th>
                    <th style="text-align:center;background:#DCFCE7;">Hadir</th>
                    <th style="text-align:center;background:#FEF9C3;">Sakit</th>
                    <th style="text-align:center;background:#DBEAFE;">Izin</th>
                    <th style="text-align:center;background:#FEE2E2;">Alpha</th>
                    <th style="text-align:center;">Total Hari</th>
                    <th style="text-align:center;">% Hadir</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $i => $student)
                @php
                    $r = $recap[$student->id] ?? ['Hadir'=>0,'Sakit'=>0,'Izin'=>0,'Alpha'=>0,'total'=>0];
                    $pct = $r['total'] > 0 ? round($r['Hadir'] / $r['total'] * 100, 1) : 0;
                    $pctColor = $pct >= 75 ? '#16A34A' : ($pct >= 50 ? '#D97706' : '#DC2626');
                @endphp
                <tr>
                    <td style="color:var(--text-muted);">{{ $i+1 }}</td>
                    <td style="font-weight:500;">{{ $student->nama_lengkap }}</td>
                    <td style="text-align:center;font-weight:700;color:#16A34A;">{{ $r['Hadir'] }}</td>
                    <td style="text-align:center;font-weight:700;color:#D97706;">{{ $r['Sakit'] }}</td>
                    <td style="text-align:center;font-weight:700;color:#2563EB;">{{ $r['Izin'] }}</td>
                    <td style="text-align:center;font-weight:700;color:#DC2626;">{{ $r['Alpha'] }}</td>
                    <td style="text-align:center;color:var(--text-muted);">{{ $r['total'] }}</td>
                    <td style="text-align:center;">
                        <span style="font-weight:700;color:{{ $pctColor }};">{{ $pct }}%</span>
                        <div style="height:4px;background:var(--border);border-radius:2px;margin-top:3px;">
                            <div style="height:4px;background:{{ $pctColor }};border-radius:2px;width:{{ $pct }}%;"></div>
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
    <div style="text-align:center;padding:2.5rem;color:var(--text-muted);">
        <div style="font-size:2.5rem;margin-bottom:.5rem;">📊</div>
        <p>Pilih kelas dan bulan untuk melihat rekap kehadiran.</p>
    </div>
</div>
@endif

@endsection
