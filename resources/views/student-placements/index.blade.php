@extends('layouts.app')
@section('title', 'Penempatan Santri')
@section('page-title', 'Distribusi Kelas')

@section('content')

{{-- ================================================================
     BANNER & STATS (HERITAGE LTE)
================================================================ --}}
<div style="background: linear-gradient(135deg, var(--primary) 0%, #1E1B4B 100%); border-radius: 20px; padding: 2rem; color: white; margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between; position: relative; overflow: hidden; box-shadow: var(--shadow-lg);">
    <div style="position:absolute; right: -50px; top: -50px; width: 200px; height: 200px; background: rgba(252, 213, 38, 0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 1;">
        <h2 style="font-size: 1.5rem; font-weight: 800; letter-spacing: -0.5px; margin-bottom: 0.5rem;">Pusat Penempatan Santri</h2>
        <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem; max-width: 500px;">
            Kelola distribusi santri ke rombongan belajar untuk periode <strong>{{ $activeYear->nama }}</strong>. 
            Gunakan fitur <em>Batch Assign</em> untuk mempercepat proses.
        </p>
    </div>

    <div style="display: flex; gap: 1.5rem; position: relative; z-index: 1;">
        <div style="text-align: center;">
            <div style="font-size: 1.75rem; font-weight: 800; color: var(--accent);">{{ $totalAktif }}</div>
            <div style="font-size: 0.7rem; color: rgba(255,255,255,0.6); text-transform: uppercase; letter-spacing: 1px; font-weight: 700;">Total Aktif</div>
        </div>
        <div style="width: 1px; height: 40px; background: rgba(255,255,255,0.1); align-self: center;"></div>
        <div style="text-align: center;">
            <div style="font-size: 1.75rem; font-weight: 800; color: var(--highlight);">{{ $totalDitempatkan }}</div>
            <div style="font-size: 0.7rem; color: rgba(255,255,255,0.6); text-transform: uppercase; letter-spacing: 1px; font-weight: 700;">Ditempatkan</div>
        </div>
        <div style="width: 1px; height: 40px; background: rgba(255,255,255,0.1); align-self: center;"></div>
        <div style="text-align: center;">
            <div style="font-size: 1.75rem; font-weight: 800; color: {{ $totalBelum > 0 ? '#F87171' : '#10B981' }};">{{ $totalBelum }}</div>
            <div style="font-size: 0.7rem; color: rgba(255,255,255,0.6); text-transform: uppercase; letter-spacing: 1px; font-weight: 700;">Belum Ok</div>
        </div>
    </div>
</div>

