@extends('layouts.app')
@section('title', 'Tambah Unit')
@section('page-title', 'Form Unit Baru')

@section('content')
<div style="display: flex; justify-content: center; padding-top: 2rem;">
    <div class="card" style="width: 100%; max-width: 580px; border-radius: 24px; box-shadow: var(--shadow-lg); overflow: hidden; border: 1px solid var(--border);">
        <div style="background: var(--primary); padding: 2rem; color: white; position: relative;">
            <h3 style="font-size: 1.25rem; font-weight: 800; margin: 0;">Tambah Unit Pendidikan</h3>
            <p style="font-size: 0.85rem; opacity: 0.7; margin-top: 4px;">Tentukan jenjang baru dalam ekosistem pendidikan SIMAD.</p>
            <div style="position: absolute; right: 2rem; top: 50%; transform: translateY(-50%); opacity: 0.1;">
                <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
            </div>
        </div>

        <div style="padding: 2.5rem;">
            <form action="{{ route('education-levels.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                {{-- KOP Surat --}}
                <div style="margin-bottom: 2rem; background: var(--bg-secondary); padding: 1.5rem; border-radius: 16px; border: 1px dashed var(--border);">
                    <label style="display: block; font-size: 0.8rem; font-weight: 800; color: var(--primary); text-transform: uppercase; margin-bottom: 0.75rem;">Header / KOP Surat (Opsional)</label>
                    <input type="file" name="kop_surat" class="form-control" accept="image/png, image/jpeg, image/jpg" style="background: white; border-radius: 10px; height: auto; padding: 0.5rem;">
                    <div style="display: flex; gap: 0.5rem; margin-top: 0.75rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--text-muted); margin-top: 2px;"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="16" y2="12"/><line x1="12" x2="12.01" y1="8" y2="8"/></svg>
                        <p style="font-size: 0.75rem; color: var(--text-muted); line-height: 1.4; margin: 0;">Maks 2MB (JPG/PNG). Gunakan rasio memanjang untuk hasil cetak E-Raport yang optimal.</p>
                    </div>
                    @error('kop_surat')<span style="color:var(--danger);font-size:.75rem;display:block;margin-top:8px;font-weight:600;">{{ $message }}</span>@enderror
                </div>

                {{-- Unit Identity --}}
                <div style="display: grid; grid-template-columns: 120px 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px;">Kode <span style="color:var(--danger);">*</span></label>
                        <input type="text" name="kode" value="{{ old('kode') }}" class="form-control {{ $errors->has('kode') ? 'is-invalid' : '' }}" placeholder="Contoh: MTS" maxlength="10" required style="border-radius: 10px; font-weight: 800; text-align: center; font-size: 1.1rem; height: 50px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px;">Nama Lengkap Unit <span style="color:var(--danger);">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama') }}" class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}" placeholder="Masukan nama lengkap unit..." maxlength="100" required style="border-radius: 10px; font-weight: 600; height: 50px;">
                    </div>
                </div>

                @if($errors->any())
                    <div style="margin-bottom: 2rem; padding: 1rem; background: #FEF2F2; border-radius: 12px; border: 1px solid #FEE2E2;">
                        @foreach($errors->all() as $error)
                            <div style="color: #DC2626; font-size: 0.8rem; font-weight: 600; margin-bottom: 2px;">• {{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <div style="display: flex; gap: 1rem; padding-top: 1rem; border-top: 1px solid var(--border);">
                    <button type="submit" class="btn btn-primary" style="flex: 2; height: 50px; border-radius: 30px; font-weight: 800; background: var(--primary); box-shadow: var(--shadow-lg);">
                        SIMPAN UNIT
                    </button>
                    <a href="{{ route('education-levels.index') }}" class="btn btn-outline" style="flex: 1; height: 50px; border-radius: 30px; font-weight: 700; display: flex; align-items: center; justify-content: center;">
                        BATAL
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

