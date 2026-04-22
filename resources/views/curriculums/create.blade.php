@extends('layouts.app')
@section('title', 'Pemetaan Kurikulum')
@section('page-title', 'Konfigurasi Pemetaan Kurikulum')

@section('content')

<div style="display: grid; grid-template-columns: 1fr 380px; gap: 2rem; align-items: start;">

    {{-- ══ LEFT PANEL: MAIN MAPPING FORM ══ --}}
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <form action="{{ route('curriculums.store') }}" method="POST" id="curriculumForm">
            @csrf

            @if($errors->any() && !$errors->has('subject_ids'))
                <div style="margin-bottom: 1.5rem; padding: 1rem 1.5rem; background: #FEF2F2; border: 1px solid #FEE2E2; border-radius: 12px; color: #DC2626; font-size: 0.85rem; font-weight: 600;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 4px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                        Terjadi kesalahan validasi:
                    </div>
                    @foreach($errors->all() as $error)
                        <div style="padding-left: 1.5rem;">• {{ $error }}</div>
                    @endforeach
                </div>
            @endif

            {{-- Step 1: Context Configuration --}}
            <div class="card" style="border-radius: 20px; border: 1px solid var(--border); box-shadow: var(--shadow-sm); margin-bottom: 2rem; overflow: hidden;">
                <div style="background: #F9FAFB; padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 32px; height: 32px; background: var(--primary); color: white; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 0.8rem;">1</div>
                    <div style="font-weight: 800; color: var(--primary); font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.5px;">Konfigurasi Dasar Kurikulum</div>
                </div>
                <div style="padding: 2rem;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                        <div>
                            <label style="display: block; font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">Tahun Ajaran <span style="color: var(--danger);">*</span></label>
                            <div style="position: relative;">
                                <select name="academic_year_id" class="form-control" style="border-radius: 12px; height: 48px; padding-left: 2.75rem; font-weight: 700; border: 1.5px solid var(--border);" required>
                                    <option value="">-- Pilih Tahun --</option>
                                    @foreach($academicYears as $year)
                                    <option value="{{ $year->id }}" {{ old('academic_year_id', $year->is_active ? $year->id : '') == $year->id ? 'selected' : '' }}>
                                        {{ $year->nama }} — {{ $year->periode }}{{ $year->is_active ? ' (AKTIF)' : '' }}
                                    </option>
                                    @endforeach
                                </select>
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--primary);"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                            </div>
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">Tingkatan Kelas <span style="color: var(--danger);">*</span></label>
                            <div style="position: relative;">
                                <select name="grade_level_id" class="form-control" style="border-radius: 12px; height: 48px; padding-left: 2.75rem; font-weight: 700; border: 1.5px solid var(--border);" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach($gradeLevels as $gl)
                                    <option value="{{ $gl->id }}" {{ old('grade_level_id') == $gl->id ? 'selected' : '' }}>
                                        [{{ $gl->educationLevel->kode }}] {{ $gl->nama_tingkat }}
                                    </option>
                                    @endforeach
                                </select>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--primary);"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                            </div>
                        </div>
                    </div>
                    <div style="background: var(--bg-secondary); padding: 1.5rem; border-radius: 16px; display: flex; align-items: center; gap: 1.5rem; border: 1px dashed var(--border);">
                        <div style="min-width: 140px;">
                            <label style="display: block; font-size: 0.75rem; font-weight: 900; color: var(--primary); text-transform: uppercase; margin-bottom: 6px;">KKM Default <span style="color: var(--danger);">*</span></label>
                            <input type="number" name="kkm_default" value="{{ old('kkm_default', 70) }}" min="0" max="100" class="form-control" style="height: 48px; border-radius: 12px; font-weight: 900; font-size: 1.25rem; text-align: center; border: 1.5px solid var(--border);" required>
                        </div>
                        <p style="font-size: 0.8rem; color: var(--text-muted); line-height: 1.5; margin: 0;"><strong>Catatan Penting:</strong> Nilai ini akan diterapkan ke seluruh mata pelajaran yang dipilih di bawah. Anda dapat melakukan penyesuaian KKM secara spesifik per mapel melalui menu Edit setelah data disimpan.</p>
                    </div>
                </div>
            </div>

            {{-- Step 2: Subject Selection Grid --}}
            <div class="card" style="border-radius: 20px; border: 1px solid var(--border); box-shadow: var(--shadow-sm); overflow: hidden;">
                <div style="background: #F9FAFB; padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 32px; height: 32px; background: var(--primary); color: white; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 0.8rem;">2</div>
                        <div style="font-weight: 800; color: var(--primary); font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.5px;">Pilih Mata Pelajaran</div>
                    </div>
                    <div style="display: flex; gap: 0.6rem;">
                        <button type="button" onclick="selectAll(true)" class="btn btn-outline" style="border-radius: 10px; height: 34px; padding: 0 1rem; font-size: 0.7rem; font-weight: 800; display: flex; align-items: center; gap: 0.4rem; background: white;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 11 3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                            SEMUA
                        </button>
                        <button type="button" onclick="selectAll(false)" class="btn btn-outline" style="border-radius: 10px; height: 34px; padding: 0 1rem; font-size: 0.7rem; font-weight: 800; display: flex; align-items: center; gap: 0.4rem; background: white;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><line x1="9" x2="15" y1="9" y2="15"/><line x1="15" x2="9" y1="9" y2="15"/></svg>
                            BATAL
                        </button>
                    </div>
                </div>

                @if($errors->has('subject_ids'))
                    <div style="padding: 0.75rem 1.5rem; background: #FEF2F2; color: #DC2626; font-size: 0.8rem; font-weight: 700; border-bottom: 1px solid #FEE2E2;">
                        ⚠️ Harap pilih minimal satu mata pelajaran untuk dipetakan.
                    </div>
                @endif

                <div style="padding: 2rem; display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 1rem;">
                    @forelse($subjects as $subject)
                    <label id="lbl-{{ $subject->id }}" class="subject-tile" style="display: flex; align-items: center; padding: 1.15rem 1.25rem; border: 2px solid var(--border); border-radius: 16px; cursor: pointer; transition: 0.2s; position: relative; gap: 1rem; overflow: hidden;">
                        <input type="checkbox" name="subject_ids[]" value="{{ $subject->id }}"
                            {{ in_array($subject->id, old('subject_ids', [])) ? 'checked' : '' }}
                            onchange="updateLabel(this)"
                            style="width: 22px; height: 22px; accent-color: var(--primary); cursor: pointer; z-index: 2;">
                        <div style="z-index: 2;">
                            <span style="font-family: 'JetBrains Mono', monospace; font-size: 0.7rem; background: var(--bg-secondary); color: var(--primary); padding: 0.2rem 0.5rem; border-radius: 6px; font-weight: 800; border: 1px solid var(--border); margin-bottom: 4px; display: inline-block;">{{ $subject->kode_mapel }}</span>
                            <div style="font-size: 0.85rem; font-weight: 700; color: var(--text); line-height: 1.2;">{{ $subject->nama_mapel }}</div>
                        </div>
                    </label>
                    @empty
                    <div style="grid-column: 1/-1; text-align: center; padding: 4rem 2rem; color: var(--text-muted);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.3; margin-bottom: 1rem;"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/><path d="M8 7h6"/><path d="M8 11h8"/></svg>
                        <div style="font-weight: 700; margin-bottom: 4px;">Katalog Mapel Belum Tersedia</div>
                        <p style="font-size: 0.8rem;"><a href="{{ route('subjects.create') }}" style="color: var(--primary); font-weight: 800;">Klik di sini</a> untuk menambah mata pelajaran baru.</p>
                    </div>
                    @endforelse
                </div>

                <div style="padding: 1.5rem 2rem; border-top: 1px solid var(--border); background: #F9FAFB; display: flex; justify-content: flex-end; gap: 1rem;">
                    <a href="{{ route('curriculums.index') }}" class="btn btn-outline" style="border-radius: 30px; height: 48px; padding: 0 2rem; display: flex; align-items: center; justify-content: center; font-weight: 800; background: white;">BATAL</a>
                    <button type="submit" class="btn btn-primary" style="border-radius: 30px; height: 48px; padding: 0 2.5rem; background: var(--primary); color: white; display: flex; align-items: center; gap: 0.6rem; font-weight: 800; box-shadow: var(--shadow-lg);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        SIMPAN KURIKULUM
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- ══ RIGHT PANEL: DUPLICATE WIZARD ══ --}}
    <div style="position: sticky; top: 1.5rem;">
        <div class="card" style="border-radius: 20px; border: 1px solid var(--border); box-shadow: var(--shadow-lg); overflow: hidden;">
            <div style="background: var(--primary); padding: 1.75rem 1.5rem; position: relative;">
                <h3 style="font-size: 1.15rem; font-weight: 900; color: white; margin: 0; display: flex; align-items: center; gap: 0.6rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                    DUPLIKAT KURIKULUM
                </h3>
                <p style="font-size: 0.75rem; color: rgba(255,255,255,0.7); margin-top: 6px; line-height: 1.4;">Salin seluruh mata pelajaran dan KKM dari kelas yang sudah ada ke tahun ajaran baru.</p>
                <div style="position: absolute; right: 1rem; top: 50%; translate: 0 -50%; opacity: 0.1;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="2" y="2" rx="2"/><rect width="18" height="18" x="4" y="4" rx="2"/></svg>
                </div>
            </div>
            <div style="padding: 2rem 1.5rem;">
                <form action="{{ route('curriculums.duplicate') }}" method="POST">
                    @csrf
                    
                    {{-- SOURCE SECTION --}}
                    <div style="margin-bottom: 2rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                            <span style="font-size: 0.7rem; font-weight: 900; color: var(--primary); text-transform: uppercase; letter-spacing: 1px;">📤 Sumber Data</span>
                            <div style="flex: 1; height: 1px; background: var(--border);"></div>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label style="display: block; font-size: 0.7rem; font-weight: 700; color: var(--text-muted); margin-bottom: 4px;">Tahun Pelajaran</label>
                                <select name="src_academic_year_id" class="form-control" style="border-radius: 10px; height: 42px; font-weight: 700; border: 1.5px solid var(--border);" required>
                                    <option value="">-- Pilih --</option>
                                    @foreach($academicYears as $year)
                                    <option value="{{ $year->id }}">{{ $year->nama }} &middot; {{ $year->periode }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <label style="display: block; font-size: 0.7rem; font-weight: 700; color: var(--text-muted); margin-bottom: 4px;">Pilih Kelas</label>
                                <select name="src_grade_level_id" class="form-control" style="border-radius: 10px; height: 42px; font-weight: 700; border: 1.5px solid var(--border);" required>
                                    <option value="">-- Pilih --</option>
                                    @foreach($gradeLevels as $gl)
                                    <option value="{{ $gl->id }}">[{{ $gl->educationLevel->kode }}] {{ $gl->nama_tingkat }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div style="position: relative; height: 30px; display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem;">
                        <div style="position: absolute; left: 0; right: 0; height: 1px; background: var(--border);"></div>
                        <div style="width: 34px; height: 34px; background: var(--bg-secondary); border: 2px solid var(--border); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--primary); z-index: 2;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="m19 12-7 7-7-7"/></svg>
                        </div>
                    </div>

                    {{-- TARGET SECTION --}}
                    <div style="margin-bottom: 2rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                            <span style="font-size: 0.7rem; font-weight: 900; color: var(--highlight); text-transform: uppercase; letter-spacing: 1px;">📥 Tujuan Salinan</span>
                            <div style="flex: 1; height: 1px; background: var(--border);"></div>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label style="display: block; font-size: 0.7rem; font-weight: 700; color: var(--text-muted); margin-bottom: 4px;">Tahun Pelajaran</label>
                                <select name="dst_academic_year_id" class="form-control" style="border-radius: 10px; height: 42px; font-weight: 700; border: 1.5px solid var(--border);" required>
                                    <option value="">-- Pilih --</option>
                                    @foreach($academicYears as $year)
                                    <option value="{{ $year->id }}" {{ $year->is_active ? 'selected' : '' }}>
                                        {{ $year->nama }} &middot; {{ $year->periode }}{{ $year->is_active ? ' (Target Aktif)' : '' }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <label style="display: block; font-size: 0.7rem; font-weight: 700; color: var(--text-muted); margin-bottom: 4px;">Salin Ke Kelas</label>
                                <select name="dst_grade_level_id" class="form-control" style="border-radius: 10px; height: 42px; font-weight: 700; border: 1.5px solid var(--border);" required>
                                    <option value="">-- Pilih --</option>
                                    @foreach($gradeLevels as $gl)
                                    <option value="{{ $gl->id }}">[{{ $gl->educationLevel->kode }}] {{ $gl->nama_tingkat }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; border-radius: 12px; height: 50px; font-weight: 900; background: var(--primary); display: flex; align-items: center; justify-content: center; gap: 1rem; box-shadow: var(--shadow-sm);"
                        onclick="return confirm('Konfirmasi: Seluruh data kurikulum pada kelas tujuan akan diperbarui dengan data dari sumber. Lanjutkan?')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/><path d="M12 21h-9M12 3h-9M21 21h-2M21 3h-2"/></svg>
                        PROSES DUPLIKAT
                    </button>
                    <p style="text-align: center; font-size: 0.65rem; color: var(--text-muted); margin-top: 1rem; line-height: 1.4;">Tindakan ini akan mengosongkan data lama di kelas tujuan dan menggantinya dengan data baru.</p>
                </form>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
function selectAll(checked) {
    document.querySelectorAll('#curriculumForm input[type="checkbox"]').forEach(cb => {
        cb.checked = checked; updateLabel(cb);
    });
}
function updateLabel(cb) {
    const lbl = document.getElementById('lbl-' + cb.value);
    if (!lbl) return;
    if (cb.checked) {
        lbl.style.borderColor = 'var(--primary)';
        lbl.style.background  = '#F0F9FF';
        lbl.style.boxShadow   = 'var(--shadow-sm)';
    } else {
        lbl.style.borderColor = 'var(--border)';
        lbl.style.background  = 'white';
        lbl.style.boxShadow   = 'none';
    }
}
document.querySelectorAll('#curriculumForm input[type="checkbox"]:checked').forEach(updateLabel);
</script>
@endpush

<style>
    .subject-tile:hover {
        border-color: var(--primary) !important;
        background: #F8FAFC !important;
    }
    .form-control:focus {
        border-color: var(--primary) !important;
        box-shadow: 0 0 0 3px rgba(0, 0, 82, 0.05);
    }
</style>

@endsection
