@extends('layouts.app')
@section('title', 'Edit Kelas')
@section('page-title', 'Edit Kelas')
@section('breadcrumb') <a href="{{ route('grade-levels.index') }}">Data Kelas</a> <span>›</span> Edit @endsection

@section('content')
<div class="card" style="max-width:480px;">
    <div class="card-header"><span class="card-title">✏️ Edit: {{ $gradeLevel->nama_tingkat }}</span></div>
    <div class="card-body">
        <form action="{{ route('grade-levels.update', $gradeLevel) }}" method="POST">
            @csrf @method('PUT')
            <div class="form-group">
                <label class="form-label">Unit Pendidikan <span style="color:var(--danger);">*</span></label>
                <select name="education_level_id" class="form-control" required>
                    @foreach($educationLevels as $level)
                        <option value="{{ $level->id }}" {{ old('education_level_id', $gradeLevel->education_level_id) == $level->id ? 'selected' : '' }}>
                            [{{ $level->kode }}] {{ $level->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Nama Kelas <span style="color:var(--danger);">*</span></label>
                <input type="text" name="nama_tingkat" value="{{ old('nama_tingkat', $gradeLevel->nama_tingkat) }}"
                    class="form-control" maxlength="50" required>
            </div>
            <div style="display:flex;gap:.75rem;margin-top:1.5rem;">
                <button type="submit" class="btn btn-primary">💾 Perbarui</button>
                <a href="{{ route('grade-levels.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
