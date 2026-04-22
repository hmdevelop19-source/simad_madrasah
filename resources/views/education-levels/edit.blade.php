@extends('layouts.app')
@section('title', 'Edit Unit')
@section('page-title', 'Pembaruan Unit')

@section('content')
<div style="display: flex; justify-content: center; padding-top: 2rem;">
    <div class="card" style="width: 100%; max-width: 580px; border-radius: 24px; box-shadow: var(--shadow-lg); overflow: hidden; border: 1px solid var(--border);">
        <div style="background: var(--primary); padding: 2rem; color: white; position: relative;">
            <h3 style="font-size: 1.25rem; font-weight: 800; margin: 0;">Edit Unit Pendidikan</h3>
            <p style="font-size: 0.85rem; opacity: 0.7; margin-top: 4px;">Memperbarui identitas dan konfigurasi unit <strong>{{ $educationLevel->nama }}</strong>.</p>
            <div style="position: absolute; right: 2rem; top: 50%; transform: translateY(-50%); opacity: 0.1;">
                <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            </div>
        </div>

        <div style="padding: 2.5rem;">
            <form action="{{ route('education-levels.update', $educationLevel) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                
                {{-- KOP Surat --}}
                <div style="margin-bottom: 2rem; background: var(--bg-secondary); padding: 1.5rem; border-radius: 16px; border: 1px dashed var(--border);">
                    <label style="display: block; font-size: 0.8rem; font-weight: 800; color: var(--primary); text-transform: uppercase; margin-bottom: 0.75rem;">Header / KOP Surat</label>
                    
                    @if($educationLevel->kop_surat)
                        <div style="margin-bottom: 1rem; padding: 1rem; background: white; border-radius: 12px; border: 1px solid var(--border); text-align: center; box-shadow: var(--shadow-sm);">
                            <span style="font-size: 0.65rem; color: var(--text-muted); display: block; font-weight: 700; margin-bottom: 8px; text-transform: uppercase;">KOP SAAT INI</span>
                            <img src="{{ Storage::url($educationLevel->kop_surat) }}" alt="KOP" style="max-width: 100%; max-height: 100px; object-fit: contain; border-radius: 4px;">
                        </div>
                    @endif

                    <input type="file" name="kop_surat" class="form-control" accept="image/png, image/jpeg, image/jpg" style="background: white; border-radius: 10px; height: auto; padding: 0.5rem;">
                    <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.75rem; line-height: 1.4;">Biarkan kosong jika tidak ingin mengubah KOP. Format: JPG/PNG, Max 2MB.</p>
                    @error('kop_surat')<span style="color:var(--danger);font-size:.75rem;display:block;margin-top:8px;font-weight:600;">{{ $message }}</span>@enderror
                </div>

                {{-- Unit Identity --}}
                <div style="display: grid; grid-template-columns: 120px 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px;">Kode <span style="color:var(--danger);">*</span></label>
                        <input type="text" name="kode" value="{{ old('kode', $educationLevel->kode) }}" class="form-control {{ $errors->has('kode') ? 'is-invalid' : '' }}" maxlength="10" required style="border-radius: 10px; font-weight: 800; text-align: center; font-size: 1.1rem; height: 50px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px;">Nama Lengkap Unit <span style="color:var(--danger);">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama', $educationLevel->nama) }}" class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}" maxlength="100" required style="border-radius: 10px; font-weight: 600; height: 50px;">
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
                        SIMPAN PERUBAHAN
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

