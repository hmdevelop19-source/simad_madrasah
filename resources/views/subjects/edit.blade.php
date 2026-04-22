@extends('layouts.app')
@section('title', 'Edit Mata Pelajaran')
@section('page-title', 'Edit Mata Pelajaran')
@section('breadcrumb') <a href="{{ route('subjects.index') }}">Mata Pelajaran</a> <span>›</span> Edit @endsection

@section('content')
<div class="card" style="max-width:480px;">
    <div class="card-header"><span class="card-title">✏️ Edit: {{ $subject->nama_mapel }}</span></div>
    <div class="card-body">
        <form action="{{ route('subjects.update', $subject) }}" method="POST">
            @csrf @method('PUT')
            <div class="form-group">
                <label class="form-label">Kode Mapel <span style="color:var(--danger);">*</span></label>
                <input type="text" name="kode_mapel" value="{{ old('kode_mapel', $subject->kode_mapel) }}"
                    class="form-control" maxlength="20" required>
            </div>
            <div class="form-group">
                <label class="form-label">Nama Mata Pelajaran <span style="color:var(--danger);">*</span></label>
                <input type="text" name="nama_mapel" value="{{ old('nama_mapel', $subject->nama_mapel) }}"
                    class="form-control" maxlength="100" required>
            </div>
            <div style="display:flex;gap:.75rem;margin-top:1.5rem;">
                <button type="submit" class="btn btn-primary">💾 Perbarui</button>
                <a href="{{ route('subjects.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
