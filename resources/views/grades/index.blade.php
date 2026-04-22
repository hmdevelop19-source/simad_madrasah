@extends('layouts.app')
@section('title', 'Input Nilai')
@section('page-title', 'Input Nilai')

@section('content')

{{-- ══════════════════════════════════════════════════════════════
     FILTER BAR — Tahun Ajaran + Tingkatan + Mapel
     Komponen Nilai berlaku PER TINGKATAN dan disimpan di session,
     sehingga tidak perlu diatur ulang setiap ganti mapel.
     ══════════════════════════════════════════════════════════════ --}}
<div class="card" style="margin-bottom:1rem;">
    <div style="padding:1rem 1.25rem;display:flex;flex-direction:column;gap:.85rem;">

        {{-- Baris 1: Tahun Ajaran + Tingkatan + Mapel --}}
        <form method="GET" id="mainFilter" style="display:flex;flex-wrap:wrap;gap:.75rem;align-items:flex-end;">
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
            @if($gradeLevelId)
            <div class="form-group" style="margin:0;min-width:200px;flex:1;">
                <label class="form-label" style="font-size:.78rem;">Mata Pelajaran</label>
                <select name="curriculum_id" class="form-control" onchange="this.form.submit()">
                    <option value="">-- Pilih Mapel --</option>
                    @foreach($kurikulumList as $kur)
                    <option value="{{ $kur->id }}" {{ $curriculumId == $kur->id ? 'selected' : '' }}>
                        [{{ $kur->subject?->kode_mapel }}] {{ $kur->subject?->nama_mapel }}
                    </option>
                    @endforeach
                </select>
            </div>
            @endif
            <div style="display:flex;gap:.4rem;align-self:flex-end;">
                <a href="{{ route('grades.recap', ['academic_year_id'=>$yearId,'grade_level_id'=>$gradeLevelId]) }}"
                   class="btn btn-outline btn-sm" style="white-space:nowrap;">📊 Rekap</a>
            </div>
        </form>

        {{-- Baris 2: Komponen Nilai Aktif (hanya tampil jika tingkatan dipilih) --}}
        @if($gradeLevelId)
        <div style="border-top:1px solid var(--border);padding-top:.75rem;">
            <form method="GET" id="komponenForm">
                {{-- Pertahankan filter yang sudah dipilih --}}
                <input type="hidden" name="academic_year_id" value="{{ $yearId }}">
                <input type="hidden" name="academic_term_id" value="{{ $termId }}">
                <input type="hidden" name="grade_level_id"   value="{{ $gradeLevelId }}">
                @if($curriculumId)
                <input type="hidden" name="curriculum_id"    value="{{ $curriculumId }}">
                @endif

                <div style="display:flex;align-items:center;gap:.75rem;flex-wrap:wrap;">
                    <span style="font-size:.78rem;font-weight:700;color:var(--primary);white-space:nowrap;display:flex;align-items:center;gap:0.4rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                        Komponen Nilai:
                    </span>
                    <span style="font-size:.7rem;color:var(--text-secondary);">
                        (berlaku untuk semua mapel di tingkatan ini)
                    </span>

                    <div style="display:flex;flex-wrap:wrap;gap:.4rem;">
                        @foreach(\App\Models\Grade::JENIS_NILAI as $j)
                        <label class="komponen-pill {{ in_array($j, $komponenAktif) ? 'aktif' : '' }}"
                               id="lbl-{{ $j }}"
                               style="display:inline-flex;align-items:center;gap:.3rem;cursor:pointer;
                                      padding:.3rem .75rem;border-radius:999px;font-size:.78rem;font-weight:600;
                                      border:1.5px solid {{ in_array($j, $komponenAktif) ? 'var(--primary)' : 'var(--border)' }};
                                      background:{{ in_array($j, $komponenAktif) ? 'var(--primary)' : 'transparent' }};
                                      color:{{ in_array($j, $komponenAktif) ? 'white' : 'var(--text-secondary)' }};
                                      transition:all .15s;">
                            <input type="checkbox"
                                   name="komponen[]"
                                   value="{{ $j }}"
                                   {{ in_array($j, $komponenAktif) ? 'checked' : '' }}
                                   onchange="submitKomponen()"
                                   style="position:absolute;opacity:0;width:0;height:0;">
                            {{ $j }}
                        </label>
                        @endforeach
                    </div>

                    <span style="font-size:.72rem;color:var(--text-muted);padding:.25rem .6rem;background:var(--bg-secondary);border-radius:6px;">
                        {{ count($komponenAktif) }} aktif
                    </span>
                </div>
            </form>
        </div>
        @endif

    </div>