{{-- ================================================================
     UNIFIED FILTER STRIP
================================================================ --}}
<div style="background: white; border: 1px solid var(--border); border-radius: 16px; padding: 1.25rem; margin-bottom: 1.5rem; box-shadow: var(--shadow-sm); display: flex; flex-wrap: wrap; gap: 1rem; align-items: center;">
    <form method="GET" style="display: contents;">
        {{-- Custom Select Wrappers --}}
        <div style="min-width: 160px;">
            <label style="display: block; font-size: 0.65rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 4px;">Unit</label>
            <select name="education_level_id" class="form-control" onchange="this.form.submit()" style="border-radius: 10px; border-color: var(--border); font-weight: 600;">
                <option value="">Semua Unit</option>
                @foreach($educationLevels as $lvl)
                    <option value="{{ $lvl->id }}" {{ $levelFilter == $lvl->id ? 'selected' : '' }}>{{ $lvl->nama }}</option>
                @endforeach
            </select>
        </div>
        <div style="min-width: 180px;">
            <label style="display: block; font-size: 0.65rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 4px;">Kenaikan Tahun Lalu</label>
            <select name="kenaikan" class="form-control" onchange="this.form.submit()" style="border-radius: 10px; border-color: var(--border); font-weight: 600;">
                <option value="semua" {{ $kenaikanFilter === 'semua' ? 'selected' : '' }}>Semua Status</option>
                <option value="naik" {{ $kenaikanFilter === 'naik' ? 'selected' : '' }}>⬆️ Naik Kelas</option>
                <option value="tinggal" {{ $kenaikanFilter === 'tinggal' ? 'selected' : '' }}>↩️ Tinggal Kelas</option>
                <option value="lulus" {{ $kenaikanFilter === 'lulus' ? 'selected' : '' }}>🎓 Lulus</option>
                <option value="baru" {{ $kenaikanFilter === 'baru' ? 'selected' : '' }}>🆕 Santri Baru</option>
            </select>
        </div>
        <div style="min-width: 180px;">
            <label style="display: block; font-size: 0.65rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 4px;">Penempatan</label>
            <select name="status" class="form-control" onchange="this.form.submit()" style="border-radius: 10px; border-color: var(--border); font-weight: 600;">
                <option value="semua" {{ $statusFilter === 'semua' ? 'selected' : '' }}>Semua Santri</option>
                <option value="belum" {{ $statusFilter === 'belum' ? 'selected' : '' }}>🚨 Belum Ada Kelas</option>
                <option value="ditempatkan" {{ $statusFilter === 'ditempatkan' ? 'selected' : '' }}>✅ Sudah Ada Kelas</option>
            </select>
        </div>

        <div style="margin-left: auto; display: flex; gap: 0.5rem; align-self: flex-end;">
            <a href="{{ route('student-placements.index') }}" class="btn btn-outline" style="border-radius: 30px; padding: 0.55rem 1.25rem; font-weight: 600; font-size: 0.85rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                RESET
            </a>
        </div>
    </form>
</div>

@if(session('success'))
<div class="alert alert-success" style="border-radius: 12px; margin-bottom: 1.5rem; border: none; background: #ECFDF5; color: #065F46; font-weight: 600;">
    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
    {{ session('success') }}
</div>
@endif

