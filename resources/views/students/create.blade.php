@extends('layouts.app')
@section('title', 'Pendaftaran Santri Baru')
@section('page-title', 'Registrasi Pendaftaran Santri')

@section('content')

<form action="{{ route('students.store') }}" method="POST" id="mainForm">
@csrf
<input type="hidden" name="mode_wali" id="mode_wali" value="{{ old('mode_wali', 'existing') }}">

<div style="display: grid; grid-template-columns: 1fr 1.15fr; gap: 2rem; align-items: start;">

    {{-- ══════════════════════════════════════════════
         KOLOM WALI (STEP 1)
    ══════════════════════════════════════════════ --}}
    <div>
        <div class="card" style="border-radius: 20px; border: 1px solid var(--border); box-shadow: var(--shadow-sm); overflow: hidden;">
            <div style="background: var(--primary); padding: 1.5rem 2rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 36px; height: 36px; background: rgba(255,255,255,0.15); color: white; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 0.85rem; border: 1px solid rgba(255,255,255,0.2);">1</div>
                    <div style="font-weight: 800; color: white; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.5px;">Informasi Wali</div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.5;"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            
            <div style="padding: 2rem;">
                {{-- SEGMENTED TOGGLE --}}
                <div style="background: #F1F5F9; padding: 0.4rem; border-radius: 14px; display: flex; gap: 0.4rem; margin-bottom: 2rem; border: 1px solid #E2E8F0;">
                    <button type="button" id="btnExisting" onclick="setModeWali('existing')" 
                        style="flex: 1; border: none; height: 42px; border-radius: 10px; font-weight: 800; font-size: 0.75rem; cursor: pointer; transition: 0.2s; text-transform: uppercase;">
                        🔍 Cari Wali Lama
                    </button>
                    <button type="button" id="btnNew" onclick="setModeWali('new')" 
                        style="flex: 1; border: none; height: 42px; border-radius: 10px; font-weight: 800; font-size: 0.75rem; cursor: pointer; transition: 0.2s; text-transform: uppercase;">
                        ➕ Wali Baru
                    </button>
                </div>

                {{-- PANEL: EXISTING WALI --}}
                <div id="panelExisting">
                    <div style="background: #F8FAFC; padding: 1.5rem; border-radius: 16px; border: 1.5px dashed var(--border);">
                        <label style="display: block; font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">Pilih Identitas Wali <span style="color: var(--danger);">*</span></label>
                        <div style="position: relative;">
                            <select name="wali_id" id="wali_id" class="form-control" style="border-radius: 12px; height: 48px; border: 1.5px solid var(--border); font-weight: 800; padding-left: 2.75rem;">
                                <option value="">-- Cari Nama Wali --</option>
                                @foreach($waliList as $w)
                                <option value="{{ $w->id }}" {{ old('wali_id') == $w->id ? 'selected' : '' }}>
                                    {{ $w->nama_lengkap }} ({{ $w->hubungan_keluarga }}) &middot; {{ $w->no_whatsapp }}
                                </option>
                                @endforeach
                            </select>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--primary);"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                        </div>
                        <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 10px;">Gunakan opsi ini jika santri memiliki saudara (kakak/adik) yang sudah terdaftar sebelumnya.</p>
                    </div>
                </div>

                {{-- PANEL: NEW WALI FORM --}}
                <div id="panelNew" style="display: none;">
                    <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                        <div>
                            <label style="display: block; font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px;">NIK Wali <span style="color: var(--danger);">*</span></label>
                            <input type="text" name="wali_baru[nik]" maxlength="16" placeholder="16 Digit NIK" class="form-control @error('wali_baru.nik') is-invalid @enderror" value="{{ old('wali_baru.nik') }}" style="border-radius: 10px; border: 1.5px solid var(--border);">
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px;">No. WhatsApp <span style="color: var(--danger);">*</span></label>
                            <input type="text" name="wali_baru[no_whatsapp]" placeholder="08xx-xxxx-xxxx" class="form-control @error('wali_baru.no_whatsapp') is-invalid @enderror" value="{{ old('wali_baru.no_whatsapp') }}" style="border-radius: 10px; border: 1.5px solid var(--border);">
                        </div>
                    </div>
                    <div style="margin-bottom: 1.25rem;">
                        <label style="display: block; font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px;">Nama Lengkap <span style="color: var(--danger);">*</span></label>
                        <input type="text" name="wali_baru[nama_lengkap]" placeholder="Nama orang tua / wali" class="form-control @error('wali_baru.nama_lengkap') is-invalid @enderror" value="{{ old('wali_baru.nama_lengkap') }}" style="border-radius: 10px; border: 1.5px solid var(--border); font-weight: 800;">
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                        <div>
                            <label style="display: block; font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px;">Hubungan <span style="color: var(--danger);">*</span></label>
                            <select name="wali_baru[hubungan_keluarga]" class="form-control" style="border-radius: 10px; border: 1.5px solid var(--border);">
                                <option value="">-- Pilih --</option>
                                @foreach($hubunganOptions as $h)
                                <option value="{{ $h }}" {{ old('wali_baru.hubungan_keluarga') === $h ? 'selected' : '' }}>{{ $h }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px;">Pendidikan Terakhir</label>
                            <select name="wali_baru[pendidikan_terakhir]" class="form-control" style="border-radius: 10px; border: 1.5px solid var(--border);">
                                <option value="">-- Pilih --</option>
                                @foreach($pendidikanOptions as $p)
                                <option value="{{ $p }}" {{ old('wali_baru.pendidikan_terakhir') === $p ? 'selected' : '' }}>{{ $p }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1.2fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                        <div>
                            <label style="display: block; font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px;">Pekerjaan</label>
                            <input type="text" name="wali_baru[pekerjaan]" placeholder="cth: Karyawan" class="form-control" value="{{ old('wali_baru.pekerjaan') }}" style="border-radius: 10px; border: 1.5px solid var(--border);">
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px;">Penghasilan</label>
                            <select name="wali_baru[penghasilan_bulanan]" class="form-control" style="border-radius: 10px; border: 1.5px solid var(--border);">
                                <option value="">-- Pilih --</option>
                                @foreach($penghasilanOptions as $ph)
                                <option value="{{ $ph }}" {{ old('wali_baru.penghasilan_bulanan') === $ph ? 'selected' : '' }}>{{ $ph }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px;">Alamat Lengkap <span style="color: var(--danger);">*</span></label>
                        <textarea name="wali_baru[alamat_lengkap]" rows="2" placeholder="Desa, Kecamatan, Kota/Kab" class="form-control" style="border-radius: 10px; border: 1.5px solid var(--border);">{{ old('wali_baru.alamat_lengkap') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════
         KOLOM SANTRI (STEP 2)
    ══════════════════════════════════════════════ --}}
    <div>
        <div class="card" style="border-radius: 20px; border: 1px solid var(--border); box-shadow: var(--shadow-sm); overflow: hidden;">
            <div style="background: var(--highlight); padding: 1.5rem 2rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 36px; height: 36px; background: rgba(0,0,0,0.05); color: var(--primary); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 0.85rem; border: 1px solid rgba(0,0,0,0.1);">2</div>
                    <div style="font-weight: 800; color: var(--primary); font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.5px;">Identitas Santri</div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.5;"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
            </div>

            <div style="padding: 2rem;">
                {{-- GENERIC ERRORS ALERT --}}
                @if($errors->any())
                <div style="padding: 1rem 1.25rem; background: #FEF2F2; border: 1px solid #FEE2E2; border-radius: 12px; margin-bottom: 2rem;">
                    <div style="color: #DC2626; font-size: 0.8rem; font-weight: 800; display: flex; align-items: center; gap: 0.5rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                        DATA BELUM LENGKAP
                    </div>
                </div>
                @endif

                {{-- NIK & NISN --}}
                <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div>
                        <label style="display: block; font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">NIK Santri <span style="color: var(--danger);">*</span></label>
                        <input type="text" name="nik" maxlength="16" placeholder="16 Digit NIK" class="form-control @error('nik') is-invalid @enderror" value="{{ old('nik') }}" style="height: 48px; border-radius: 12px; border: 1.5px solid var(--border); font-weight: 800;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">NISN (Opsional)</label>
                        <input type="text" name="nisn" maxlength="10" placeholder="10 Digit NISN" class="form-control" value="{{ old('nisn') }}" style="height: 48px; border-radius: 12px; border: 1.5px solid var(--border);">
                    </div>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">No. Kartu Keluarga <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="no_kk" maxlength="16" placeholder="16 Digit Nomor KK" class="form-control @error('no_kk') is-invalid @enderror" value="{{ old('no_kk') }}" style="height: 48px; border-radius: 12px; border: 1.5px solid var(--border); font-weight: 800;">
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">Nama Lengkap Santri <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="nama_lengkap" placeholder="Sesuai Akta Kelahiran" class="form-control @error('nama_lengkap') is-invalid @enderror" value="{{ old('nama_lengkap') }}" style="height: 48px; border-radius: 12px; border: 1.5px solid var(--border); font-weight: 900; font-size: 1.1rem; color: var(--primary);">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div>
                        <label style="display: block; font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" placeholder="Kota/Kab" class="form-control" value="{{ old('tempat_lahir') }}" style="height: 45px; border-radius: 10px; border: 1.5px solid var(--border);">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir') }}" style="height: 45px; border-radius: 10px; border: 1.5px solid var(--border);">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                    <div>
                        <label style="display: block; font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-control" style="height: 45px; border-radius: 10px; border: 1.5px solid var(--border);">
                            <option value="">-- Pilih --</option>
                            <option value="L" {{ old('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">Status Masuk</label>
                        <select name="status_aktif" class="form-control" style="height: 45px; border-radius: 10px; border: 1.5px solid var(--border);">
                            @foreach(['Aktif','Keluar'] as $s)
                            <option value="{{ $s }}" {{ old('status_aktif','Aktif') === $s ? 'selected' : '' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div style="background: var(--bg-secondary); padding: 1.5rem; border-radius: 16px; border: 1px solid var(--border);">
                    <label style="display: block; font-size: 0.75rem; font-weight: 900; color: var(--primary); text-transform: uppercase; margin-bottom: 8px;">Unit Pendidikan Saat Ini <span style="color: var(--danger);">*</span></label>
                    <div style="position: relative;">
                        <select name="current_level_id" class="form-control" style="height: 48px; border-radius: 12px; border: 1.5px solid var(--border); font-weight: 800; padding-left: 2.75rem;" required>
                            <option value="">-- Tentukan Unit Sekolah --</option>
                            @foreach($educationLevels as $lvl)
                            <option value="{{ $lvl->id }}" {{ old('current_level_id') == $lvl->id ? 'selected' : '' }}>
                                [{{ $lvl->kode }}] {{ $lvl->nama }}
                            </option>
                            @endforeach
                        </select>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--primary);"><path d="m2 9 10-5 10 5-10 5Z"/><path d="m2 14 10 5 10-5"/><path d="m2 19 10 5 10-5"/></svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ══════════════════════════════════════════════
     STICKY ACTION BAR
══════════════════════════════════════════════ --}}
<div style="position: sticky; bottom: 1.5rem; left: 0; right: 0; margin-top: 2rem; z-index: 100;">
    <div style="background: white; border: 1px solid var(--border); border-radius: 30px; padding: 1rem 2rem; box-shadow: var(--shadow-lg); display: flex; justify-content: space-between; align-items: center; border-bottom: 4px solid var(--primary);">
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <div style="background: #ECFDF5; color: #059669; padding: 0.4rem 0.8rem; border-radius: 30px; font-size: 0.75rem; font-weight: 800;">
                TAHAP REGISTRASI
            </div>
            <span style="font-size: 0.8rem; font-weight: 600; color: var(--text-muted);">Mohon periksa kembali data sebelum menyimpan.</span>
        </div>
        <div style="display: flex; gap: 1rem;">
            <a href="{{ route('students.index') }}" class="btn btn-outline" style="height: 48px; border-radius: 30px; padding: 0 2rem; display: flex; align-items: center; justify-content: center; font-weight: 800; background: white;">BATAL</a>
            <button type="submit" class="btn btn-primary" style="height: 48px; border-radius: 30px; padding: 0 3rem; background: var(--primary); color: white; border: none; font-weight: 900; box-shadow: var(--shadow-lg); cursor: pointer; display: flex; align-items: center; gap: 0.75rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                SIMPAN PENDAFTARAN
            </button>
        </div>
    </div>
</div>

</form>

@push('scripts')
<script>
// ── Global Config ──────────────────
const colors = {
    primary: '#000052',
    bg: '#F1F5F9',
    white: '#FFFFFF'
};

// ── Restore mode dari old input saat validasi gagal ──────────────────
window.onload = () => {
    const savedMode = '{{ old("mode_wali", "existing") }}';
    setModeWali(savedMode, true);
};

function setModeWali(mode, init = false) {
    document.getElementById('mode_wali').value = mode;

    const btnEx = document.getElementById('btnExisting');
    const btnNw = document.getElementById('btnNew');
    const panEx = document.getElementById('panelExisting');
    const panNw = document.getElementById('panelNew');

    if (mode === 'existing') {
        // Style Buttons
        btnEx.style.background = colors.white;   btnEx.style.color = colors.primary; btnEx.style.boxShadow = '0 4px 12px rgba(0,0,0,0.1)';
        btnNw.style.background = 'transparent'; btnNw.style.color = '#64748B';      btnNw.style.boxShadow = 'none';
        
        // Toggle Panels
        panEx.style.display = 'block';
        panNw.style.display = 'none';
        
        // Remove Required from new fields
        document.querySelectorAll('#panelNew [name]').forEach(el => el.removeAttribute('required'));
    } else {
        // Style Buttons
        btnNw.style.background = colors.white;   btnNw.style.color = colors.primary; btnNw.style.boxShadow = '0 4px 12px rgba(0,0,0,0.1)';
        btnEx.style.background = 'transparent'; btnEx.style.color = '#64748B';      btnEx.style.boxShadow = 'none';
        
        // Toggle Panels
        panEx.style.display = 'none';
        panNw.style.display = 'block';
        
        // Add Required to critical new fields
        ['wali_baru[nik]','wali_baru[nama_lengkap]','wali_baru[no_whatsapp]','wali_baru[alamat_lengkap]'].forEach(n => {
            const el = document.querySelector(`[name="${n}"]`);
            if (el) el.setAttribute('required', '');
        });
        
        const waliId = document.getElementById('wali_id');
        if (waliId) waliId.removeAttribute('required');
    }
}
</script>
@endpush

<style>
    .form-control:focus {
        border-color: var(--primary) !important;
        box-shadow: 0 0 0 4px rgba(0, 0, 82, 0.05);
    }
</style>

@endsection
