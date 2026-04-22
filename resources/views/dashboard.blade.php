{{--
    View: dashboard.blade.php
    Halaman dashboard utama yang tampil setelah login.

    @extends('layouts.app')   → Gunakan layout master (sidebar + topbar)
    @section('content')       → Isi bagian @yield('content') di layout

    Variabel yang diterima dari DashboardController:
    - $user             : User yang sedang login (dengan relasi educationLevel)
    - $stats            : Array statistik (total_santri, total_guru, dst)
    - $activeTahunAjaran: Record tahun ajaran aktif (bisa null)
    - $educationLevels  : Collection EducationLevel (hanya untuk super_admin)
--}}
@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

@php
    $roleLabels = [
        'super_admin'    => ['🛡️', 'Super Administrator', 'var(--primary)'],
        'kepala_sekolah' => ['👔', 'Kepala Sekolah',      'var(--warning)'],
        'guru'           => ['👨‍🏫', 'Guru Mata Pelajaran', 'var(--success)'],
        'wali_kelas'     => ['🏫', 'Wali Kelas',          'var(--info)'],
    ];
    // Ambil role pertama sebagai label utama (atau super_admin jika ada)
    $allUserRoles = $user->roles->pluck('name')->toArray();
    $primaryRole  = in_array('super_admin', $allUserRoles) ? 'super_admin' : ($allUserRoles[0] ?? 'guest');
    $roleInfo     = $roleLabels[$primaryRole] ?? ['👤', ucfirst(str_replace('_', ' ', $primaryRole)), '#64748B'];
@endphp

{{-- ================================================================
     WELCOME HERO CARD
================================================================ --}}
<div style="background: linear-gradient(135deg, var(--primary) 0%, #1E1B4B 100%); border-radius: 24px; padding: 2.5rem; color: white; margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between; position: relative; overflow: hidden; box-shadow: var(--shadow-lg);">
    <div style="position:absolute; right: -50px; top: -50px; width: 250px; height: 250px; background: rgba(252, 213, 38, 0.05); border-radius: 50%;"></div>
    <div style="position:absolute; left: 20%; bottom: -30px; width: 100px; height: 100px; background: rgba(0, 176, 251, 0.1); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 1;">
        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
            <div style="width: 45px; height: 45px; background: var(--accent); color: var(--primary); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.25rem;">
                {{ substr($user->name, 0, 1) }}
            </div>
            <div>
                <p style="color: rgba(255,255,255,0.6); font-size: 0.85rem; font-weight: 500;">Ahlan wa Sahlan,</p>
                <h2 style="font-size: 1.5rem; font-weight: 700; letter-spacing: -0.5px;">{{ $user->name }}</h2>
            </div>
        </div>
        <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(255,255,255,0.1); padding: 0.5rem 1rem; border-radius: 30px; font-size: 0.8rem; border: 1px solid rgba(255,255,255,0.1);">
            <span style="color: var(--accent);">●</span>
            <span style="font-weight: 600; letter-spacing: 0.5px;">{{ strtoupper($roleInfo[1]) }}</span>
            @if($user->educationLevel)
                <span style="opacity: 0.4; margin: 0 4px;">|</span>
                <span style="color: var(--highlight);">{{ $user->educationLevel->nama }}</span>
            @endif
        </div>
    </div>

    <div style="display: none; position: relative; z-index: 1;" class="d-md-block">
        <svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.1; color: var(--accent);"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
    </div>
</div>

{{-- ================================================================
     INFO TAHUN AJARAN AKTIF
================================================================ --}}
@php
    $activeTerm = $activeTahunAjaran ? $activeTahunAjaran->activeTerm() : null;
@endphp
@if($activeTahunAjaran)
<div style="background: white; border: 1px solid var(--border); border-radius: 16px; padding: 1.25rem 1.5rem; margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between; box-shadow: var(--shadow-sm);">
    <div style="display: flex; align-items: center; gap: 1rem;">
        <div style="width: 48px; height: 48px; background: var(--primary-light); color: var(--primary); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
        </div>
        <div>
            <div style="font-size: 0.95rem; font-weight: 700; color: var(--primary);">Periode Aktif: {{ $activeTahunAjaran->nama }}</div>
            <div style="font-size: 0.8rem; color: var(--text-secondary); margin-top: 2px;">
                Fase Evaluasi: <span class="badge badge-purple" style="font-size: 0.65rem;">{{ $activeTerm?->nama ?? 'Belum Ditentukan' }}</span>
            </div>
        </div>
    </div>
    @if(auth()->user()->isSuperAdmin())
    <a href="{{ route('academic-years.index') }}" class="btn btn-outline btn-sm">Ganti Periode</a>
    @endif