{{-- ================================================================
     MAIN TABLE
================================================================ --}}
<form id="bulkActionForm" action="{{ route('student-placements.bulk') }}" method="POST">
    @csrf
    <div class="card" style="box-shadow: var(--shadow); border-radius: 20px; overflow: hidden;">
        <div class="table-wrap">
            <table style="border-collapse: separate; border-spacing: 0;">
                <thead>
                    <tr style="background: var(--bg-secondary);">
                        <th style="width: 50px; text-align: center; padding-left: 1.5rem;">
                            <input type="checkbox" id="selectAll" style="width: 16px; height: 16px;">
                        </th>
                        <th style="min-width: 250px;">Data Santri</th>
                        <th>Info Tahun Lalu</th>
                        <th>Penempatan Sekarang ({{ $activeYear->nama }})</th>
                        <th style="text-align: center; width: 120px; padding-right: 1.5rem;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                    @php
                        $history    = $student->histories->first();
                        $prevData   = $prevStatusMap[$student->id] ?? null;
                        $prevStatus = $prevData['status'] ?? null;
                        $prevKelas  = $prevData['kelas'] ?? null;
                        
                        $isPlaced = (bool)$history;
                    @endphp
                    <tr style="{{ !$isPlaced ? 'background: #fffdf2;' : '' }}">
                        <td style="text-align: center; padding-left: 1.5rem;">
                            <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" class="child-checkbox" style="width: 16px; height: 16px;">
                        </td>
                        <td style="padding: 1.25rem 1rem;">
                            <div style="font-weight: 700; color: var(--primary); font-size: 0.95rem;">{{ strtoupper($student->nama_lengkap) }}</div>
                            <div style="font-size: 0.725rem; color: var(--text-muted); margin-top: 2px; font-weight: 600;">{{ $student->educationLevel?->nama }} • NIS: {{ $student->nis ?? '-' }}</div>
                        </td>
                        <td>
                            @if($prevStatus)
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <span style="font-size: 0.8rem; font-weight: 700; color: var(--text);">{{ $prevStatus }}</span>
                                    <span style="font-size: 0.7rem; color: var(--text-muted); background: var(--bg-secondary); padding: 2px 6px; border-radius: 4px;">{{ $prevKelas ?? '?' }}</span>
                                </div>
                            @else
                                <span style="font-size: 0.75rem; font-weight: 600; color: var(--primary); background: rgba(0, 176, 251, 0.1); padding: 4px 10px; border-radius: 20px;">🆕 SANTRI BARU</span>
                            @endif
                        </td>
                        <td>
                            @if($isPlaced)
                                <div style="display: flex; align-items: center; gap: 0.6rem;">
                                    <div style="width: 32px; height: 32px; background: #EEF2FF; color: var(--primary); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.75rem;">{{ substr($history->classroom->nama_kelas, 0, 1) }}</div>
                                    <div>
                                        <div style="font-weight: 700; color: var(--success); font-size: 0.9rem;">{{ $history->classroom->nama_kelas }}</div>
                                        <div style="font-size: 0.65rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">{{ $history->classroom->gradeLevel?->nama_tingkat }}</div>
                                    </div>
                                </div>
                            @else
                                <span style="display: flex; align-items: center; gap: 0.4rem; color: #F87171; font-weight: 700; font-size: 0.75rem;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                                    BELUM ADA KELAS
                                </span>
                            @endif
                        </td>
                        <td style="text-align: center; padding-right: 1.5rem;">
                            @if($isPlaced)
                                <button type="button" onclick="confirmDeletePlacement('{{ route('student-placements.destroy', $history->id) }}')" class="btn btn-sm" style="background: #FEE2E2; color: #DC2626; border: none; padding: 0.4rem; border-radius: 8px;" title="Hapus Penempatan">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                </button>
                            @else
                                <button type="button" onclick="quickAssign({{ $student->id }}, '{{ $student->nama_lengkap }}')" class="btn btn-sm" style="background: var(--accent); color: var(--primary); border: none; padding: 0.4rem 0.8rem; border-radius: 30px; font-weight: 700; font-size: 0.75rem;">
                                    ASSIGN
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="text-align:center; padding: 5rem 0; color: var(--text-muted); font-size: 0.85rem;">Pencarian tidak membuahkan hasil. Silakan sesuaikan filter Anda.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding: 1rem 1.5rem; background: #fff; border-top: 1px solid var(--border);">
            {{ $students->links() }}
        </div>
    </div>

    {{-- ================================================================
         STICKY BATCH ACTION BAR
    ================================================================ --}}
    <div id="batchActionBar" style="display: none; position: fixed; bottom: 2rem; left: 50%; transform: translateX(-50%); background: white; padding: 1rem 2rem; border-radius: 50px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); border: 2px solid var(--primary); z-index: 1000; align-items: center; gap: 1.5rem; animation: slideUp 0.3s ease-out;">
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <div id="selectedCountBadge" style="width: 28px; height: 28px; background: var(--primary); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 800;">0</div>
            <span style="font-size: 0.85rem; font-weight: 700; color: var(--primary); white-space: nowrap;">SANTRI TERPILIH</span>
        </div>
        
        <div style="height: 24px; width: 1px; background: var(--border);"></div>

        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <select name="class_id" class="form-control" style="border-radius: 30px; padding: 0.4rem 1rem; font-size: 0.85rem; min-width: 200px; height: 38px; font-weight: 600;" required>
                <option value="">-- Assign ke Kelas --</option>
                @foreach($classrooms as $cls)
                    <option value="{{ $cls->id }}">{{ $cls->nama_kelas }} ({{ $cls->gradeLevel?->educationLevel?->kode }})</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary" style="border-radius: 30px; padding: 0.55rem 1.75rem; font-weight: 800; background: var(--primary); box-shadow: 0 4px 12px rgba(0, 0, 82, 0.2);">
                EKSEKUSI
            </button>
        </div>

        <button type="button" onclick="cancelSelection()" style="background: none; border: none; color: #F87171; font-weight: 700; font-size: 0.75rem; cursor: pointer; text-transform: uppercase;">
            BATAL
        </button>
    </div>
