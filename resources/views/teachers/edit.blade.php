@extends('layouts.app')
@section('title', 'Edit Guru')
@section('page-title', 'Pendaftaran Personel Baru')

@section('content')

<div style="display: flex; justify-content: center; padding: 2rem 0;">
    <div class="card" style="width: 100%; max-width: 680px; border-radius: 24px; box-shadow: var(--shadow-lg); overflow: hidden; border: 1px solid var(--border);">
        
        {{-- CARD HEADER --}}
        <div style="background: var(--primary); padding: 2.25rem; color: white; position: relative;">
            <div style="display: flex; align-items: center; gap: 1rem; position: relative; z-index: 2;">
                <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.15); border-radius: 14px; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(255,255,255,0.25);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                </div>
                <div>
                    <h3 style="font-size: 1.25rem; font-weight: 800; margin: 0; letter-spacing: 0.5px;">Edit Personel: {{ $teacher->nama_lengkap }}</h3>
                    <p style="font-size: 0.85rem; opacity: 0.75; margin-top: 4px;">Perbarui profil dan akses sistem untuk personel ini.</p>
                </div>
            </div>
        </div>

        {{-- FORM BODY --}}
        <div style="padding: 2.5rem;">
            <form action="{{ route('teachers.update', $teacher) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- SECTION 1: PROFIL PERSONAL --}}
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 2rem;">
                    <span style="font-size: 0.75rem; font-weight: 900; color: var(--primary); text-transform: uppercase; letter-spacing: 1px;">⚙️ Profil Personal</span>
                    <div style="flex: 1; height: 1px; background: var(--border);"></div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">Nama Lengkap <span style="color: var(--danger);">*</span></label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $teacher->nama_lengkap) }}" class="form-control" style="height: 48px; border-radius: 12px; font-weight: 800; border: 1.5px solid var(--border);" required>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">NIP (Opsional)</label>
                        <input type="text" name="nip" value="{{ old('nip', $teacher->nip) }}" class="form-control" style="height: 48px; border-radius: 12px; font-weight: 800; border: 1.5px solid var(--border);">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">Alamat Email Personel</label>
                        <input type="email" name="email" value="{{ old('email', $teacher->email) }}" class="form-control" style="height: 48px; border-radius: 12px; font-weight: 800; border: 1.5px solid var(--border);">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">Hubungi (WhatsApp)</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp', $teacher->no_hp) }}" class="form-control" style="height: 48px; border-radius: 12px; border: 1.5px solid var(--border);">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">Unit Kerja (Scope)</label>
                        <select name="education_level_id" class="form-control" style="height: 48px; border-radius: 12px; font-weight: 800; border: 1.5px solid var(--border);">
                            <option value="">GLOBAL / SEMUA UNIT</option>
                            @foreach($educationLevels as $level)
                                <option value="{{ $level->id }}" {{ old('education_level_id', $teacher->user?->education_level_id) == $level->id ? 'selected' : '' }}>
                                    UNIT: {{ $level->nama }} ({{ $level->kode }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div style="padding-top: 1.75rem;">
                        <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer; background: var(--bg-secondary); padding: 0.5rem 1rem; border-radius: 12px; border: 1px solid var(--border);">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $teacher->is_active) ? 'checked' : '' }} style="width: 20px; height: 20px; accent-color: var(--primary);">
                            <span style="font-size: 0.85rem; font-weight: 700; color: var(--primary);">Status Aktif</span>
                        </label>
                    </div>
                </div>

                <div style="margin-bottom: 2.5rem;">
                    <label style="display: block; font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">Alamat Domisili</label>
                    <textarea name="alamat" class="form-control" rows="2" style="border-radius: 12px; border: 1.5px solid var(--border); padding: 0.75rem 1rem;">{{ old('alamat', $teacher->alamat) }}</textarea>
                </div>

                {{-- SECTION 2: AKSES LOGIN --}}
                <div style="background: #F8FAFC; border: 1px solid var(--border); border-radius: 20px; padding: 2rem;">
                    <div style="display: flex; align-items: flex-start; gap: 1rem; margin-bottom: 2rem;">
                        <div style="width: 40px; height: 40px; background: white; color: var(--primary); border-radius: 10px; display: flex; align-items: center; justify-content: center; box-shadow: var(--shadow-sm); border: 1px solid var(--border);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/></svg>
                        </div>
                        <div>
                            <h4 style="font-size: 0.95rem; font-weight: 800; color: var(--primary); margin: 0;">Otoritas & Hak Akses</h4>
                            <p style="font-size: 0.75rem; color: var(--text-muted); margin: 0; line-height: 1.4;">Kelola jabatan personel ini di sistem.</p>
                        </div>
                    </div>

                    @if($teacher->user)
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 12px;">Posisi / Jabatan (Multiples OK)</label>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                            @foreach($roles as $role)
                            <label style="display: flex; align-items: center; gap: 0.75rem; background: white; padding: 0.75rem; border: 1.5px solid var(--border); border-radius: 12px; cursor: pointer; transition: all 0.2s;">
                                <input type="checkbox" name="roles[]" value="{{ $role->name }}" 
                                    {{ (is_array(old('roles')) && in_array($role->name, old('roles'))) || ($teacher->user->hasRole($role->name)) ? 'checked' : '' }}
                                    style="width: 18px; height: 18px; accent-color: var(--primary);">
                                <span style="font-size: 0.8rem; font-weight: 800; color: var(--primary);">{{ strtoupper(str_replace('_', ' ', $role->name)) }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div style="background: white; border: 1.5px dashed #E2E8F0; padding: 1rem; border-radius: 12px; display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 36px; height: 36px; background: #F0FDF4; color: #166534; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        </div>
                        <div>
                            <div style="font-size: 0.7rem; font-weight: 800; color: #94A3B8; text-transform: uppercase;">ID Login Terintegrasi</div>
                            <div style="font-size: 0.85rem; font-weight: 700; color: #1E293B;">{{ $teacher->user->email }}</div>
                        </div>
                    </div>
                    @else
                    <div style="background: #FFFBEB; border: 1.5px dashed #FEF3C7; padding: 1rem; border-radius: 12px; display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 36px; height: 36px; background: white; color: #D97706; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                        </div>
                        <div>
                            <div style="font-size: 0.7rem; font-weight: 800; color: #92400E; text-transform: uppercase;">Belum Ada Akun</div>
                            <div style="font-size: 0.8rem; color: #92400E;">Simpan data dengan email untuk buat akun otomatis.</div>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- SUBMIT BUTTONS --}}
                <div style="display: flex; gap: 1rem; padding-top: 2.5rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 2; height: 52px; border-radius: 30px; font-weight: 900; background: var(--primary); display: flex; align-items: center; justify-content: center; gap: 0.75rem; box-shadow: var(--shadow-lg);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        PERBARUI PERSONEL
                    </button>
                    <a href="{{ route('teachers.index') }}" class="btn btn-outline" style="flex: 1; height: 52px; border-radius: 30px; display: flex; align-items: center; justify-content: center; border: 1.5px solid var(--border); background: white; font-weight: 700;">
                        BATAL
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .form-control:focus {
        border-color: var(--primary) !important;
        box-shadow: 0 0 0 4px rgba(0, 0, 82, 0.05);
    }
</style>

@endsection
