@extends('layouts.app')
@section('title', 'Tambah Tahun Ajaran')
@section('page-title', 'Konfigurasi Tahun Ajaran Baru')

@section('content')

<div style="display: flex; justify-content: center; padding: 3rem 0;">
    <div class="card" style="width: 100%; max-width: 540px; border-radius: 24px; box-shadow: var(--shadow-lg); overflow: hidden; border: 1px solid var(--border);">
        
        {{-- CARD HEADER --}}
        <div style="background: var(--primary); padding: 2.25rem; color: white; position: relative;">
            <div style="display: flex; align-items: center; gap: 1rem; position: relative; z-index: 2;">
                <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.15); border-radius: 14px; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(255,255,255,0.25);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                </div>
                <div>
                    <h3 style="font-size: 1.25rem; font-weight: 800; margin: 0; letter-spacing: 0.5px;">Setup Tahun Ajaran</h3>
                    <p style="font-size: 0.85rem; opacity: 0.75; margin-top: 4px;">Inisialisasi periode akademik dan jadwal kuartal otomatis.</p>
                </div>
            </div>
            <div style="position: absolute; right: 2rem; top: 50%; transform: translateY(-50%); opacity: 0.1; pointer-events: none;">
                <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
            </div>
        </div>

        {{-- FORM BODY --}}
        <div style="padding: 2.5rem;">
            <form action="{{ route('academic-years.store') }}" method="POST">
                @csrf

                <div style="margin-bottom: 2rem;">
                    <label style="display: block; font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">Nama Tahun Ajaran <span style="color: var(--danger);">*</span></label>
                    <div style="position: relative;">
                        <input type="text" name="nama" value="{{ old('nama') }}" class="form-control @error('nama') is-invalid @enderror" placeholder="Contoh: 2025/2026" style="height: 52px; border-radius: 12px; border: 1.5px solid var(--border); padding-left: 2.75rem; font-weight: 900; font-size: 1.15rem; color: var(--primary);" required>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--primary);"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                    </div>
                    @error('nama')<span style="color: var(--danger); font-size: 0.7rem; font-weight: 700; margin-top: 4px; display: block;">{{ $message }}</span>@enderror
                </div>

                {{-- INFO BOX: AUTO QUARTERS --}}
                <div style="background: #F0F9FF; border: 1px solid #BAE6FD; border-radius: 20px; padding: 1.5rem; margin-bottom: 2rem; display: flex; gap: 1.25rem;">
                    <div style="width: 40px; height: 40px; background: white; color: #0284C7; border-radius: 10px; display: flex; align-items: center; justify-content: center; box-shadow: var(--shadow-sm); flex-shrink: 0;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8V4H8"/><rect width="16" height="16" x="4" y="4" rx="2"/><path d="M12 4v16"/><path d="M4 12h16"/></svg>
                    </div>
                    <div>
                        <h4 style="font-size: 0.9rem; font-weight: 800; color: #0369A1; margin: 0;">Setup Kuartal Otomatis</h4>
                        <p style="font-size: 0.75rem; color: #075985; margin: 6px 0 0 0; line-height: 1.5;">
                            Sistem akan secara otomatis menyusun <strong>4 Periode Kuartal</strong> untuk tahun ajaran ini. Anda dapat mengaktifkan jadwal perkuliahan setelah proses ini selesai.
                        </p>
                    </div>
                </div>

                <div style="background: #F8FAFC; border: 1px solid var(--border); border-radius: 16px; padding: 1rem 1.5rem; margin-bottom: 2.5rem;">
                    <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer; margin: 0;">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }} style="width: 20px; height: 20px; accent-color: var(--primary);">
                        <div style="flex: 1;">
                            <span style="display: block; font-size: 0.85rem; font-weight: 800; color: var(--primary);">Aktifkan Tahun Ajaran Ini</span>
                            <span style="display: block; font-size: 0.65rem; color: #EF4444; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px;">⚠️ Akan menonaktifkan tahun ajaran aktif lainnya.</span>
                        </div>
                    </label>
                </div>

                {{-- SUBMIT BUTTONS --}}
                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 2; height: 52px; border-radius: 30px; font-weight: 900; background: var(--primary); display: flex; align-items: center; justify-content: center; gap: 0.75rem; box-shadow: var(--shadow-lg); cursor: pointer; border: none; color: white;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        SIMPAN PERIODE
                    </button>
                    <a href="{{ route('academic-years.index') }}" class="btn btn-outline" style="flex: 1; height: 52px; border-radius: 30px; display: flex; align-items: center; justify-content: center; border: 1.5px solid var(--border); background: white; font-weight: 700; text-decoration: none; color: var(--text);">
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
