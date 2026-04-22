@extends('layouts.app')
@section('title', 'Tambah Tingkat')
@section('page-title', 'Konfigurasi Tingkat Kelas')

@section('content')

<div style="display: flex; justify-content: center; padding: 3rem 0;">
    <div class="card" style="width: 100%; max-width: 540px; border-radius: 24px; box-shadow: var(--shadow-lg); overflow: hidden; border: 1px solid var(--border);">
        
        {{-- CARD HEADER --}}
        <div style="background: var(--primary); padding: 2rem; color: white; position: relative;">
            <div style="display: flex; align-items: center; gap: 1rem; position: relative; z-index: 2;">
                <div style="width: 48px; height: 48px; background: rgba(255,255,255,0.15); border-radius: 12px; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(255,255,255,0.25);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                </div>
                <div>
                    <h3 style="font-size: 1.15rem; font-weight: 800; margin: 0; letter-spacing: 0.5px;">Tambah Tingkat</h3>
                    <p style="font-size: 0.8rem; opacity: 0.75; margin-top: 4px;">Daftarkan jenjang kelas baru sesuai unit pendidikan.</p>
                </div>
            </div>
            <div style="position: absolute; right: 1.5rem; top: 50%; transform: translateY(-50%); opacity: 0.1; pointer-events: none;">
                <svg xmlns="http://www.w3.org/2000/svg" width="90" height="90" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M2 20h20"/><path d="M2 14h20"/><path d="M2 8h20"/><path d="M2 2h20"/></svg>
            </div>
        </div>

        {{-- FORM BODY --}}
        <div style="padding: 2.5rem;">
            <form action="{{ route('grade-levels.store') }}" method="POST">
                @csrf

                <div style="margin-bottom: 1.75rem;">
                    <label style="display: block; font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">Unit Pendidikan <span style="color: var(--danger);">*</span></label>
                    <div style="position: relative;">
                        <select name="education_level_id" class="form-control @error('education_level_id') is-invalid @enderror" style="height: 48px; border-radius: 12px; border: 1.5px solid var(--border); font-weight: 700; padding-left: 2.75rem;" required>
                            <option value="">-- Tentukan Unit --</option>
                            @foreach($educationLevels as $level)
                                <option value="{{ $level->id }}" {{ old('education_level_id') == $level->id ? 'selected' : '' }}>
                                    [{{ $level->kode }}] {{ $level->nama }}
                                </option>
                            @endforeach
                        </select>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--primary);"><path d="m2 9 10-5 10 5-10 5Z"/><path d="M7 21a10 10 0 1 1 10 0"/></svg>
                    </div>
                    @error('education_level_id')<span style="color: var(--danger); font-size: 0.7rem; font-weight: 700; margin-top: 4px; display: block;">{{ $message }}</span>@enderror
                </div>

                <div style="margin-bottom: 2.5rem;">
                    <label style="display: block; font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">Nama Tingkat / Kelas <span style="color: var(--danger);">*</span></label>
                    <div style="position: relative;">
                        <input type="text" name="nama_tingkat" value="{{ old('nama_tingkat') }}" class="form-control @error('nama_tingkat') is-invalid @enderror" placeholder="Contoh: Kelas 7, Kelas 8, RA A" style="height: 48px; border-radius: 12px; border: 1.5px solid var(--border); padding-left: 2.75rem; font-weight: 900; font-size: 1rem; color: var(--primary);" required>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--primary);"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                    </div>
                    @error('nama_tingkat')<span style="color: var(--danger); font-size: 0.7rem; font-weight: 700; margin-top: 4px; display: block;">{{ $message }}</span>@enderror
                    <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 10px; line-height: 1.4;">Nama tingkat akan digunakan sebagai referensi pengelompokan Rombongan Belajar (Rombel).</p>
                </div>

                {{-- SUBMIT BUTTONS --}}
                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 2; height: 52px; border-radius: 30px; font-weight: 900; background: var(--primary); display: flex; align-items: center; justify-content: center; gap: 0.75rem; box-shadow: var(--shadow-lg); cursor: pointer; border: none; color: white;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        SIMPAN TINGKAT
                    </button>
                    <a href="{{ route('grade-levels.index') }}" class="btn btn-outline" style="flex: 1; height: 52px; border-radius: 30px; display: flex; align-items: center; justify-content: center; border: 1.5px solid var(--border); background: white; font-weight: 700; text-decoration: none; color: var(--text);">
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
