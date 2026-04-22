@extends('layouts.app')
@section('title', 'Profil Santri')
@section('page-title', 'Profil Santri')
@section('breadcrumb') <a href="{{ route('students.index') }}">Data Santri</a> <span>›</span> {{ $student->nama_lengkap }} @endsection

@section('content')
@php $statusColors = ['Aktif'=>'#059669','Lulus'=>'#2563EB','Mutasi'=>'#D97706','Keluar'=>'#94A3B8']; @endphp

{{-- Header Profil --}}
<div style="background:linear-gradient(135deg,#4F46E5,#3730A3);border-radius:16px;padding:1.5rem 2rem;color:white;margin-bottom:1.5rem;display:flex;align-items:center;justify-content:space-between">
    <div>
        <div style="font-size:.8rem;opacity:.7;margin-bottom:.25rem;">Data Santri</div>
        <div style="font-size:1.5rem;font-weight:700;">{{ $student->nama_lengkap }}</div>
        <div style="margin-top:.5rem;display:flex;gap:.5rem;flex-wrap:wrap;">
            <span style="background:rgba(255,255,255,.2);padding:.25rem .75rem;border-radius:20px;font-size:.8rem;">{{ $student->educationLevel?->nama ?? '–' }}</span>
            <span style="background:rgba(255,255,255,.15);padding:.25rem .75rem;border-radius:20px;font-size:.8rem;color:{{ $statusColors[$student->status_aktif] ?? '#fff' }};">{{ $student->status_aktif }}</span>
        </div>
    </div>
    <div style="font-size:4rem;opacity:.2;">🧑‍🎓</div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">

    {{-- Identitas --}}
    <div class="card">
        <div class="card-header"><span class="card-title">📋 Identitas</span></div>
        <div class="card-body" style="display:grid;gap:.75rem;">
            @foreach([
                ['NIK', $student->nik],
                ['No. KK', $student->no_kk],
                ['NISN', $student->nisn ?? '–'],
                ['Tempat Lahir', $student->tempat_lahir],
                ['Tanggal Lahir', $student->tanggal_lahir?->format('d M Y')],
                ['Jenis Kelamin', $student->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'],
            ] as [$label, $val])
            <div style="display:flex;justify-content:space-between;font-size:.875rem;border-bottom:1px dashed #F1F5F9;padding-bottom:.5rem;">
                <span style="color:var(--text-muted);">{{ $label }}</span>
                <span style="font-weight:500;">{{ $val }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Wali --}}
    <div class="card">
        <div class="card-header"><span class="card-title">👨‍👩‍👦 Wali Santri</span></div>
        <div class="card-body">
            @if($student->wali)
                @foreach([
                    ['Nama', $student->wali->nama_lengkap],
                    ['Hubungan', $student->wali->hubungan_keluarga],
                    ['No. WhatsApp', $student->wali->no_whatsapp],
                    ['Pekerjaan', $student->wali->pekerjaan],
                ] as [$label, $val])
                <div style="display:flex;justify-content:space-between;font-size:.875rem;border-bottom:1px dashed #F1F5F9;padding-bottom:.5rem;margin-bottom:.5rem;">
                    <span style="color:var(--text-muted);">{{ $label }}</span>
                    <span style="font-weight:500;">{{ $val }}</span>
                </div>
                @endforeach
            @else
                <p style="color:var(--text-muted);font-size:.875rem;">Belum ada wali yang terdaftar.</p>
            @endif
        </div>
    </div>
</div>

{{-- Riwayat Kelas --}}
<div class="card" style="margin-top:1.5rem;">
    <div class="card-header"><span class="card-title">📅 Riwayat Kelas</span></div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Tahun Ajaran</th><th>Kelas</th><th>Status Kenaikan</th></tr></thead>
            <tbody>
                @forelse($student->histories->sortByDesc('id') as $history)
                <tr>
                    <td>{{ $history->academicYear?->nama }} — {{ $history->academicYear?->periode }}</td>
                    <td>{{ $history->classroom?->nama_kelas ?? '–' }}</td>
                    <td><span class="badge badge-info">{{ $history->status_kenaikan ?? '–' }}</span></td>
                </tr>
                @empty
                <tr><td colspan="3" style="text-align:center;color:var(--text-muted);padding:1.5rem;">Belum ada riwayat kelas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div style="display:flex;gap:.75rem;margin-top:1.5rem;">
    @can('edit-santri')
    <a href="{{ route('students.edit', $student) }}" class="btn btn-primary">✏️ Edit Data</a>
    @endcan
    <a href="{{ route('students.index') }}" class="btn btn-outline">← Kembali</a>
</div>
@endsection
