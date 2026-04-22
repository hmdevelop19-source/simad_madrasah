@extends('layouts.app')
@section('title', 'Profil Aplikasi')
@section('page-title', 'Konfigurasi Sistem')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-xl-11">
        {{-- ── 1. HEADER SECTION (KOP STYLE) ── --}}
        <div style="background: linear-gradient(135deg, #000030 0%, #000052 40%, #00B0FB 100%); border-radius: 24px; padding: 2.5rem 3rem; margin-bottom: 24px; position: relative; overflow: hidden; box-shadow: 0 15px 35px rgba(0,0,82,0.15);">
            <div style="position: absolute; top: -50px; right: -50px; width: 250px; height: 250px; background: rgba(255,255,255,0.03); border-radius: 50%;"></div>
            
            {{-- Forced Side-by-Side Flexbox --}}
            <div style="display: flex; align-items: center; gap: 2.5rem; position: relative; z-index: 2;">
                {{-- FAVICON (KIRI) --}}
                <div style="flex: 0 0 auto;">
                    <div style="width: 130px; height: 130px; background: white; border-radius: 32px; padding: 18px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); display: flex; align-items: center; justify-content: center; position: relative;">
                        @if(isset($settings['app.favicon']) && $settings['app.favicon'])
                            <img src="{{ asset('storage/' . $settings['app.favicon']) }}" id="favicon-preview-hero" style="width: 100%; height: 100%; object-fit: contain;">
                        @else
                            <div style="color: #cbd5e1;" id="favicon-placeholder-hero">
                                <svg xmlns="http://www.w3.org/2000/svg" width="54" height="54" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="M2 12h2"/><path d="M20 12h2"/></svg>
                            </div>
                        @endif
                        <span style="position: absolute; top: -8px; right: -8px; background: #22c55e; color: white; padding: 4px 10px; border-radius: 8px; font-size: 0.6rem; font-weight: 900; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">ACTIVE</span>
                    </div>
                </div>

                {{-- APP INFO (KANAN - RATA KIRI) --}}
                <div style="flex: 1; text-align: left;">
                    <h1 id="hero-app-name" style="color: white; font-weight: 950; font-size: 2.75rem; margin-bottom: 0.5rem; letter-spacing: -1.5px; line-height: 1; text-align: left;">
                        {{ $settings['app.name'] ?? 'Nama Aplikasi' }}
                    </h1>
                    <div style="display: flex; flex-wrap: wrap; gap: 1.5rem; color: rgba(255,255,255,0.8); font-size: 0.95rem; text-align: left;">
                        <div class="d-flex align-items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.7;"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                            <span id="hero-app-short">{{ $settings['app.short_name'] ?? 'SIMAD' }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.7;"><circle cx="12" cy="12" r="10"/><line x1="22" x2="18" y1="2" y2="6"/><line x1="6" x2="2" y1="18" y2="22"/><line x1="2" x2="6" y1="2" y2="6"/><line x1="18" x2="22" y1="18" y2="22"/></svg>
                            <span id="hero-app-version">Version: {{ $settings['app.version'] ?? '1.0.0' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── 2. MAIN CONFIGURE CARD ── --}}
        <div style="background: white; border-radius: 24px; border: 1px solid var(--border); box-shadow: var(--shadow-sm); overflow: hidden;">
            <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="group" value="app">

                <div style="display: flex; flex-wrap: wrap; width: 100%;">
                    {{-- LEFT COLUMN: DATA TEXT --}}
                    <div style="flex: 1 1 500px; min-width: 320px; padding: 4rem; border-right: 1px solid #f1f5f9;">
                        <div class="d-flex align-items-center gap-3 mb-5" style="margin-bottom: 3.5rem !important;">
                            <div style="width: 44px; height: 44px; background: #eff6ff; color: #1e3a8a; border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                            </div>
                            <h5 style="margin: 0; font-weight: 900; color: var(--primary); letter-spacing: -0.5px;">Konfigurasi Sistem</h5>
                        </div>

                        {{-- ROW 1: NAMA & SINGKATAN --}}
                        <div style="display: flex; gap: 2.5rem; margin-bottom: 2.5rem; width: 100%; flex-wrap: wrap; align-items: flex-end;">
                            <div style="flex: 2; min-width: 280px;">
                                <div style="margin-bottom: 0.875rem;">
                                    <label style="font-weight: 700; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.8px;">Nama Lengkap Aplikasi</label>
                                </div>
                                <input type="text" name="app[name]" id="input-app-name" value="{{ $settings['app.name'] ?? '' }}" class="form-control" style="background: #f8fafc; border-radius: 14px; padding: 1.125rem 1.5rem; font-weight: 600; border: 1.5px solid #e2e8f0; font-size: 1rem; width: 100%; transition: all 0.2s ease;">
                            </div>

                            <div style="flex: 1; min-width: 180px;">
                                <div style="margin-bottom: 0.875rem;">
                                    <label style="font-weight: 700; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.8px;">Singkatan</label>
                                </div>
                                <input type="text" name="app[short_name]" id="input-app-short" value="{{ $settings['app.short_name'] ?? '' }}" class="form-control" style="background: #f8fafc; border-radius: 14px; padding: 1.125rem 1.5rem; font-weight: 600; border: 1.5px solid #e2e8f0; font-size: 1rem; width: 100%; transition: all 0.2s ease;">
                            </div>
                        </div>

                        {{-- ROW 2: VERSI --}}
                        <div style="margin-bottom: 2.5rem;">
                            <div style="margin-bottom: 0.875rem;">
                                <label style="font-weight: 700; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.8px;">Versi Rilis Aplikasi</label>
                            </div>
                            <input type="text" name="app[version]" id="input-app-version" value="{{ $settings['app.version'] ?? '' }}" class="form-control" style="background: #f8fafc; border-radius: 14px; padding: 1.125rem 1.5rem; font-weight: 600; border: 1.5px solid #e2e8f0; font-size: 1rem; transition: all 0.2s ease;">
                        </div>

                        {{-- ROW 3: FOOTER --}}
                        <div style="margin-bottom: 0;">
                            <div style="margin-bottom: 0.875rem;">
                                <label style="font-weight: 700; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.8px;">Teks Hak Cipta (Footer)</label>
                            </div>
                            <textarea name="app[footer]" id="input-app-footer" rows="4" class="form-control" style="background: #f8fafc; border-radius: 14px; padding: 1.125rem 1.5rem; font-weight: 600; resize: none; border: 1.5px solid #e2e8f0; font-size: 1rem; transition: all 0.2s ease; line-height: 1.6;">{{ $settings['app.footer'] ?? '' }}</textarea>
                        </div>
                    </div>

                    {{-- RIGHT COLUMN: FAVICON & BUTTON --}}
                    <div style="flex: 0 0 400px; min-width: 320px; display: flex; flex-direction: column; background: #fafafa;">
                        <div style="padding: 4rem; flex: 1;">
                            <div class="d-flex align-items-center gap-3 mb-5" style="margin-bottom: 3.5rem !important;">
                                <div style="width: 44px; height: 44px; background: #fef3c7; color: #92400e; border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="M2 12h2"/><path d="M20 12h2"/></svg>
                                </div>
                                <h5 style="margin: 0; font-weight: 900; color: var(--primary); letter-spacing: -0.5px;">Ikon Aplikasi</h5>
                            </div>

                            <div style="border: 2px dashed #cbd5e1; border-radius: 20px; padding: 4rem 1.5rem; text-align: center; background: white; transition: all 0.2s ease;">
                                <div style="margin-bottom: 2.25rem; color: #94a3b8;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg>
                                </div>
                                <label for="favicon-upload" class="px-4 py-2 mb-2 btn btn-primary" style="background: var(--primary); border: none; border-radius: 10px; font-weight: 800; cursor: pointer; letter-spacing: 0.5px; padding: 10px 24px !important;">
                                    UNGGAH IKON BARU
                                </label>
                                <input type="file" name="app[favicon]" id="favicon-upload" hidden accept="image/x-icon,image/png">
                                <p style="margin: 1.25rem 0 0 0; font-size: 0.75rem; color: #94a3b8; font-weight: 500;">Rekomendasi: PNG 512x512px</p>
                            </div>
                            
                            <div style="margin-top: 3rem; background: white; border-radius: 16px; padding: 1.5rem; border: 1px solid #f1f5f9;">
                                <h6 style="font-size: 0.75rem; font-weight: 800; color: #475569; text-transform: uppercase; margin-bottom: 1.25rem;">Environment</h6>
                                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                                    <div style="display: flex; justify-content: space-between; font-size: 0.8rem;">
                                        <span style="color: #64748b; font-weight: 600;">Laravel Engine</span>
                                        <span style="color: var(--primary); font-weight: 800;">v{{ app()->version() }}</span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; font-size: 0.8rem;">
                                        <span style="color: #64748b; font-weight: 600;">PHP Runtime</span>
                                        <span style="color: var(--primary); font-weight: 800;">v{{ phpversion() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ACTION BUTTON AT THE BOTTOM OF RIGHT COLUMN --}}
                        <div style="background: #f8fafc; padding: 2.5rem; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; gap: 1rem; border-top: 1px solid #f1f5f9;">
                            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1.25rem; border-radius: 16px; font-weight: 950; background: var(--primary); color: white; border: none; box-shadow: 0 10px 25px rgba(0, 0, 82, 0.15); font-size: 1rem; letter-spacing: 0.5px; text-transform: uppercase; display: flex; align-items: center; justify-content: center; text-align: center;">
                                PERBARUI SISTEM
                            </button>
                            <span style="color: #94a3b8; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Pastikan data sudah benar</span>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const inputAppName = document.getElementById('input-app-name');
    const inputAppShort = document.getElementById('input-app-short');
    const inputAppVersion = document.getElementById('input-app-version');
    
    const heroAppName = document.getElementById('hero-app-name');
    const heroAppShort = document.getElementById('hero-app-short');
    const heroAppVersionNum = document.getElementById('hero-app-version');

    if(inputAppName) {
        inputAppName.addEventListener('input', (e) => { heroAppName.textContent = e.target.value || 'Nama Aplikasi'; });
    }
    if(inputAppShort) {
        inputAppShort.addEventListener('input', (e) => { heroAppShort.textContent = e.target.value || 'SIMAD'; });
    }
    if(inputAppVersion) {
        inputAppVersion.addEventListener('input', (e) => { heroAppVersionNum.textContent = 'Version: ' + (e.target.value || '1.0.0'); });
    }

    const faviconInput = document.getElementById('favicon-input');
    if(faviconInput) {
        faviconInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const heroFav = document.getElementById('favicon-preview-hero');
                    const placeholder = document.getElementById('favicon-placeholder-hero');
                    if (heroFav) {
                        heroFav.src = e.target.result;
                    } else if (placeholder) {
                        placeholder.outerHTML = `<img src="${e.target.result}" id="favicon-preview-hero" style="width: 100%; height: 100%; object-fit: contain;">`;
                    }
                }
                reader.readAsDataURL(file);
            }
        });
    }
</script>
@endpush
@endsection
