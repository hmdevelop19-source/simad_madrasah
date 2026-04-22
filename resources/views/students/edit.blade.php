@extends('layouts.app')
@section('title', 'Edit Data Santri')
@section('page-title', 'Edit Data Santri')
@section('breadcrumb') <a href="{{ route('students.index') }}">Data Santri</a> <span>›</span> Edit @endsection

@section('content')
<div class="card" style="max-width:700px;">
    <div class="card-header"><span class="card-title">✏️ Edit: {{ $student->nama_lengkap }}</span></div>
    <div class="card-body">
        <form action="{{ route('students.update', $student) }}" method="POST">
            @csrf @method('PUT')

            <p style="font-size:.875rem;font-weight:600;color:var(--text-muted);margin-bottom:1rem;text-transform:uppercase;letter-spacing:.05em;">Identitas Santri</p>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">Nama Lengkap <span style="color:var(--danger);">*</span></label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $student->nama_lengkap) }}"
                        class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">NIK <span style="color:var(--danger);">*</span></label>
                    <input type="text" name="nik" value="{{ old('nik', $student->nik) }}"
                        class="form-control {{ $errors->has('nik') ? 'is-invalid' : '' }}" maxlength="16" required>
                    @error('nik')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">No. KK <span style="color:var(--danger);">*</span></label>
                    <input type="text" name="no_kk" value="{{ old('no_kk', $student->no_kk) }}" class="form-control" maxlength="16" required>
                </div>
                <div class="form-group">
                    <label class="form-label">NISN</label>
                    <input type="text" name="nisn" value="{{ old('nisn', $student->nisn) }}"
                        class="form-control {{ $errors->has('nisn') ? 'is-invalid' : '' }}" maxlength="10">
                    @error('nisn')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Tempat Lahir <span style="color:var(--danger);">*</span></label>
                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $student->tempat_lahir) }}" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Lahir <span style="color:var(--danger);">*</span></label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $student->tanggal_lahir?->format('Y-m-d')) }}" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Jenis Kelamin <span style="color:var(--danger);">*</span></label>
                    <select name="jenis_kelamin" class="form-control" required>
                        <option value="L" {{ old('jenis_kelamin', $student->jenis_kelamin) === 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                        <option value="P" {{ old('jenis_kelamin', $student->jenis_kelamin) === 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Status Aktif <span style="color:var(--danger);">*</span></label>
                    <select name="status_aktif" class="form-control" required>
                        @foreach($statusOptions as $status)
                        <option value="{{ $status }}" {{ old('status_aktif', $student->status_aktif) === $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <hr style="border:none;border-top:1px solid var(--border);margin:1.5rem 0;">

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div class="form-group">
                    <label class="form-label">Unit Pendidikan <span style="color:var(--danger);">*</span></label>
                    <select name="current_level_id" class="form-control" required>
                        @foreach($educationLevels as $level)
                        <option value="{{ $level->id }}" {{ old('current_level_id', $student->current_level_id) == $level->id ? 'selected' : '' }}>
                            [{{ $level->kode }}] {{ $level->nama }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Wali Santri</label>
                    <select name="wali_id" class="form-control">
                        <option value="">– Tidak Ada –</option>
                        @foreach($waliList as $wali)
                        <option value="{{ $wali->id }}" {{ old('wali_id', $student->wali_id) == $wali->id ? 'selected' : '' }}>
                            {{ $wali->nama_lengkap }} ({{ $wali->hubungan_keluarga }})
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="display:flex;gap:.75rem;margin-top:1.5rem;">
                <button type="submit" class="btn btn-primary">💾 Perbarui</button>
                <a href="{{ route('students.show', $student) }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
