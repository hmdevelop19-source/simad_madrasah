@extends('layouts.app')
@section('title', 'Profil Wali Santri')
@section('page-title', 'Profil Wali Santri')
@section('breadcrumb') <a href="{{ route('wali-santri.index') }}">Wali Santri</a> <span>›</span> {{ $waliSantri->nama_lengkap }} @endsection

@section('content')
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
    <div class="card">
        <div class="card-header"><span class="card-title">👤 Identitas Wali</span>
            @can('edit-wali')
            <a href="{{ route('wali-santri.edit', $waliSantri) }}" class="btn btn-outline btn-sm">✏️ Edit</a>
            @endcan
        </div>
        <div class="card-body" style="display:grid;gap:.75rem;">
            @php $fields = [
                ['Nama Lengkap', $waliSantri->nama_lengkap],
                ['NIK', $waliSantri->nik],
                ['Hubungan', $waliSantri->hubungan_keluarga],
                ['Pendidikan', $waliSantri->pendidikan_terakhir],
                ['Pekerjaan', $waliSantri->pekerjaan],
                ['Penghasilan', $waliSantri->penghasilan_bulanan],
                ['No. WhatsApp', $waliSantri->no_whatsapp],
            ]; @endphp
            @foreach($fields as [$label, $val])
            <div style="display:flex;justify-content:space-between;font-size:.875rem;border-bottom:1px dashed #F1F5F9;padding-bottom:.5rem;">
                <span style="color:var(--text-muted);">{{ $label }}</span>
                <span style="font-weight:500;">{{ $val }}</span>
            </div>
            @endforeach
            <div style="font-size:.875rem;border-bottom:1px dashed #F1F5F9;padding-bottom:.5rem;">
                <span style="color:var(--text-muted);display:block;margin-bottom:.25rem;">Alamat</span>
                <span>{{ $waliSantri->alamat_lengkap }}</span>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><span class="card-title">🧑‍🎓 Daftar Santri yang Diasuh</span></div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Nama Santri</th><th>Unit</th><th style="text-align:center;">Status</th></tr></thead>
                <tbody>
                    @forelse($waliSantri->students as $student)
                    <tr>
                        <td>
                            <a href="{{ route('students.show', $student) }}" style="color:var(--primary);text-decoration:none;font-weight:500;">{{ $student->nama_lengkap }}</a>
                        </td>
                        <td><span class="badge badge-purple">{{ $student->educationLevel?->kode ?? '–' }}</span></td>
                        <td style="text-align:center;"><span class="badge badge-{{ $student->status_aktif === 'Aktif' ? 'success' : 'gray' }}">{{ $student->status_aktif }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="3" style="text-align:center;color:var(--text-muted);padding:1.5rem;">Belum ada santri terdaftar.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div style="margin-top:1.5rem;">
    <a href="{{ route('wali-santri.index') }}" class="btn btn-outline">← Kembali</a>
</div>
@endsection
