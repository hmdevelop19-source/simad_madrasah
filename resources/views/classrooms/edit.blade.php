@extends('layouts.app')
@section('title', 'Edit Rombel')
@section('page-title', 'Edit Rombel')
@section('breadcrumb') <a href="{{ route('classrooms.index') }}">Rombel Kelas</a> <span>›</span> Edit @endsection

@section('content')
<div class="card" style="max-width:500px;">
    <div class="card-header"><span class="card-title">✏️ Edit: {{ $classroom->nama_kelas }}</span></div>
    <div class="card-body">
        <form action="{{ route('classrooms.update', $classroom) }}" method="POST">
            @csrf @method('PUT')
            <div class="form-group">
                <label class="form-label">Kelas <span style="color:var(--danger);">*</span></label>
                <select name="grade_level_id" class="form-control" required>
                    @foreach($gradeLevels as $gl)
                    <option value="{{ $gl->id }}" {{ old('grade_level_id', $classroom->grade_level_id) == $gl->id ? 'selected' : '' }}>
                        [{{ $gl->educationLevel->kode }}] {{ $gl->nama_tingkat }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Nama Rombel <span style="color:var(--danger);">*</span></label>
                <input type="text" name="nama_kelas" value="{{ old('nama_kelas', $classroom->nama_kelas) }}"
                    class="form-control" maxlength="50" required>
            </div>
            <div class="form-group">
                <label class="form-label">Wali Kelas (Opsional)</label>
                <select name="wali_kelas_id" class="form-control">
                    <option value="">-- Tidak Ada --</option>
                    @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}" {{ old('wali_kelas_id', $classroom->wali_kelas_id) == $teacher->id ? 'selected' : '' }}>
                        {{ $teacher->nama_lengkap }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex;gap:.75rem;margin-top:1.5rem;">
                <button type="submit" class="btn btn-primary">💾 Perbarui</button>
                <a href="{{ route('classrooms.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
