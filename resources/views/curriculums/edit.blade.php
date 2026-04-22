@extends('layouts.app')
@section('title', 'Edit Kurikulum')
@section('page-title', 'Edit Kurikulum')
@section('breadcrumb') <a href="{{ route('curriculums.index') }}">Kurikulum</a> <span>›</span> Edit @endsection

@section('content')
<div class="card" style="max-width:560px;">
    <div class="card-header"><span class="card-title">✏️ Edit Entri Kurikulum</span></div>
    <div class="card-body">
        {{-- Info readonly --}}
        <div style="background:#F8FAFC;border:1px solid var(--border);border-radius:10px;padding:1rem;margin-bottom:1.25rem;">
            <p style="font-size:.8rem;color:var(--text-muted);margin-bottom:.5rem;">📌 Data yang dikaitkan (tidak dapat diubah di sini)</p>
            <div style="font-size:.875rem;display:grid;grid-template-columns:1fr 1fr;gap:.5rem;">
                <div><span style="color:var(--text-muted);">Tahun Ajaran:</span> <strong>{{ $curriculum->academicYear?->nama }}</strong></div>
                <div><span style="color:var(--text-muted);">Periode:</span> <strong>{{ $curriculum->academicYear?->periode }}</strong></div>
                <div><span style="color:var(--text-muted);">Tingkat:</span> <strong>{{ $curriculum->gradeLevel?->nama_tingkat }}</strong></div>
                <div><span style="color:var(--text-muted);">Mapel:</span> <strong>{{ $curriculum->subject?->nama_mapel }}</strong></div>
            </div>
        </div>

        <form action="{{ route('curriculums.update', $curriculum) }}" method="POST">
            @csrf @method('PUT')

            {{-- Hidden fields to pass required validation --}}
            <input type="hidden" name="academic_year_id" value="{{ $curriculum->academic_year_id }}">
            <input type="hidden" name="grade_level_id" value="{{ $curriculum->grade_level_id }}">
            <input type="hidden" name="subject_id" value="{{ $curriculum->subject_id }}">

            <div class="form-group">
                <label class="form-label">KKM (Kriteria Ketuntasan Minimal) <span style="color:var(--danger);">*</span></label>
                <input type="number" name="kkm" value="{{ old('kkm', $curriculum->kkm) }}"
                    class="form-control" min="0" max="100" required>
                <small style="color:var(--text-muted);font-size:.775rem;">Nilai antara 0-100</small>
            </div>
            <div style="display:flex;gap:.75rem;margin-top:1.5rem;">
                <button type="submit" class="btn btn-primary">💾 Perbarui KKM</button>
                <a href="{{ route('curriculums.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
