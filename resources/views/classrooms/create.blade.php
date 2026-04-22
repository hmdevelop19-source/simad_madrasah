@extends('layouts.app')
@section('title', 'Tambah Rombel')
@section('page-title', 'Pendaftaran Rombongan Belajar')

@section('content')

<div style="display: flex; justify-content: center; padding: 3rem 0;">
    <div class="card" style="width: 100%; max-width: 580px; border-radius: 24px; box-shadow: var(--shadow-lg); overflow: hidden; border: 1px solid var(--border);">
        
        {{-- CARD HEADER --}}
        <div style="background: var(--primary); padding: 2.25rem; color: white; position: relative;">
            <div style="display: flex; align-items: center; gap: 1rem; position: relative; z-index: 2;">
                <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.15); border-radius: 14px; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(255,255,255,0.25);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9Z"/><path d="m3 9 2.45-4.91A2 2 0 0 1 7.24 3h9.52a2 2 0 0 1 1.79 1.09L21 9"/><path d="M12 3v6"/></svg>
                </div>
                <div>
                    <h3 style="font-size: 1.25rem; font-weight: 800; margin: 0; letter-spacing: 0.5px;">Tambah Rombongan Belajar</h3>
                    <p style="font-size: 0.85rem; opacity: 0.75; margin-top: 4px;">Konfigurasi grup kelas dan penunjukan wali kelas aktif.</p>
                </div>
            </div>
            <div style="position: absolute; right: 2rem; top: 50%; transform: translateY(-50%); opacity: 0.1; pointer-events: none;">
                <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9Z"/><circle cx="12" cy="12" r="3"/></svg>
            </div>
        </div>

        {{-- FORM BODY --}}
        <div style="padding: 2.5rem;">
            <form action="{{ route('classrooms.store') }}" method="POST">
                @csrf

                {{-- STEP 1: GRADE & UNIT --}}
                <div style="margin-bottom: 2rem;">
                    <label style="display: block; font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">Tingkatan Kelas <span style="color: var(--danger);">*</span></label>
                    <div style="position: relative;">
                        <select name="grade_level_id" class="form-control @error('grade_level_id') is-invalid @enderror" style="height: 48px; border-radius: 12px; border: 1.5px solid var(--border); font-weight: 700; padding-left: 2.75rem;" required>
                            <option value="">-- Tentukan Tingkat --</option>
                            @foreach($gradeLevels as $gl)
                            <option value="{{ $gl->id }}" {{ old('grade_level_id') == $gl->id ? 'selected' : '' }}>
                                [{{ $gl->educationLevel->kode }}] {{ $gl->nama_tingkat }}
                            </option>
                            @endforeach
                        </select>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--primary);"><path d="m2 9 10-5 10 5-10 5Z"/><path d="M7 21a10 10 0 1 1 10 0"/></svg>
                    </div>
                    @error('grade_level_id')<span style="color: var(--danger); font-size: 0.7rem; font-weight: 700; margin-top: 4px; display: block;">{{ $message }}</span>@enderror
                </div>

                {{-- STEP 2: CLASSROOM NAME --}}
                <div style="margin-bottom: 2rem;">
                    <label style="display: block; font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">Nama Rombel <span style="color: var(--danger);">*</span></label>
                    <div style="position: relative;">
                        <input type="text" name="nama_kelas" value="{{ old('nama_kelas') }}" class="form-control @error('nama_kelas') is-invalid @enderror" placeholder="Contoh: 7-A, 8-B, RA-Al Fatihah" style="height: 48px; border-radius: 12px; border: 1.5px solid var(--border); padding-left: 2.75rem; font-weight: 900; font-size: 1.1rem; color: var(--primary);" required>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--primary);"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                    </div>
                    @error('nama_kelas')<span style="color: var(--danger); font-size: 0.7rem; font-weight: 700; margin-top: 4px; display: block;">{{ $message }}</span>@enderror
                    <p style="font-size: 0.7rem; color: var(--text-muted); margin-top: 8px;">Gunakan penamaan yang jelas dan spesifik (Maks 50 Karakter).</p>
                </div>

                {{-- STEP 3: ASSIGN WALI KELAS --}}
                <div style="background: #F8FAFC; padding: 1.75rem; border-radius: 20px; border: 1px solid var(--border); margin-bottom: 2.5rem;">
                    <div style="display: flex; align-items: flex-start; gap: 1rem; margin-bottom: 1.5rem;">
                        <div style="width: 40px; height: 40px; background: white; color: var(--primary); border-radius: 10px; display: flex; align-items: center; justify-content: center; box-shadow: var(--shadow-sm); border: 1px solid var(--border);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        </div>
                        <div>
                            <h4 style="font-size: 0.95rem; font-weight: 800; color: var(--primary); margin: 0;">Penugasan Wali Kelas</h4>
                            <p style="font-size: 0.75rem; color: var(--text-muted); margin: 0; line-height: 1.4;">Opsional. Anda dapat menentukan wali kelas nanti melalui menu Edit.</p>
                        </div>
                    </div>

                    <div style="position: relative;">
                        <select name="wali_kelas_id" class="form-control" style="height: 48px; border-radius: 12px; border: 1.5px solid var(--border); font-weight: 700; padding-left: 2.75rem; background: white;">
                            <option value="">-- Pilih Nama Guru --</option>
                            @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('wali_kelas_id') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->nama_lengkap }}{{ $teacher->nip ? " (NIP: {$teacher->nip})" : '' }}
                            </option>
                            @endforeach
                        </select>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--primary);"><circle cx="12" cy="12" r="10"/><path d="M12 8v8"/><path d="M8 12h8"/></svg>
                    </div>
                </div>

                {{-- SUBMIT BUTTONS --}}
                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 2; height: 52px; border-radius: 30px; font-weight: 900; background: var(--primary); display: flex; align-items: center; justify-content: center; gap: 0.75rem; box-shadow: var(--shadow-lg); cursor: pointer; border: none; color: white;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        SIMPAN ROMBEL
                    </button>
                    <a href="{{ route('classrooms.index') }}" class="btn btn-outline" style="flex: 1; height: 52px; border-radius: 30px; display: flex; align-items: center; justify-content: center; border: 1.5px solid var(--border); background: white; font-weight: 700; text-decoration: none; color: var(--text);">
                        BATAL
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .form-control:focus {
        border-color: var(--primary) !important;
        box-shadow: 0 0 0 4px rgba(0, 0, 82, 0.05);
    }
</style>

@endsection