</form>

{{-- Delete Form (HIDDEN) --}}
<form id="deletePlacementForm" method="POST" style="display:none;">
    @csrf @method('DELETE')
</form>

{{-- Quick Assign Modal (HIDDEN) --}}
<div id="quickAssignModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 2000; align-items: center; justify-content: center;">
    <div style="background: white; padding: 2rem; border-radius: 20px; width: 400px; box-shadow: var(--shadow-lg);">
        <h3 style="font-size: 1.1rem; font-weight: 800; color: var(--primary); margin-bottom: 0.5rem;" id="qaStudentName">Penempatan Santri</h3>
        <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.5rem;">Pilih kelas tujuan untuk santri ini:</p>
        
        <form action="{{ route('student-placements.store') }}" method="POST">
            @csrf
            <input type="hidden" name="student_id" id="qaStudentId">
            <select name="class_id" class="form-control" style="margin-bottom: 1.5rem; border-radius: 12px; height: 45px; font-weight: 600;" required>
                <option value="">-- Pilih Kelas --</option>
                @foreach($classrooms as $cls)
                    <option value="{{ $cls->id }}">{{ $cls->nama_kelas }} ({{ $cls->gradeLevel?->educationLevel?->kode }})</option>
                @endforeach
            </select>
            <div style="display: flex; gap: 0.75rem;">
                <button type="button" onclick="document.getElementById('quickAssignModal').style.display='none'" class="btn btn-outline" style="flex:1; border-radius: 30px;">BATAL</button>
                <button type="submit" class="btn btn-primary" style="flex:2; border-radius: 30px; font-weight: 700;">KONFIRMASI</button>
            </div>
        </form>
    </div>
</div>

<style>
@keyframes slideUp {
    from { opacity: 0; transform: translate(-50%, 50px); }
    to { opacity: 1; transform: translate(-50%, 0); }
}
.sidebar-link.active { background: #EEF2FF; color: var(--primary); font-weight: 800; border-right: 4px solid var(--primary); }
tr:hover { background: #fcfcfc; }
</style>

@push('scripts')
<script>
const selectAll = document.getElementById('selectAll');
const checkboxes = document.querySelectorAll('.child-checkbox');
const batchBar = document.getElementById('batchActionBar');
const countBadge = document.getElementById('selectedCountBadge');

function toggleBatchBar() {
    const checkedCount = document.querySelectorAll('.child-checkbox:checked').length;
    if (checkedCount > 0) {
        batchBar.style.display = 'flex';
        countBadge.textContent = checkedCount;
    } else {
        batchBar.style.display = 'none';
        selectAll.checked = false;
    }
}

selectAll.addEventListener('change', () => {
    checkboxes.forEach(cb => cb.checked = selectAll.checked);
    toggleBatchBar();
});

checkboxes.forEach(cb => {
    cb.addEventListener('change', toggleBatchBar);
});

function cancelSelection() {
    checkboxes.forEach(cb => cb.checked = false);
    selectAll.checked = false;
    toggleBatchBar();
}

function confirmDeletePlacement(url) {
    if (confirm('Apakah Anda yakin ingin menghapus penempatan santri ini?')) {
        const form = document.getElementById('deletePlacementForm');
        form.action = url;
        form.submit();
    }
}

function quickAssign(id, name) {
    document.getElementById('qaStudentId').value = id;
    document.getElementById('qaStudentName').textContent = name;
    document.getElementById('quickAssignModal').style.display = 'flex';
}
</script>
@endpush
@endsection
