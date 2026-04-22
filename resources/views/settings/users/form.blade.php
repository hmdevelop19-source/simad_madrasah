@extends('layouts.app')
@section('title', $isEdit ? 'Edit Akun' : 'User Baru')
@section('page-title', $isEdit ? 'Profil Pendidik / Admin' : 'Registrasi Akun')

@section('content')
<style>
    .form-card { background: white; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); overflow: hidden; padding: 3rem; }
    .label-custom { font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 0.75rem; }
    .input-custom { background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 12px 18px; font-weight: 700; color: #1e293b; width: 100%; transition: all 0.2s; font-size: 0.9rem; }
    .input-custom:focus { border-color: var(--primary); background: white; outline: none; box-shadow: 0 0 0 4px rgba(0,0,82,0.05); }
    .input-custom::placeholder { color: #cbd5e1; font-weight: 500; }
    
    .select-custom { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='m19.5 8.25-7.5 7.5-7.5-7.5'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 1rem center; background-size: 1.25rem; }
    
    .helper-text { font-size: 0.75rem; color: #94a3b8; margin-top: 8px; display: block; font-weight: 500; }
    .section-title { font-size: 0.85rem; font-weight: 900; color: var(--primary); text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #f1f5f9; padding-bottom: 8px; margin-bottom: 1.5rem; }
</style>

<div class="row justify-content-center">
    <div class="col-12 col-xl-10">
        <form action="{{ $isEdit ? route('users.update', $user->id) : route('users.store') }}" method="POST">
            @csrf
            @if($isEdit) @method('PUT') @endif

            {{-- HEADER NAV --}}
            <div class="d-flex justify-content-between align-items-center mb-5 px-2">
                <div style="flex: 1; max-width: 450px;">
                    <span style="font-size: 0.65rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1.5px; display: block; margin-bottom: 4px;">PENGELOLA DASHBOARD</span>
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: #EEF2FF; border-radius: 12px; color: var(--primary); border: 1px solid #E0E7FF;">
                            <i class="fa-solid fa-user-shield" style="font-size: 1.2rem; opacity: 0.8;"></i>
                        </div>
                        <div style="flex: 1;">
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                                class="role-title-input" 
                                placeholder="Nama Lengkap User..." 
                                style="font-size: 1.5rem; font-weight: 950; color: var(--primary); border: none; background: transparent; padding: 0; outline: none; width: 100%;"
                                required>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-3">
                    <a href="{{ route('users.index') }}" class="btn btn-light" style="border-radius: 12px; font-weight: 800; color: #64748b; padding: 12px 25px; border: 1.5px solid #e2e8f0;">
                       <i class="fa-solid fa-arrow-left me-2"></i> KEMBALI
                    </a>
                    <button type="submit" class="btn btn-primary" style="background: var(--primary); border: none; border-radius: 12px; font-weight: 900; padding: 12px 35px; box-shadow: 0 10px 20px rgba(0,0,82,0.15);">
                        <i class="fa-solid fa-floppy-disk me-2"></i> {{ $isEdit ? 'SIMPAN PERUBAHAN' : 'REKAP & AKTIFKAN' }}
                    </button>
                </div>
            </div>

            <div class="form-card">
                <div class="row g-5">
                    {{-- KIRI: DATA LOGIN --}}
                    <div class="col-md-6">
                        <h6 class="section-title">Kredensial Login</h6>
                        <div class="mb-4">
                            <label class="label-custom">Alamat Email (Digunakan untuk Login)</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="input-custom" placeholder="masukkan email aktif..." required>
                            @error('email') <span class="text-danger" style="font-size: 0.7rem; font-weight: 700; margin-top: 5px; display: block;">{{ $message }}</span> @enderror
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="label-custom">{{ $isEdit ? 'Password Baru' : 'Password Login' }}</label>
                                <input type="password" name="password" class="input-custom" placeholder="••••••••" {{ $isEdit ? '' : 'required' }}>
                                @if($isEdit)
                                    <small class="helper-text">*Kosongkan jika tidak ingin ganti password.</small>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label class="label-custom">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="input-custom" placeholder="••••••••" {{ $isEdit ? '' : 'required' }}>
                            </div>
                        </div>
                    </div>

                    {{-- KANAN: HAK AKSES --}}
                    <div class="col-md-6">
                        <h6 class="section-title">Otoritas & Penempatan</h6>
                        <div class="mb-4">
                            <label class="label-custom">Otoritas & Jabatan (Multi-Role)</label>
                            <div class="row g-3">
                                @foreach($roles as $role)
                                    <div class="col-6">
                                        <label class="d-flex align-items-center gap-2 p-3" style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; cursor: pointer; transition: 0.2s;" onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='#e2e8f0'">
                                            <input type="checkbox" name="roles[]" value="{{ $role->name }}" 
                                                {{ (is_array(old('roles')) && in_array($role->name, old('roles'))) || ($isEdit && $user->hasRole($role->name)) ? 'checked' : '' }}
                                                style="width: 18px; height: 18px; accent-color: var(--primary);">
                                            <span style="font-size: 0.8rem; font-weight: 800; color: var(--primary); text-transform: uppercase;">{{ str_replace('_', ' ', $role->name) }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <small class="helper-text">User ini dapat memiliki lebih dari satu jabatan sekaligus.</small>
                            @error('roles') <span class="text-danger" style="font-size: 0.7rem; font-weight: 700;">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="label-custom">Unit Kerja / Education Level</label>
                            <select name="education_level_id" class="input-custom select-custom">
                                <option value="">SEMUA UNIT (AKSES GLOBAL)</option>
                                @foreach($educationLevels as $level)
                                    <option value="{{ $level->id }}" 
                                        {{ (old('education_level_id') == $level->id || ($isEdit && $user->education_level_id == $level->id)) ? 'selected' : '' }}>
                                        UNIT: {{ $level->nama }} ({{ $level->kode }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="helper-text">Membatasi data santri/guru yang muncul di dashboard user.</small>
                        </div>
                    </div>
                </div>

                <div class="mt-5 d-flex justify-content-end gap-3" style="border-top: 1px solid #f1f5f9; padding-top: 2rem;">
                    <button type="submit" class="btn btn-primary" style="background: var(--primary); border: none; border-radius: 12px; font-weight: 900; padding: 12px 45px; box-shadow: 0 10px 20px rgba(0,0,82,0.15);">
                        <i class="fa-solid fa-check-double me-2"></i> {{ $isEdit ? 'PERBARUI DATA PENGGUNA' : 'SIMPAN & AKTIFKAN' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