</div>

@if(session('success'))
<div class="alert alert-success" style="margin-bottom:.75rem;">{{ session('success') }}</div>
@endif

@if($curriculum && $gradeLevelId && !empty($komponenAktif))

{{-- Info header mapel + KKM --}}
<div style="display:flex;align-items:center;gap:1rem;margin-bottom:.75rem;flex-wrap:wrap;">
    <div style="background:var(--primary);color:white;padding:.4rem .85rem;border-radius:var(--radius);font-size:.8rem;font-weight:700;">
        {{ $curriculum->subject?->kode_mapel }}
    </div>
    <div>
        <span style="font-weight:700;font-size:.92rem;">{{ $curriculum->subject?->nama_mapel }}</span>
        <span style="color:var(--text-muted);font-size:.8rem;margin-left:.5rem;">
            — <strong>{{ implode(', ', $komponenAktif) }}</strong>
        </span>
    </div>
    <div style="margin-left:auto;display:flex;align-items:center;gap:.4rem;">
        <span style="font-size:.78rem;color:var(--text-muted);">KKM:</span>
        <span style="font-weight:800;font-size:1rem;color:var(--primary);">{{ $curriculum->kkm }}</span>
    </div>
</div>

@if($students->isEmpty())
<div class="card">
    <div style="text-align:center;padding:2.5rem;color:var(--text-muted);">
        <div style="font-size:2.5rem;margin-bottom:.5rem;">🏫</div>
        <p>Belum ada santri yang ditempatkan di tingkatan ini.</p>
    </div>
</div>
@else

<form action="{{ route('grades.store') }}" method="POST">
    @csrf
    <input type="hidden" name="curriculum_id"     value="{{ $curriculumId }}">
    <input type="hidden" name="academic_year_id"   value="{{ $yearId }}">
    <input type="hidden" name="academic_term_id"   value="{{ $termId }}">
    <input type="hidden" name="grade_level_id"     value="{{ $gradeLevelId }}">
    @foreach($komponenAktif as $k)
    <input type="hidden" name="komponen_aktif[]"  value="{{ $k }}">
    @endforeach

    <div class="card">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="width:2.5rem;">#</th>
                        <th>Nama Santri</th>
                        @foreach($komponenAktif as $j)
                        <th style="text-align:center;min-width:100px;">
                            <span style="font-weight:700;">{{ $j }}</span>
                            <span style="display:block;font-size:.65rem;color:var(--text-muted);font-weight:400;">(0–100)</span>
                        </th>
                        @endforeach
                        <th style="text-align:center;width:90px;">Rata-rata</th>
                        <th style="text-align:center;width:80px;">Status</th>
                        <th>Catatan Guru</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $i => $student)
                    @php
                        $kkm     = $curriculum->kkm;
                        $nilaiArr = [];
                        foreach($komponenAktif as $j) {
                            $nilaiArr[$j] = $existingMap[$j][$student->id] ?? null;
                        }
                        $filled   = array_filter($nilaiArr, fn($v) => $v !== null);
                        $rataRata = count($filled) > 0 ? round(array_sum($filled)/count($filled),1) : null;
                        $isBelow  = $rataRata !== null && $rataRata < $kkm;
                        $catatanExisting = null;
                        foreach($komponenAktif as $j) {
                            if(isset($existingRowMap[$j][$student->id])) {
                                $catatanExisting = $existingRowMap[$j][$student->id]->catatan_guru;
                                break;
                            }
                        }
                    @endphp
                    <tr id="row-{{ $student->id }}" style="{{ $isBelow ? 'background:#FFF1F2;' : '' }}">
                        <td style="color:var(--text-muted);">{{ $i+1 }}</td>
                        <td style="font-weight:500;">{{ $student->nama_lengkap }}</td>
                        @foreach($komponenAktif as $j)
                        @php $nVal = $nilaiArr[$j]; @endphp
                        <td style="text-align:center;">
                            <input type="number"
                                   name="nilais[{{ $j }}][{{ $student->id }}]"
                                   value="{{ $nVal }}"
                                   min="0" max="100" step="0.5"
                                   class="form-control nilai-input"
                                   data-student="{{ $student->id }}"
                                   data-kkm="{{ $kkm }}"
                                   style="text-align:center;font-weight:700;font-size:.9rem;padding:.3rem .5rem;{{ $nVal !== null && $nVal < $kkm ? 'border-color:#F87171;color:#DC2626;' : '' }}"
                                   oninput="recalcRow({{ $student->id }}, {{ $kkm }})">
                        </td>
                        @endforeach
                        <td style="text-align:center;font-weight:700;" id="rata-{{ $student->id }}">
                            <span style="color:{{ $rataRata !== null ? ($isBelow ? '#DC2626' : '#16A34A') : 'var(--text-muted)' }};">
                                {{ $rataRata ?? '–' }}
                            </span>
                        </td>
                        <td style="text-align:center;" id="status-{{ $student->id }}">
                            @if($rataRata !== null)
                            <span style="font-size:.72rem;font-weight:700;color:{{ $isBelow ? '#DC2626' : '#16A34A' }};">
                                {{ $isBelow ? '✗ Remedial' : '✓ Tuntas' }}
                            </span>
                            @else
                            <span style="color:var(--text-muted);font-size:.78rem;">–</span>
                            @endif
                        </td>
                        <td>
                            <input type="text" name="catatan[{{ $student->id }}]"
                                   value="{{ $catatanExisting }}"
                                   placeholder="Opsional..."
                                   class="form-control" style="font-size:.78rem;padding:.3rem .6rem;">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding:1.25rem 1.5rem;display:flex;justify-content:flex-end;gap:1rem;border-top:1px solid var(--border);background:var(--bg-secondary);">
            <span style="font-size:.85rem;color:var(--text-secondary);align-self:center;font-weight:500;">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:2px;"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                {{ $students->count() }} Santri dalam daftar
            </span>
            <button type="submit" class="btn btn-primary" style="background:var(--primary);border-radius:30px;padding:0.6rem 2rem;font-weight:700;box-shadow:var(--shadow);">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:4px;"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                SIMPAN NILAI
            </button>
        </div>
    </div>
