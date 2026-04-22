@extends('layouts.app')
@section('title', 'Tambah Wali Santri')
@section('page-title', 'Registrasi Wali Santri Baru')

@section('content')

<div style="display: flex; justify-content: center; padding: 2rem 0;">
    <div class="card" style="width: 100%; max-width: 680px; border-radius: 24px; box-shadow: var(--shadow-lg); overflow: hidden; border: 1px solid var(--border);">
        
        {{-- CARD HEADER --}}
        <div style="background: var(--primary); padding: 2.25rem; color: white; position: relative;">
            <div style="display: flex; align-items: center; gap: 1rem; position: relative; z-index: 2;">
                <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.15); border-radius: 14px; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(255,255,255,0.25);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <div>
                    <h3 style="font-size: 1.25rem; font-weight: 800; margin: 0; letter-spacing: 0.5px;">Registrasi Wali Santri</h3>
                    <p style="font-size: 0.85rem; opacity: 0.75; margin-top: 4px;">Pendataan identitas orang tua atau wali penanggung jawab santri.</p>
                </div>
            </div>
            <div style="position: absolute; right: 2rem; top: 50%; transform: translateY(-50%); opacity: 0.1; pointer-events: none;">
                <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M2 21a8 8 0 0 1 13.29-6"/><circle cx="10" cy="8" r="5"/><path d="M19 16v6"/><path d="M22 19h-6"/></svg>
            </div>
        </div>

        {{-- FORM BODY --}}
        <div style="padding: 2.5rem;">
            <form action="{{ route('wali-santri.store') }}" method="POST">
                @csrf

                {{-- SECTION 1: PROFIL DASAR --}}
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 2rem;">
                    <span style="font-size: 0.75rem; font-weight: 900; color: var(--primary); text-transform: uppercase; letter-spacing: 1px;">📋 Identitas Dasar</span>
                    <div style="flex: 1; height: 1px; background: var(--border);"></div>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">Nama Lengkap <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" class="form-control @error('nama_lengkap') is-invalid @enderror" placeholder="Nama ayah / ibu / wali" style="height: 48px; border-radius: 12px; font-weight: 800; border: 1.5px solid var(--border);" required>
                    @error('nama_lengkap')<span style="color: var(--danger); font-size: 0.7rem; font-weight: 700; margin-top: 4px; display: block;">{{ $message }}</span>@enderror
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">NIK (16 Digit) <span style="color: var(--danger);">*</span></label>
                        <div style="position: relative;">
                            <input type="text" name="nik" value="{{ old('nik') }}" class="form-control @error('nik') is-invalid @enderror" placeholder="16 digit NIK" maxlength="16" style="height: 48px; border-radius: 12px; border: 1.5px solid var(--border); padding-left: 2.75rem;" required>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--primary);"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        </div>
                        @error('nik')<span style="color: var(--danger); font-size: 0.7rem; font-weight: 700; margin-top: 4px; display: block;">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">No. WhatsApp <span style="color: var(--danger);">*</span></label>
                        <div style="position: relative;">
                            <input type="text" name="no_whatsapp" value="{{ old('no_whatsapp') }}" class="form-control @error('no_whatsapp') is-invalid @enderror" placeholder="08xx-xxxx-xxxx" style="height: 48px; border-radius: 12px; border: 1.5px solid var(--border); padding-left: 2.75rem;" required>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--primary);"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        </div>
                        @error('no_whatsapp')<span style="color: var(--danger); font-size: 0.7rem; font-weight: 700; margin-top: 4px; display: block;">{{ $message }}</span>@enderror
                    </div>
                </div>

                {{-- SECTION 2: PROFIL SOSIAL EKONOMI --}}
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 2rem; padding-top: 1rem;">
                    <span style="font-size: 0.75rem; font-weight: 900; color: var(--primary); text-transform: uppercase; letter-spacing: 1px;">🏢 Profil Sosio-Ekonomi</span>
                    <div style="flex: 1; height: 1px; background: var(--border);"></div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div>
                        <label style="display: block; font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px;">Hubungan Keluarga</label>
                        <select name="hubungan_keluarga" class="form-control" style="height: 42px; border-radius: 10px; border: 1px solid var(--border); font-weight: 700;" required>
                            <option value="">-- Pilih --</option>
                            @foreach(['Ayah','Ibu','Kakek','Nenek','Paman','Bibi','Wali Lainnya'] as $hub)
                            <option value="{{ $hub }}" {{ old('hubungan_keluarga') === $hub ? 'selected' : '' }}>{{ $hub }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px;">Pendidikan Terakhir</label>
                        <select name="pendidikan_terakhir" class="form-control" style="height: 42px; border-radius: 10px; border: 1px solid var(--border); font-weight: 700;" required>
                            <option value="">-- Pilih --</option>
                            @foreach(['SD/MI','SMP/MTs','SMA/MA','D3','S1','S2','S3','Lainnya'] as $pend)
                            <option value="{{ $pend }}" {{ old('pendidikan_terakhir') === $pend ? 'selected' : '' }}>{{ $pend }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div>
                        <label style="display: block; font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px;">Pekerjaan Utama</label>
                        <input type="text" name="pekerjaan" value="{{ old('pekerjaan') }}" class="form-control" placeholder="cth: Wiraswasta" style="height: 42px; border-radius: 10px; border: 1px solid var(--border);" required>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px;">Penghasilan Per-Bulan</label>
                        <select name="penghasilan_bulanan" class="form-control" style="height: 42px; border-radius: 10px; border: 1px solid var(--border); font-weight: 700;" required>
                            <option value="">-- Pilih --</option>
                            @foreach(['< 1 Juta','1-3 Juta','3-5 Juta','5-10 Juta','> 10 Juta'] as $pg)
                            <option value="{{ $pg }}" {{ old('penghasilan_bulanan') === $pg ? 'selected' : '' }}>{{ $pg }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div style="margin-bottom: 2.5rem;">
                    <label style="display: block; font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">Alamat Domisili Lengkap</label>
                    <textarea name="alamat_lengkap" class="form-control" rows="3" placeholder="Jalan, RT/RW, Desa, Kecamatan, Kab/Kota" style="border-radius: 12px; border: 1.5px solid var(--border); padding: 0.75rem 1rem;" required>{{ old('alamat_lengkap') }}</textarea>
                </div>

                {{-- SUBMIT BUTTONS --}}
                <div style="display: flex; gap: 1rem; padding-top: 1rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 2; height: 52px; border-radius: 30px; font-weight: 900; background: var(--primary); display: flex; align-items: center; justify-content: center; gap: 0.75rem; box-shadow: var(--shadow-lg);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        SIMPAN DATA WALI
                    </button>
                    <a href="{{ route('wali-santri.index') }}" class="btn btn-outline" style="flex: 1; height: 52px; border-radius: 30px; display: flex; align-items: center; justify-content: center; border: 1.5px solid var(--border); background: white; font-weight: 700;">
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
