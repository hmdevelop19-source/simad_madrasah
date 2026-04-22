@extends('layouts.app')
@section('title', 'Presensi')
@section('page-title', 'Presensi')

@section('content')

{{-- Filter bar --}}
<div class="card" style="margin-bottom:1rem;">
    <div style="padding:1rem 1.25rem;">
        <form method="GET" style="display:flex;flex-wrap:wrap;gap:.75rem;align-items:flex-end;">
            <div class="form-group" style="margin:0;flex:1;min-width:180px;">
                <label class="form-label" style="font-size:.78rem;">Kelas</label>
                <select name="class_id" class="form-control" onchange="this.form.submit()">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($classrooms as $cl)
                    <option value="{{ $cl->id }}" {{ $classId == $cl->id ? 'selected' : '' }}>
                        [{{ $cl->gradeLevel?->educationLevel?->kode }}] {{ $cl->nama_kelas }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="margin:0;flex:1;min-width:160px;">
                <label class="form-label" style="font-size:.78rem;">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="{{ $tanggal }}" onchange="this.form.submit()">
            </div>
            <a href="{{ route('attendances.recap', ['class_id' => $classId]) }}"
               class="btn btn-outline btn-sm" style="white-space:nowrap;">📊 Rekap Bulanan</a>
        </form>
    </div>
</div>

@if($classId && $students->isEmpty())
<div class="card">
    <div style="text-align:center;padding:2.5rem;color:var(--text-muted);">
        <div style="font-size:2.5rem;margin-bottom:.5rem;">🏫</div>
        <p>Belum ada santri yang ditempatkan di kelas ini untuk tahun ajaran aktif.</p>
        <a href="{{ route('student-placements.index') }}" class="btn btn-outline btn-sm">Kelola Penempatan</a>
    </div>
</div>
@elseif(!$classId)
<div class="card">
    <div style="text-align:center;padding:2.5rem;color:var(--text-muted);">
        <div style="font-size:2.5rem;margin-bottom:.5rem;">✅</div>
        <p>Pilih kelas dan tanggal untuk mencatat presensi harian.</p>
    </div>
</div>
@else

{{-- Header info --}}
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.75rem;flex-wrap:wrap;gap:.5rem;">
    <div>
        <h3 style="font-size:.95rem;font-weight:700;margin:0;">
            [{{ $classroom?->gradeLevel?->educationLevel?->kode }}] {{ $classroom?->nama_kelas }}
        </h3>
        <span style="font-size:.8rem;color:var(--text-muted);">
            📅 {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }} &middot; {{ $students->count() }} santri
        </span>
    </div>
    {{-- Tombol cepat --}}
    <div style="display:flex;gap:.4rem;">
        <button type="button" onclick="setAll('Hadir')" class="btn btn-sm" style="background:#DCFCE7;color:#166534;border:1px solid #BBF7D0;font-size:.75rem;">✅ Hadir Semua</button>
        <button type="button" onclick="setAll('Alpha')" class="btn btn-sm" style="background:#FEE2E2;color:#991B1B;border:1px solid #FECACA;font-size:.75rem;">❌ Alpha Semua</button>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success" style="margin-bottom:.75rem;">{{ session('success') }}</div>
@endif

<form action="{{ route('attendances.store') }}" method="POST">
    @csrf
    <input type="hidden" name="class_id" value="{{ $classId }}">
    <input type="hidden" name="tanggal" value="{{ $tanggal }}">

    <div class="card">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="width:2.5rem;">#</th>
                        <th>Nama Santri</th>
                        @foreach(\App\Models\Attendance::STATUSES as $s)
                        <th style="text-align:center;width:80px;">{{ $s }}</th>
                        @endforeach
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $i => $student)
                    @php $att = $existing->get($student->id); $cur = $att?->status ?? 'Hadir'; @endphp
                    <tr id="row-{{ $student->id }}" class="att-row" data-status="{{ $cur }}">
                        <td style="color:var(--text-muted);">{{ $i+1 }}</td>
                        <td style="font-weight:500;">{{ $student->nama_lengkap }}</td>
                        @foreach(\App\Models\Attendance::STATUSES as $s)
                        @php
                            $colors = ['Hadir'=>'#16A34A','Sakit'=>'#D97706','Izin'=>'#2563EB','Alpha'=>'#DC2626'];
                        @endphp
                        <td style="text-align:center;">
                            <label style="cursor:pointer;display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:50%;border:2px solid {{ $colors[$s] }}33;transition:all .15s;"
                                   id="lbl-{{ $student->id }}-{{ $s }}">
                                <input type="radio"
                                       name="statuses[{{ $student->id }}]"
                                       value="{{ $s }}"
                                       {{ $cur === $s ? 'checked' : '' }}
                                       onchange="updateRow({{ $student->id }}, '{{ $s }}')"
                                       style="position:absolute;opacity:0;width:0;height:0;">
                                <span style="width:14px;height:14px;border-radius:50%;background:{{ $cur === $s ? $colors[$s] : 'transparent' }};"
                                      id="dot-{{ $student->id }}-{{ $s }}"></span>
                            </label>
                        </td>
                        @endforeach
                        <td>
                            <input type="text" name="keterangans[{{ $student->id }}]"
                                   value="{{ $att?->keterangan }}"
                                   placeholder="Opsional..."
                                   class="form-control" style="font-size:.78rem;padding:.3rem .6rem;">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding:.85rem 1.25rem;display:flex;justify-content:flex-end;border-top:1px solid var(--border);">
            <button type="submit" class="btn btn-primary">💾 Simpan Presensi</button>
        </div>
    </div>
</form>
@endif

@push('scripts')
<script>
const colors = { Hadir:'#16A34A', Sakit:'#D97706', Izin:'#2563EB', Alpha:'#DC2626' };
const rowBg  = { Hadir:'', Sakit:'#FFFBEB', Izin:'#EFF6FF', Alpha:'#FFF1F2' };

function updateRow(studentId, status) {
    // Update semua dots
    ['Hadir','Sakit','Izin','Alpha'].forEach(s => {
        const dot = document.getElementById('dot-'+studentId+'-'+s);
        dot.style.background = s === status ? colors[s] : 'transparent';
    });
    // Row bg
    const row = document.getElementById('row-'+studentId);
    row.style.background = rowBg[status] || '';
}

function setAll(status) {
    document.querySelectorAll('.att-row').forEach(row => {
        const sid = row.id.replace('row-','');
        const radio = row.querySelector('input[value="'+status+'"]');
        if (radio) { radio.checked = true; updateRow(sid, status); }
    });
}

// Init row colors on load
document.querySelectorAll('.att-row').forEach(row => {
    const sid = row.id.replace('row-','');
    const status = row.dataset.status;
    if (status) {
        updateRow(sid, status);
    }
});
</script>
@endpush

@endsection
