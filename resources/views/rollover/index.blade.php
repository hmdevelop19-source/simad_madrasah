@extends('layouts.app')
@section('title', 'Year-End Rollover')
@section('page-title', 'Year-End Rollover Wizard')

@section('content')

@if(!$activeYear)
<div class="card">
    <div class="card-body" style="text-align:center;padding:3rem;">
        <div style="font-size:3rem;margin-bottom:1rem;">⚠️</div>
        <h3 style="margin-bottom:.5rem;">Tidak Ada Tahun Ajaran Aktif</h3>
        <p style="color:var(--text-muted);margin-bottom:1.5rem;">Aktifkan tahun ajaran terlebih dahulu sebelum menjalankan rollover.</p>
        <a href="{{ route('academic-years.index') }}" class="btn btn-primary">🗓️ Kelola Tahun Ajaran</a>
    </div>
</div>
@else

{{-- Info tahun ajaran aktif --}}
<div style="background:linear-gradient(135deg,#4F46E5,#7C3AED);border-radius:var(--radius-lg);padding:1.5rem 2rem;margin-bottom:1.75rem;color:white;display:flex;justify-content:space-between;align-items:center;">
    <div>
        <div style="font-size:.75rem;opacity:.75;margin-bottom:.25rem;">TAHUN AJARAN AKTIF</div>
        <div style="font-size:1.5rem;font-weight:700;">{{ $activeYear->nama }}</div>
        <div style="font-size:.875rem;opacity:.85;">{{ $activeYear->periode }}</div>
    </div>
    <div style="text-align:right;">
        <div style="font-size:.75rem;opacity:.75;margin-bottom:.5rem;">RINGKASAN</div>
        <div style="display:flex;gap:1.5rem;">
            <div>
                <div style="font-size:1.25rem;font-weight:700;">{{ $totalSantriAktif }}</div>
                <div style="font-size:.7rem;opacity:.75;">Santri Aktif</div>
            </div>
            <div>
                <div style="font-size:1.25rem;font-weight:700;">{{ $totalDitempatkan }}</div>
                <div style="font-size:.7rem;opacity:.75;">Ditempatkan</div>
            </div>
            <div>
                <div style="font-size:1.25rem;font-weight:700;{{ $totalBelumDitentukan > 0 ? 'color:#FCD34D;' : '' }}">{{ $totalBelumDitentukan }}</div>
                <div style="font-size:.7rem;opacity:.75;">Belum Dinilai</div>
            </div>
            <div>
                <div style="font-size:1.25rem;font-weight:700;">{{ $totalKelas }}</div>
                <div style="font-size:.7rem;opacity:.75;">Kelas</div>
            </div>
        </div>
    </div>
</div>

{{-- Progress Wizard --}}
<div class="card" style="margin-bottom:1.5rem;">
    <div style="padding:1.5rem 2rem;">
        <div style="display:flex;align-items:center;gap:0;">
            @foreach([['🔍','Step 1','Review & Konfirmasi','step1'],['📋','Step 2','Tetapkan Status Kenaikan','step2'],['🚀','Step 3','Eksekusi Rollover','step3']] as $i => $step)
            <div style="flex:1;display:flex;flex-direction:column;align-items:center;position:relative;">
                @if($i > 0)
                <div style="position:absolute;top:20px;left:-50%;width:100%;height:2px;background:var(--border);z-index:0;"></div>
                @endif
                <div style="width:40px;height:40px;border-radius:50%;background:var(--primary-light);color:var(--primary);display:flex;align-items:center;justify-content:center;font-size:1.1rem;font-weight:700;z-index:1;position:relative;">{{ $step[0] }}</div>
                <div style="font-size:.75rem;font-weight:600;margin-top:.4rem;color:var(--text);">{{ $step[1] }}</div>
                <div style="font-size:.7rem;color:var(--text-muted);text-align:center;">{{ $step[2] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Peringatan + CTA --}}
<div class="card">
    <div class="card-body" style="padding:2rem;text-align:center;">
        <div style="max-width:560px;margin:0 auto;">
            <div style="background:#FFFBEB;border:1.5px solid #FCD34D;border-radius:var(--radius);padding:1rem 1.25rem;margin-bottom:1.75rem;text-align:left;">
                <div style="font-weight:600;color:#92400E;margin-bottom:.4rem;">⚠️ Perhatian Sebelum Rollover</div>
                <ul style="font-size:.85rem;color:#78350F;padding-left:1.25rem;line-height:1.8;">
                    <li>Pastikan semua santri sudah <strong>ditempatkan ke kelas</strong> untuk tahun ini</li>
                    <li>Pastikan <strong>input nilai</strong> semua mapel sudah selesai</li>
                    <li>Proses ini <strong>tidak dapat dibatalkan</strong> setelah dikonfirmasi</li>
                </ul>
            </div>
            @if($totalBelumDitentukan > 0)
            <div style="background:#FEF2F2;border:1.5px solid #FCA5A5;border-radius:var(--radius);padding:.875rem 1.25rem;margin-bottom:1.5rem;color:#991B1B;font-size:.85rem;text-align:left;">
                🚨 <strong>{{ $totalBelumDitentukan }} santri</strong> belum memiliki status kenaikan. Silakan tetapkan di <a href="{{ route('student-placements.index') }}" style="color:var(--danger);font-weight:600;">Penempatan Santri</a> terlebih dahulu, lalu kembali ke sini untuk melanjutkan Rollover.
            </div>
            @endif

            <a href="{{ route('rollover.step1') }}" class="btn btn-primary" style="padding:.75rem 2rem;font-size:1rem;">
                🚀 Mulai Year-End Rollover
            </a>
        </div>
    </div>
</div>
@endif
@endsection