</form>
@endif

@elseif($gradeLevelId && $curriculumId && empty($komponenAktif))
<div class="card">
    <div style="text-align:center;padding:2rem;color:var(--text-muted);">
        <div style="font-size:2.5rem;margin-bottom:.5rem;">⚙️</div>
        <p>Pilih minimal satu komponen nilai di atas untuk mulai input.</p>
    </div>
</div>
@else
<div class="card">
    <div style="text-align:center;padding:2.5rem;color:var(--text-muted);">
        <div style="font-size:2.5rem;margin-bottom:.5rem;">📝</div>
        <p>Pilih tingkatan kelas dan mata pelajaran untuk mulai input nilai.</p>
    </div>
</div>
@endif

@push('scripts')
<script>
// ─── Toggle pill komponen ──────────────────────────────────────
document.querySelectorAll('[id^="lbl-"]').forEach(lbl => {
    const cb = lbl.querySelector('input[type=checkbox]');
    cb.addEventListener('change', () => {
        if (cb.checked) {
            lbl.style.background  = 'var(--primary)';
            lbl.style.color       = 'white';
            lbl.style.borderColor = 'var(--primary)';
        } else {
            lbl.style.background  = 'transparent';
            lbl.style.color       = 'var(--text-secondary)';
            lbl.style.borderColor = 'var(--border)';
        }
    });
});

function submitKomponen() {
    document.getElementById('komponenForm').submit();
}

// ─── Hitung rata-rata realtime per baris ─────────────────────
function recalcRow(studentId, kkm) {
    const inputs = document.querySelectorAll(`.nilai-input[data-student="${studentId}"]`);
    let sum = 0, count = 0;
    inputs.forEach(inp => {
        const v = parseFloat(inp.value);
        if (!isNaN(v)) { sum += v; count++; }
        if (inp.value === '') {
            inp.style.borderColor = ''; inp.style.color = '';
        } else if (v < kkm) {
            inp.style.borderColor = '#F87171'; inp.style.color = '#DC2626';
        } else {
            inp.style.borderColor = '#86EFAC'; inp.style.color = '#16A34A';
        }
    });
    const rataEl   = document.getElementById('rata-' + studentId);
    const statusEl = document.getElementById('status-' + studentId);
    const row      = document.getElementById('row-' + studentId);

    if (count === 0) {
        rataEl.innerHTML   = '<span style="color:var(--text-muted);">–</span>';
        statusEl.innerHTML = '<span style="color:var(--text-muted);font-size:.78rem;">–</span>';
        row.style.background = '';
        return;
    }
    const avg   = Math.round(sum / count * 10) / 10;
    const color = avg < kkm ? '#DC2626' : '#16A34A';
    const label = avg < kkm ? '✗ Remedial' : '✓ Tuntas';
    rataEl.innerHTML   = `<span style="color:${color};">${avg}</span>`;
    statusEl.innerHTML = `<span style="font-size:.72rem;font-weight:700;color:${color};">${label}</span>`;
    row.style.background = avg < kkm ? '#FFF1F2' : '';
}
</script>
@endpush

@endsection
