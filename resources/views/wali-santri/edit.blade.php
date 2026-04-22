@extends('layouts.app')
@section('title', 'Edit Wali Santri')
@section('page-title', 'Edit Wali Santri')
@section('breadcrumb') <a href="{{ route('wali-santri.index') }}">Wali Santri</a> <span>›</span> Edit @endsection

@section('content')
<div class="card" style="max-width:660px;">
    <div class="card-header"><span class="card-title">✏️ Edit: {{ $waliSantri->nama_lengkap }}</span></div>
    <div class="card-body">
        <form action="{{ route('wali-santri.update', $waliSantri) }}" method="POST">
            @csrf @method('PUT')
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">Nama Lengkap <span style="color:var(--danger);">*</span></label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $waliSantri->nama_lengkap) }}" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">NIK <span style="color:var(--danger);">*</span></label>
                    <input type="text" name="nik" value="{{ old('nik', $waliSantri->nik) }}"
                        class="form-control {{ $errors->has('nik') ? 'is-invalid' : '' }}" maxlength="16" required>
                    @error('nik')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">No. WhatsApp <span style="color:var(--danger);">*</span></label>
                    <input type="text" name="no_whatsapp" value="{{ old('no_whatsapp', $waliSantri->no_whatsapp) }}"
                        class="form-control {{ $errors->has('no_whatsapp') ? 'is-invalid' : '' }}" required>
                    @error('no_whatsapp')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Hubungan Keluarga <span style="color:var(--danger);">*</span></label>
                    <select name="hubungan_keluarga" class="form-control" required>
                        @foreach(['Ayah','Ibu','Kakek','Nenek','Paman','Bibi','Wali Lainnya'] as $hub)
                        <option value="{{ $hub }}" {{ old('hubungan_keluarga', $waliSantri->hubungan_keluarga) === $hub ? 'selected' : '' }}>{{ $hub }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Pendidikan Terakhir <span style="color:var(--danger);">*</span></label>
                    <select name="pendidikan_terakhir" class="form-control" required>
                        @foreach(['SD/MI','SMP/MTs','SMA/MA','D3','S1','S2','S3','Lainnya'] as $pend)
                        <option value="{{ $pend }}" {{ old('pendidikan_terakhir', $waliSantri->pendidikan_terakhir) === $pend ? 'selected' : '' }}>{{ $pend }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Pekerjaan <span style="color:var(--danger);">*</span></label>
                    <input type="text" name="pekerjaan" value="{{ old('pekerjaan', $waliSantri->pekerjaan) }}" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Penghasilan Bulanan <span style="color:var(--danger);">*</span></label>
                    <select name="penghasilan_bulanan" class="form-control" required>
                        @foreach(['< 1 Juta','1-3 Juta','3-5 Juta','5-10 Juta','> 10 Juta'] as $pg)
                        <option value="{{ $pg }}" {{ old('penghasilan_bulanan', $waliSantri->penghasilan_bulanan) === $pg ? 'selected' : '' }}>{{ $pg }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">Alamat Lengkap <span style="color:var(--danger);">*</span></label>
                    <textarea name="alamat_lengkap" class="form-control" rows="3" required>{{ old('alamat_lengkap', $waliSantri->alamat_lengkap) }}</textarea>
                </div>
            </div>
            <div style="display:flex;gap:.75rem;margin-top:1.5rem;">
                <button type="submit" class="btn btn-primary">💾 Perbarui</button>
                <a href="{{ route('wali-santri.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
