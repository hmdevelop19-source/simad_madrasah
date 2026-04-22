@extends('layouts.app')
@section('title', 'Edit Tahun Ajaran')
@section('page-title', 'Edit Tahun Ajaran')
@section('breadcrumb') <a href="{{ route('academic-years.index') }}">Tahun Ajaran</a> <span>›</span> Edit @endsection

@section('content')
<div class="card" style="max-width:500px;">
    <div class="card-header"><span class="card-title">✏️ Edit: {{ $academicYear->nama }}</span></div>
    <div class="card-body">
        <form action="{{ route('academic-years.update', $academicYear) }}" method="POST">
            @csrf @method('PUT')
            <div class="form-group">
                <label class="form-label">Nama Tahun Ajaran <span style="color:var(--danger);">*</span></label>
                <input type="text" name="nama" value="{{ old('nama', $academicYear->nama) }}"
                    class="form-control" maxlength="20" required>
            </div>
            <div class="form-group" style="background: var(--bg-secondary); padding: 1rem; border-radius: var(--radius); border: 1px dashed var(--border-dark); margin-bottom: 1.5rem;">
                <div style="display: flex; align-items: flex-start; gap: 0.75rem;">
                    <span style="font-size: 1.25rem;">ℹ️</span>
                    <div>
                        <div style="font-size: 0.875rem; font-weight: 600; color: var(--text);">Kuartal Dikelola Terpisah</div>
                        <p style="font-size: 0.775rem; color: var(--text-secondary); margin-top: 2px; line-height: 1.4;">
                            Kuartal untuk tahun ini dikelola langsung melalui halaman utama daftar Tahun Ajaran. Anda dapat mengaktifkan atau menonaktifkan kuartal tertentu di sana.
                        </p>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label style="display:flex;align-items:center;gap:.5rem;cursor:pointer;font-size:.875rem;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $academicYear->is_active) ? 'checked' : '' }}
                        style="width:16px;height:16px;accent-color:var(--primary);">
                    Tahun Ajaran Aktif
                </label>
            </div>
            <div style="display:flex;gap:.75rem;margin-top:1.5rem;">
                <button type="submit" class="btn btn-primary">💾 Perbarui</button>
                <a href="{{ route('academic-years.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