</div>
@else
<div style="background: #FFFBEB; border: 1px solid #FEF3C7; border-radius: 16px; padding: 1.25rem 1.5rem; margin-bottom: 2rem; display: flex; align-items: center; gap: 1rem; color: #92400E;">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
    <div>
        <div style="font-size: 0.9rem; font-weight: 700;">Tahun Ajaran Belum Aktif</div>
        <div style="font-size: 0.8rem; opacity: 0.8;">Segera aktifkan tahun ajaran untuk memulai kegiatan akademik.</div>
    </div>
</div>
@endif

{{-- ================================================================
     STATISTIK CARDS
================================================================ --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(79, 70, 229, 0.1); color: #4F46E5;">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
        <div>
            <div class="stat-value" style="color: var(--text);">{{ number_format($stats['total_santri']) }}</div>
            <div class="stat-label">Santri Aktif</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: #D97706;">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
        </div>
        <div>
            <div class="stat-value" style="color: var(--text);">{{ number_format($stats['total_guru']) }}</div>
            <div class="stat-label">Guru Pengajar</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #059669;">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        </div>
        <div>
            <div class="stat-value" style="color: var(--text);">{{ number_format($stats['total_kelas']) }}</div>
            <div class="stat-label">Rombel Kelas</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(6, 182, 212, 0.1); color: #0891B2;">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/><path d="M8 7h6"/><path d="M8 11h8"/></svg>
        </div>
        <div>
            <div class="stat-value" style="color: var(--text);">{{ number_format($stats['total_mapel']) }}</div>
            <div class="stat-label">Mata Pelajaran</div>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; margin-top: 1rem;">
    {{-- Left: Unit Summary --}}
    @if(auth()->user()->isSuperAdmin() && $educationLevels->isNotEmpty())
    <div class="card">
        <div class="card-header">
            <span class="card-title">🏫 Sebaran Data per Unit</span>
            <a href="{{ route('education-levels.index') }}" style="font-size: 0.75rem; font-weight: 600; color: var(--highlight);">Lihat Detail</a>
        </div>
        <div class="card-body" style="padding:0;">
            <table>
                <thead>
                    <tr>
                        <th>Unit Pendidikan</th>
                        <th style="text-align:right;">Santri</th>
                        <th style="text-align:center;">Kapasitas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($educationLevels as $level)
                    <tr>
                        <td>
                            <div style="font-weight: 600; color: var(--primary);">{{ $level->nama }}</div>
                            <div style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">{{ $level->kode }}</div>
                        </td>
                        <td style="text-align:right; font-weight: 700; color: var(--text);">{{ number_format($level->students_count) }}</td>
                        <td style="text-align:center;">
                            <div style="width: 100px; height: 6px; background: var(--bg-secondary); border-radius: 10px; margin: 0 auto; overflow: hidden;">
                                <div style="width:{{ min(100, ($level->students_count/1000)*100) }}%; height: 100%; background: var(--primary);"></div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Right: Quick Actions --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">⚡ Pintasan Cepat</span>
        </div>
        <div class="card-body" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            @php
                $shortcuts = [];
                if(auth()->user()->isSuperAdmin()) {
                    $shortcuts = [
                        ['lbl'=>'Master Guru', 'icon'=>'👨‍🏫', 'route'=>'teachers.index', 'color'=>'#F59E0B'],
                        ['lbl'=>'Data Santri', 'icon'=>'🧑‍🎓', 'route'=>'students.index', 'color'=>'#10B981'],
                        ['lbl'=>'Kurikulum', 'icon'=>'📜', 'route'=>'curriculums.index', 'color'=>'#3B82F6'],
                        ['lbl'=>'Input Nilai', 'icon'=>'📝', 'route'=>'grades.index', 'color'=>'#6366F1'],
                    ];
                } else {
                    $shortcuts = [
                        ['lbl'=>'Presensi', 'icon'=>'✅', 'route'=>'attendances.index', 'color'=>'#10B981'],
                        ['lbl'=>'Input Nilai', 'icon'=>'📝', 'route'=>'grades.index', 'color'=>'#6366F1'],
                        ['lbl'=>'Kepribadian', 'icon'=>'🤝', 'route'=>'personality.index', 'color'=>'#8B5CF6'],
                        ['lbl'=>'Raport', 'icon'=>'📄', 'route'=>'reports.index', 'color'=>'#EF4444'],
                    ];
                }
            @endphp
            
            @foreach($shortcuts as $s)
            <a href="{{ Route::has($s['route']) ? route($s['route']) : '#' }}" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 1.25rem 0.5rem; border: 1.5px solid var(--border); border-radius: 16px; transition: all 0.2s ease; text-align: center; gap: 0.5rem;">
                <span style="font-size: 1.5rem;">{{ $s['icon'] }}</span>
                <span style="font-size: 0.75rem; font-weight: 700; color: var(--text-secondary);">{{ $s['lbl'] }}</span>
            </a>
            @endforeach
        </div>
    </div>
</div>

@endsection
