@extends('layouts.app')
@section('title', 'Profil Induk')
@section('page-title', 'Pusat Identitas Lembaga')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-xl-11">
        {{-- ── 1. HEADER SECTION (KOP SURAT STYLE) ── --}}
        <div style="background: linear-gradient(135deg, #000030 0%, #000052 40%, #00B0FB 100%); border-radius: 24px; padding: 2.5rem 3rem; margin-bottom: 24px; position: relative; overflow: hidden; box-shadow: 0 15px 35px rgba(0,0,82,0.15);">
            <div style="position: absolute; top: -50px; right: -50px; width: 250px; height: 250px; background: rgba(255,255,255,0.03); border-radius: 50%;"></div>
            
            {{-- Forced Side-by-Side Flexbox --}}
            <div style="display: flex; align-items: center; gap: 2.5rem; position: relative; z-index: 2;">
                {{-- LOGO (KIRI) --}}
                <div style="flex: 0 0 auto;">
                    <div style="width: 130px; height: 130px; background: white; border-radius: 32px; padding: 18px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); display: flex; align-items: center; justify-content: center; position: relative;">
                        @if(isset($settings['institution.logo']) && $settings['institution.logo'])
                            <img src="{{ asset('storage/' . $settings['institution.logo']) }}" id="hero-logo" style="width: 100%; height: 100%; object-fit: contain;">
                        @else
                            <div style="color: #cbd5e1;" id="hero-logo-placeholder">
                                <svg xmlns="http://www.w3.org/2000/svg" width="54" height="54" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 14h18M3 10l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V10z"/><circle cx="12" cy="14" r="3"/></svg>
                            </div>
                        @endif
                        <span style="position: absolute; top: -8px; right: -8px; background: #FFD700; color: #000052; padding: 4px 10px; border-radius: 8px; font-size: 0.6rem; font-weight: 900; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">OFFICIAL</span>
                    </div>
                </div>

                {{-- TEXT (KANAN - RATA KIRI) --}}
                <div style="flex: 1; text-align: left;">
                    <h1 id="hero-name" style="color: white; font-weight: 950; font-size: 2.75rem; margin-bottom: 0.5rem; letter-spacing: -1.5px; line-height: 1; text-align: left;">
                        {{ $settings['institution.name'] ?? 'Nama Lembaga' }}
                    </h1>
                    <div style="display: flex; flex-wrap: wrap; gap: 1.5rem; color: rgba(255,255,255,0.8); font-size: 0.95rem; text-align: left;">
                        <div class="d-flex align-items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.7;"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M19 16l3 3-3 3"/><path d="m19 16 3 3-3 3"/></svg>
                            <span id="hero-head">Pengasuh: {{ $settings['institution.head'] ?? '-' }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.7;"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.74a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/></svg>
                            <span id="hero-address">Alamat: {{ $settings['institution.address'] ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── 2. UNIFIED MAIN CARD ── --}}
        <div style="background: white; border-radius: 24px; border: 1px solid var(--border); box-shadow: var(--shadow-sm); overflow: hidden;">
            <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="group" value="institution">

                <div style="display: flex; flex-wrap: wrap; width: 100%;">
                    {{-- LEFT COLUMN: DATA TEXT --}}
                    <div style="flex: 1 1 500px; min-width: 320px; padding: 4rem; border-right: 1px solid #f1f5f9;">
                        <div class="d-flex align-items-center gap-3 mb-5" style="margin-bottom: 3.5rem !important;">
                            <div style="width: 44px; height: 44px; background: #eff6ff; color: #1e3a8a; border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/></svg>
                            </div>
                            <h5 style="margin: 0; font-weight: 900; color: var(--primary); letter-spacing: -0.5px;">Konfigurasi Data Teks</h5>
                        </div>

                        <div class="mb-4" style="margin-bottom: 2rem !important;">
                            <label class="form-label" style="font-weight: 700; color: #64748b; font-size: 0.75rem; text-transform: uppercase; margin-bottom: 0.75rem; display: block; letter-spacing: 0.5px;">Nama Lembaga</label>
                            <input type="text" name="institution[name]" id="input-name" value="{{ $settings['institution.name'] ?? '' }}" class="form-control" style="background: #f8fafc; border-radius: 14px; padding: 1.125rem 1.25rem; font-weight: 600; border-color: #e2e8f0; font-size: 1rem; transition: all 0.2s ease;">
                        </div>

                        <div class="mb-4" style="margin-bottom: 2rem !important;">
                            <label class="form-label" style="font-weight: 700; color: #64748b; font-size: 0.75rem; text-transform: uppercase; margin-bottom: 0.75rem; display: block; letter-spacing: 0.5px;">Nama Pengasuh / Pimpinan</label>
                            <input type="text" name="institution[head]" id="input-head" value="{{ $settings['institution.head'] ?? '' }}" class="form-control" style="background: #f8fafc; border-radius: 14px; padding: 1.125rem 1.25rem; font-weight: 600; border-color: #e2e8f0; font-size: 1rem; transition: all 0.2s ease;">
                        </div>

                        <div class="mb-0">
                            <label class="form-label" style="font-weight: 700; color: #64748b; font-size: 0.75rem; text-transform: uppercase; margin-bottom: 0.75rem; display: block; letter-spacing: 0.5px;">Alamat Domisili</label>
                            <textarea name="institution[address]" id="input-address" rows="5" class="form-control" style="background: #f8fafc; border-radius: 14px; padding: 1.125rem 1.25rem; font-weight: 600; resize: none; border-color: #e2e8f0; font-size: 1rem; transition: all 0.2s ease;">{{ $settings['institution.address'] ?? '' }}</textarea>
                        </div>
                    </div>

                    {{-- RIGHT COLUMN: LOGO UPLOAD --}}
                    <div style="flex: 0 0 380px; min-width: 320px; padding: 4rem; background: #fafafa;">
                        <div class="d-flex align-items-center gap-3 mb-5" style="margin-bottom: 3.5rem !important;">
                            <div style="width: 44px; height: 44px; background: #fef3c7; color: #92400e; border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                            </div>
                            <h5 style="margin: 0; font-weight: 900; color: var(--primary); letter-spacing: -0.5px;">Manajemen Logo</h5>
                        </div>

                        <div style="border: 2px dashed #cbd5e1; border-radius: 20px; padding: 4rem 1.5rem; text-align: center; background: white; transition: all 0.2s ease;">
                            <div style="margin-bottom: 2.25rem; color: #94a3b8;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg>
                            </div>
                            <label for="logo-upload" class="px-4 py-2 mb-2 btn btn-primary" style="background: var(--primary); border: none; border-radius: 10px; font-weight: 800; cursor: pointer; letter-spacing: 0.5px; padding: 10px 24px !important;">
                                UNGGAH LOGO BARU
                            </label>
                            <input type="file" name="institution[logo]" id="logo-upload" hidden accept="image/*">
                            <p style="margin: 1.25rem 0 0 0; font-size: 0.75rem; color: #94a3b8; font-weight: 500;">PNG/JPG latar transparan (Maks 2MB)</p>
                        </div>
                        
                        <div style="margin-top: 3rem; background: white; border-radius: 16px; padding: 1.5rem; border: 1px solid #f1f5f9; box-shadow: 0 4px 10px rgba(0,0,0,0.02);">
                            <div class="d-flex gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="16" y2="12"/><line x1="12" x2="12.01" y1="8" y2="8"/></svg>
                                <p style="font-size: 0.75rem; color: #64748b; margin: 0; line-height: 1.6;">
                                    <strong>Sinkronisasi Otomatis:</strong> Perubahan logo akan langsung diterapkan pada KOP surat resmi dan modul administrasi lainnya.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- FORM FOOTER / ACTION --}}
                <div style="background: #f8fafc; padding: 1.75rem 3rem; display: flex; justify-content: flex-end; border-top: 1px solid var(--border);">
                    <button type="submit" class="btn btn-warning" style="padding: 14px 60px; border-radius: 14px; font-weight: 950; background: var(--accent); color: var(--primary); border: none; box-shadow: 0 10px 25px rgba(252, 213, 38, 0.4); font-size: 1rem; letter-spacing: -0.5px;">
                        SIMPAN PERUBAHAN
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const inputName = document.getElementById('input-name');
    const inputHead = document.getElementById('input-head');
    const inputAddress = document.getElementById('input-address');
    const heroName = document.getElementById('hero-name');
    const heroHead = document.getElementById('hero-head');
    const heroAddress = document.getElementById('hero-address');

    if(inputName) {
        inputName.addEventListener('input', (e) => { heroName.textContent = e.target.value || 'Nama Lembaga'; });
    }
    if(inputHead) {
        inputHead.addEventListener('input', (e) => { heroHead.textContent = 'Pengasuh: ' + (e.target.value || '-'); });
    }
    if(inputAddress) {
        inputAddress.addEventListener('input', (e) => { heroAddress.textContent = 'Alamat: ' + (e.target.value || '-'); });
    }

    const logoUpload = document.getElementById('logo-upload');
    if(logoUpload) {
        logoUpload.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const heroLogo = document.getElementById('hero-logo');
                    const placeholder = document.getElementById('hero-logo-placeholder');
                    if (heroLogo) {
                        heroLogo.src = e.target.result;
                    } else if (placeholder) {
                        placeholder.outerHTML = `<img src="${e.target.result}" id="hero-logo" style="width: 100%; height: 100%; object-fit: contain;">`;
                    }
                }
                reader.readAsDataURL(file);
            }
        });
    }
</script>
@endpush
@endsection
