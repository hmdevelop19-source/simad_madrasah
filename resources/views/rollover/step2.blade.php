@extends('layouts.app')
@section('title', 'Rollover — Step 2')
@section('page-title', '📋 Step 2: Tetapkan Status Kenaikan')
@section('breadcrumb') <a href="{{ route('rollover.index') }}">Year-End Rollover</a> › Step 2 @endsection

@section('content')

{{-- Step Indicator --}}
<div style="display:flex;gap:.5rem;align-items:center;margin-bottom:1.5rem;">
    <span style="background:var(--success);color:white;width:28px;height:28px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem;">✓</span>
    <span style="height:2px;width:60px;background:var(--primary);"></span>
    <span style="background:var(--primary);color:white;width:28px;height:28px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem;">2</span>
    <span style="height:2px;width:60px;background:var(--border);"></span>
    <span style="background:var(--border);color:var(--text-muted);width:28px;height:28px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem;">3</span>
</div>

@if($belumDitempatkan > 0)
<div class="alert alert-warning">
    ⚠️ <strong>{{ $belumDitempatkan }} santri belum ditempatkan</strong> sehingga tidak muncul di tabel ini.
    <a href="{{ route('student-placements.index', ['status'=>'belum']) }}" style="font-weight:600;color:inherit;text-decoration:underline;">Tempatkan sekarang →</a>
</div>
@endif

<form action="{{ route('rollover.step2.process') }}" method="POST">
    @csrf

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
        <div>
            <h3 style="font-size:1rem;font-weight:600;">Tetapkan Status Kenaikan — <span style="color:var(--primary);">{{ $activeYear->nama }}</span></h3>
            <p style="font-size:.825rem;color:var(--text-muted);margin-top:.2rem;">Klik dropdown untuk mengubah status setiap santri. Klik "Simpan & Lanjut" jika sudah selesai.</p>
        </div>
        <div style="display:flex;gap:.5rem;">
            <a href="{{ route('rollover.step1') }}" class="btn btn-outline btn-sm">← Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan & Lanjut ke Step 3 →</button>
        </div>
    </div>

    @forelse($histories as $classId => $classSantri)
    @php $classroom = $classSantri->first()->classroom; @endphp
    <div class="card" style="margin-bottom:1rem;">
        <div class="card-header" style="background:var(--bg-secondary);">
            <span class="card-title">
                🏫 {{ $classroom?->nama_kelas ?? 'Kelas Tidak Diketahui' }}
                @if($classroom?->gradeLevel?->educationLevel)
                    <span class="badge badge-purple" style="margin-left:.4rem;">{{ $classroom->gradeLevel->educationLevel->kode }}</span>
                @endif
            </span>
            <div style="display:flex;gap:.4rem;">
                <button type="button" onclick="setClassStatus({{ $classId }}, 'Naik Kelas')" class="btn btn-outline btn-sm" style="font-size:.72rem;">Semua Naik ↑</button>
                <button type="button" onclick="setClassStatus({{ $classId }}, 'Lulus')" class="btn btn-outline btn-sm" style="font-size:.72rem;">Semua Lulus 🎓</button>
            </div>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nama Santri</th>
                        <th>Jenis Kelamin</th>
                        <th style="min-width:200px;">Status Kenaikan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($classSantri as $history)
                    <tr>
                        <td style="font-weight:600;">{{ $history->student?->nama_lengkap }}</td>
                        <td>{{ $history->student?->jenis_kelamin }}</td>
                        <td>
                            <select name="statuses[{{ $history->id }}]"
                                    data-class="{{ $classId }}"
                                    class="form-control class-status-{{ $classId }}"
                                    style="padding:.35rem .6rem;font-size:.82rem;"
                                    onchange="colorStatus(this)">
                                @foreach($statusOptions as $opt)
                                <option value="{{ $opt }}" {{ $history->status_kenaikan === $opt ? 'selected' : '' }}>
                                    {{ $opt }}
                                </option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @empty
    <div class="card">
        <div class="card-body" style="text-align:center;padding:3rem;color:var(--text-muted);">
            Belum ada santri yang ditempatkan di tahun ajaran ini.
            <br><a href="{{ route('student-placements.index') }}" style="color:var(--primary);">Lakukan penempatan terlebih dahulu →</a>
        </div>
    </div>
    @endforelse

    @if($histories->isNotEmpty())
    <div style="border-top:1px solid var(--border);padding-top:1rem;display:flex;justify-content:flex-end;gap:.75rem;">
        <a href="{{ route('rollover.step1') }}" class="btn btn-outline">← Kembali ke Step 1</a>
        <button type="submit" class="btn btn-primary" style="padding:.6rem 1.5rem;">Simpan & Lanjut ke Step 3 →</button>
    </div>
    @endif
</form>

@push('scripts')
<script>
function setClassStatus(classId, status) {
    document.querySelectorAll('.class-status-' + classId).forEach(function(el) {
        el.value = status;
        colorStatus(el);
    });
}
function colorStatus(el) {
    const colors = {
        'Naik Kelas':    '#16A34A',
        'Tinggal Kelas': '#D97706',
        'Lulus':         '#4F46E5',
        'Mutasi':        '#9333EA',
        'Belum Ditentukan': '#94A3B8',
    };
    el.style.color = colors[el.value] || '#1E293B';
    el.style.fontWeight = el.value === 'Belum Ditentukan' ? '400' : '600';
}
// Apply on load
document.querySelectorAll('select[name^="statuses"]').forEach(colorStatus);
</script>
@endpush
@endsection
