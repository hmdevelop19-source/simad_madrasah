<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{--
        @yield('title') → Diisi oleh halaman child dengan @section('title', 'Nama Halaman')
        Jika tidak diisi, default: 'SIMAD'
    --}}
    <title>@yield('title', \App\Models\SystemSetting::getValue('app.name', 'SIMAD')) — Sistem Informasi Madrasah</title>

    <meta name="description" content="{{ \App\Models\SystemSetting::getValue('app.name', 'SIMAD') }} — Sistem Informasi Manajemen Madrasah Terpadu">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if(\App\Models\SystemSetting::getValue('app.favicon'))
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . \App\Models\SystemSetting::getValue('app.favicon')) }}">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* ================================================================
           DESIGN SYSTEM — CSS Variables
           Semua warna, spacing, dan shadow dipusat di sini.
           Ubah di sini → semua halaman ikut berubah.
        ================================================================ */
        :root {
            /* Brand Colors (Heritage LTE) */
            --primary:       #000052;
            --primary-light: #EEF2FF;
            --primary-hover: #00003d;
            --accent:        #FCD526;
            --highlight:     #00B0FB;

            /* Semantic Colors */
            --success:  #22C55E;
            --warning:  #F59E0B;
            --danger:   #EF4444;
            --info:     #3B82F6;

            /* Neutral Palette */
            --surface:      #FFFFFF;
            --bg:           #F1F5F9;
            --bg-secondary: #F8FAFC;
            --border:       #E2E8F0;
            --border-dark:  #CBD5E1;
            --text:         #1E293B;
            --text-secondary: #475569;
            --text-muted:   #94A3B8;

            /* Sidebar - Heritage Style */
            --sidebar-bg:    #000052;
            --sidebar-item-active: rgba(252, 213, 38, 0.15);
            --sidebar-text:  rgba(255, 255, 255, 0.7);
            --sidebar-width: 260px;

            /* Spacing & Radius */
            --header-height: 72px;
            --radius-sm:  6px;
            --radius:     10px;
            --radius-lg:  16px;
            --radius-xl:  20px;

            /* Shadows */
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.1);
            --shadow:    0 4px 15px rgba(0,0,0,0.05);
            --shadow-lg: 0 10px 30px rgba(0,0,0,0.08);
        }

        /* ================================================================
           RESET & BASE
        ================================================================ */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html { font-size: 15px; -webkit-font-smoothing: antialiased; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
        }

        a { color: inherit; text-decoration: none; }

        /* Helpers & Utilities */
        .d-flex { display: flex !important; }
        .d-inline-flex { display: inline-flex !important; }
        .flex-column { flex-direction: column !important; }
        .align-items-center { align-items: center !important; }
        .justify-content-center { justify-content: center !important; }
        .justify-content-between { justify-content: space-between !important; }
        .justify-content-end { justify-content: flex-end !important; }
        .flex-wrap { flex-wrap: wrap !important; }
        .flex-1 { flex: 1 !important; }
        .gap-1 { gap: 0.25rem !important; }
        .gap-2 { gap: 0.5rem !important; }
        .gap-3 { gap: 1rem !important; }
        .ms-auto { margin-left: auto !important; }
        .me-auto { margin-right: auto !important; }
        .mb-0 { margin-bottom: 0 !important; }
        .mb-1 { margin-bottom: 0.25rem !important; }
        .mb-2 { margin-bottom: 0.5rem !important; }
        .mb-3 { margin-bottom: 1rem !important; }
        .mb-4 { margin-bottom: 1.5rem !important; }
        .mb-5 { margin-bottom: 3rem !important; }
        .mt-auto { margin-top: auto !important; }
        .w-100 { width: 100% !important; }
        .text-white { color: white !important; }
        .text-muted { color: var(--text-muted) !important; }
        .text-decoration-none { text-decoration: none !important; }
        
        .p-0 { padding: 0 !important; }
        .p-1 { padding: 0.25rem !important; }
        .p-2 { padding: 0.5rem !important; }
        .p-3 { padding: 1rem !important; }
        .p-4 { padding: 1.5rem !important; }
        .p-5 { padding: 3rem !important; }

        /* ================================================================
           SIDEBAR
        ================================================================ */
        .sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: linear-gradient(180deg, var(--sidebar-bg) 0%, #312E81 100%);
            position: fixed;
            top: 0; left: 0; bottom: 0;
            display: flex;
            flex-direction: column;
            padding: 0 0.875rem 1.25rem;
            z-index: 200;
            overflow-y: auto;
        }

        /* ── Brand ── */
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 0.75rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            margin-bottom: 1rem;
        }

        .brand-icon {
            width: 40px; height: 40px;
            background: rgba(255,255,255,0.15);
            border-radius: var(--radius);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .brand-text h2 {
            color: white;
            font-size: 1.1rem;
            font-weight: 700;
            letter-spacing: -0.3px;
        }

        .brand-text small {
            color: rgba(255,255,255,0.45);
            font-size: 0.7rem;
            display: block;
        }

        /* ── Nav Section Headers ── */
        .nav-section {
            font-size: 0.65rem;
            font-weight: 600;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.35);
            padding: 0 0.75rem;
            margin: 1.25rem 0 0.4rem;
        }

        /* ── Nav Links ── */
        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.6rem 0.75rem;
            border-radius: var(--radius);
            color: var(--sidebar-text);
            font-size: 0.875rem;
            transition: background 0.15s, color 0.15s;
            margin-bottom: 2px;
            white-space: nowrap;
        }

        .nav-link:hover {
            background: var(--sidebar-hover);
            color: white;
        }

        .nav-link.active {
            background: var(--sidebar-item-active);
            color: var(--accent);
            font-weight: 700;
            opacity: 1;
        }

        /* ── Sidebar Dropdown ── */
        .nav-dropdown {
            margin-bottom: 0.25rem;
        }
        .nav-dropdown-trigger {
            display: flex;
            align-items: center;
            padding: 0.875rem 1.25rem;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 12px;
            transition: all 0.2s ease;
            cursor: pointer;
            user-select: none;
            gap: 1rem;
        }
        .nav-dropdown-trigger:hover {
            background: rgba(255,255,255,0.05);
            color: white;
        }
        .nav-dropdown-trigger .chevron {
            margin-left: auto;
            transition: transform 0.3s ease;
            opacity: 0.5;
        }
        .nav-dropdown.open .nav-dropdown-trigger .chevron {
            transform: rotate(180deg);
            opacity: 1;
            color: var(--accent);
        }
        .nav-dropdown.open .nav-dropdown-trigger {
            color: white;
            font-weight: 600;
        }
        .nav-dropdown-content {
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            padding-left: 2.5rem;
            transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.3s ease, margin-top 0.3s ease;
            margin-top: 0;
            gap: 0.25rem;
        }
        .nav-dropdown.open .nav-dropdown-content {
            max-height: 500px; /* High enough value for children */
            opacity: 1;
            margin-top: 0.5rem;
            padding-bottom: 0.5rem;
        }
        .nav-dropdown-content .nav-link {
            padding: 0.65rem 1rem;
            font-size: 0.825rem;
            border-radius: 8px;
        }

        .nav-icon { font-size: 1rem; width: 20px; text-align: center; flex-shrink: 0; }

        /* ── Sidebar Footer ── */
        .sidebar-footer {
            margin-top: auto;
            padding: 1rem 0.75rem 0.25rem;
            border-top: 1px solid rgba(255,255,255,0.08);
        }

        .user-card {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            margin-bottom: 0.875rem;
        }

        .user-avatar {
            width: 34px; height: 34px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.8rem; font-weight: 700; color: white;
            flex-shrink: 0;
        }

        .user-name {
            font-size: 0.825rem;
            font-weight: 500;
            color: white;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-role-badge {
            font-size: 0.65rem;
            color: rgba(255,255,255,0.5);
            background: rgba(255,255,255,0.1);
            padding: 2px 7px;
            border-radius: 20px;
            display: inline-block;
            margin-top: 2px;
        }

        /* ── Logout Button ── */
        .btn-logout {
            width: 100%;
            padding: 0.55rem;
            background: rgba(239,68,68,0.15);
            color: #FCA5A5;
            border: 1px solid rgba(239,68,68,0.25);
            border-radius: var(--radius-sm);
            font-size: 0.8rem;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.15s;
        }

        .btn-logout:hover { background: rgba(239,68,68,0.3); color: white; border-color: rgba(239,68,68,0.5); }

        /* ================================================================
           MAIN CONTENT AREA
        ================================================================ */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ── Top Navbar ── */
        .topbar {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 0.875rem 1.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: var(--shadow-sm);
        }

        .topbar-left {
            display: flex;
            flex-direction: column;
        }

        .page-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text);
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .breadcrumb a { color: var(--primary); }
        .breadcrumb a:hover { text-decoration: underline; }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .topbar-date {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .tahun-ajaran-badge {
            background: var(--primary-light);
            color: var(--primary);
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        /* ── Page Content ── */
        .page-content {
            padding: 1.75rem;
            flex: 1;
        }

        /* ================================================================
           REUSABLE COMPONENTS
        ================================================================ */

        /* ── Stat Cards ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.75rem;
        }

        .stat-card {
            background: var(--surface);
            border-radius: var(--radius-lg);
            padding: 1.25rem 1.5rem;
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: transform 0.15s, box-shadow 0.15s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        .stat-icon {
            width: 46px; height: 46px;
            border-radius: var(--radius);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            line-height: 1;
            letter-spacing: -1px;
        }

        .stat-label {
            font-size: 0.775rem;
            color: var(--text-muted);
            margin-top: 3px;
        }

        .stat-trend {
            font-size: 0.7rem;
            margin-top: 4px;
            color: var(--success);
        }

        /* ── Section Card ── */
        .card {
            background: var(--surface);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
            overflow: hidden;
            margin-bottom: 1.25rem;
        }

        .card-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-title {
            font-size: 0.9375rem;
            font-weight: 600;
            color: var(--text);
        }

        .card-body { padding: 1.5rem; }

        /* ── Alert / Flash messages ── */
        .alert {
            padding: 0.875rem 1.125rem;
            border-radius: var(--radius);
            font-size: 0.875rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            border-left: 3px solid transparent;
        }

        .alert-success { background: #F0FDF4; color: #16A34A; border-color: var(--success); }
        .alert-danger  { background: #FEF2F2; color: #DC2626; border-color: var(--danger); }
        .alert-warning { background: #FFFBEB; color: #D97706; border-color: var(--warning); }
        .alert-info    { background: #EFF6FF; color: #2563EB; border-color: var(--info); }

        /* ── Buttons ── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.55rem 1.125rem;
            border-radius: var(--radius);
            font-size: 0.875rem;
            font-weight: 500;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            border: 1.5px solid transparent;
            transition: all 0.15s;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        .btn-primary:hover { background: var(--primary-hover); }

        .btn-outline {
            background: transparent;
            color: var(--text-secondary);
            border-color: var(--border-dark);
        }
        .btn-outline:hover { background: var(--bg-secondary); }

        .btn-danger { background: var(--danger); color: white; }
        .btn-danger:hover { background: #DC2626; }

        .btn-sm { padding: 0.35rem 0.75rem; font-size: 0.8rem; }

        /* ── Tables ── */
        .table-wrap { overflow-x: auto; }

        table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }

        th {
            background: var(--bg-secondary);
            text-align: left;
            padding: 0.75rem 1rem;
            font-weight: 600;
            font-size: 0.775rem;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid var(--border);
        }

        td {
            padding: 0.875rem 1rem;
            border-bottom: 1px solid var(--border);
            color: var(--text);
            vertical-align: middle;
        }

        tr:last-child td { border-bottom: none; }
        tr:hover td { background: var(--bg); }

        /* ── Badge ── */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.2rem 0.625rem;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 500;
        }

        .badge-success { background: #DCFCE7; color: #15803D; }
        .badge-danger  { background: #FEE2E2; color: #B91C1C; }
        .badge-warning { background: #FEF3C7; color: #B45309; }
        .badge-info    { background: #DBEAFE; color: #1D4ED8; }
        .badge-purple  { background: #EDE9FE; color: #6D28D9; }
        .badge-gray    { background: #F1F5F9; color: #475569; }

        /* ── Forms ── */
        .form-group { margin-bottom: 1.125rem; }

        label.form-label {
            display: block;
            font-size: 0.825rem;
            font-weight: 500;
            color: var(--text-secondary);
            margin-bottom: 0.4rem;
        }

        .form-control {
            width: 100%;
            padding: 0.625rem 0.875rem;
            border: 1.5px solid var(--border);
            border-radius: var(--radius);
            font-size: 0.875rem;
            font-family: 'Inter', sans-serif;
            color: var(--text);
            background: var(--surface);
            transition: border-color 0.15s, box-shadow 0.15s;
            outline: none;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79,70,229,0.1);
        }

        .form-control.is-invalid { border-color: var(--danger); }
        .invalid-feedback { color: var(--danger); font-size: 0.775rem; margin-top: 0.3rem; display: block; }

        /* ── Pagination ── */
        .pagination {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            justify-content: flex-end;
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--border);
        }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main-wrapper { margin-left: 0; }
        }
    </style>

    {{-- Slot untuk CSS/JS tambahan per halaman --}}
    @stack('styles')
</head>
<body>

{{-- ================================================================
     SIDEBAR NAVIGASI
     Komponen sidebar dipisah agar mudah diupdate tanpa ubah layout
================================================================ --}}
<aside class="sidebar">
    {{-- Brand --}}
    <div class="sidebar-brand d-flex align-items-center" style="height: var(--header-height); padding: 0 1.25rem; border-bottom: 1px solid rgba(255,255,255,0.05); margin-bottom: 1.5rem;">
        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-white text-decoration-none" style="gap: 14px; transition: all 0.3s ease;">
            @php 
                $sidebarFavicon = \App\Models\SystemSetting::getValue('app.favicon');
                $appShortName = \App\Models\SystemSetting::getValue('app.short_name', 'SIMAD');
                $appVersion = \App\Models\SystemSetting::getValue('app.version', 'v3.0');
            @endphp
            <div class="d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: #FCD526; border-radius: 12px; box-shadow: 0 4px 15px rgba(252, 213, 38, 0.4); transform: rotate(-5deg); overflow: hidden; flex-shrink: 0;">
                @if($sidebarFavicon)
                    <img src="{{ asset('storage/' . $sidebarFavicon) }}" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#000052" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                @endif
            </div>
            <div class="brand-text">
                <h1 style="color: white; font-size: 1.2rem; font-weight: 950; margin: 0; letter-spacing: 1px; line-height: 1;">
                    {{ substr($appShortName, 0, 2) }}<span style="color: var(--accent);">{{ substr($appShortName, 2) }}</span>
                </h1>
                <span style="font-size: 0.65rem; color: rgba(255,255,255,0.4); text-transform: uppercase; font-weight: 800; letter-spacing: 0.5px; display: block; margin-top: 4px;">{{ $appVersion }}</span>
            </div>
        </a>
    </div>

    {{-- Navigasi Utama --}}
    <div style="flex: 1; overflow-y: auto; padding: 0.5rem 0.875rem;">
        <span class="nav-section">Dashboard</span>
        @can('view-dashboard')
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg></span> 
            Pusat Informasi
        </a>
        @endcan

        {{-- ── Group: Master Utama ── --}}
        @php 
            $isMasterActive = request()->routeIs(['education-levels.*', 'teachers.*', 'students.*', 'wali-santri.*', 'grade-levels.*', 'subjects.*', 'classrooms.*']);
        @endphp
        @canany(['view-unit', 'view-guru', 'view-santri', 'view-wali', 'view-tingkat', 'view-mapel', 'view-rombel'])
        <div class="nav-dropdown {{ $isMasterActive ? 'open' : '' }}">
            <div class="nav-dropdown-trigger">
                <span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></span>
                <span>Master Utama</span>
                <span class="chevron"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg></span>
            </div>
            <div class="nav-dropdown-content">
                @can('view-unit') <a href="{{ route('education-levels.index') }}" class="nav-link {{ request()->routeIs('education-levels.*') ? 'active' : '' }}">Unit Pendidikan</a> @endcan
                @can('view-guru') <a href="{{ route('teachers.index') }}" class="nav-link {{ request()->routeIs('teachers.*') ? 'active' : '' }}">Data Master Guru</a> @endcan
                @can('view-santri') <a href="{{ route('students.index') }}" class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}">Data Master Santri</a> @endcan
                @can('view-wali') <a href="{{ route('wali-santri.index') }}" class="nav-link {{ request()->routeIs('wali-santri.*') ? 'active' : '' }}">Data Wali Santri</a> @endcan
                @can('view-tingkat') <a href="{{ route('grade-levels.index') }}" class="nav-link {{ request()->routeIs('grade-levels.*') ? 'active' : '' }}">Tingkat Kelas</a> @endcan
                @can('view-mapel') <a href="{{ route('subjects.index') }}" class="nav-link {{ request()->routeIs('subjects.*') ? 'active' : '' }}">Mata Pelajaran</a> @endcan
                @can('view-rombel') <a href="{{ route('classrooms.index') }}" class="nav-link {{ request()->routeIs('classrooms.*') ? 'active' : '' }}">Rombel Kelas</a> @endcan
            </div>
        </div>
        @endcanany

        {{-- ── Group: Akademik ── --}}
        @php 
            $isAkademikActive = request()->routeIs(['academic-years.*', 'academic-terms.*', 'curriculums.*', 'student-placements.*', 'rollover.*']);
        @endphp
        @canany(['view-tahun', 'view-kuartal', 'view-katalog', 'view-distribusi', 'view-rollover'])
        <div class="nav-dropdown {{ $isAkademikActive ? 'open' : '' }}">
            <div class="nav-dropdown-trigger">
                <span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/><path d="M8 7h6"/><path d="M8 11h8"/></svg></span>
                <span>Akademik</span>
                <span class="chevron"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg></span>
            </div>
            <div class="nav-dropdown-content">
                @can('view-tahun') <a href="{{ route('academic-years.index') }}" class="nav-link {{ request()->routeIs('academic-years.*') ? 'active' : '' }}">Tahun Ajaran</a> @endcan
                @can('view-kuartal') <a href="{{ route('academic-terms.index') }}" class="nav-link {{ request()->routeIs('academic-terms.*') ? 'active' : '' }}">Manajemen Kuartal</a> @endcan
                @can('view-katalog') <a href="{{ route('curriculums.index') }}" class="nav-link {{ request()->routeIs('curriculums.*') ? 'active' : '' }}">Katalog Kurikulum</a> @endcan
                @can('view-distribusi') <a href="{{ route('student-placements.index') }}" class="nav-link {{ request()->routeIs('student-placements.*') ? 'active' : '' }}">Distribusi Kelas</a> @endcan
                @can('view-rollover') <a href="{{ route('rollover.index') }}" class="nav-link {{ request()->routeIs('rollover.*') ? 'active' : '' }}">Wizard Rollover</a> @endcan
            </div>
        </div>
        @endcanany

        {{-- ── Group: Evaluasi ── --}}
        @php 
            $isEvaluasiActive = request()->routeIs(['attendances.*', 'grades.*', 'personality.*', 'reports.*']);
        @endphp
        @canany(['view-presensi', 'view-nilai', 'view-kepribadian', 'view-raport'])
        <div class="nav-dropdown {{ $isEvaluasiActive ? 'open' : '' }}">
            <div class="nav-dropdown-trigger">
                <span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></span>
                <span>Evaluasi & Nilai</span>
                <span class="chevron"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg></span>
            </div>
            <div class="nav-dropdown-content">
                @can('view-presensi') <a href="{{ route('attendances.index') }}" class="nav-link {{ request()->routeIs('attendances.*') ? 'active' : '' }}">Presensi Santri</a> @endcan
                @can('view-nilai') <a href="{{ route('grades.index') }}" class="nav-link {{ request()->routeIs('grades.*') ? 'active' : '' }}">Input Nilai Mapel</a> @endcan
                @can('view-kepribadian') <a href="{{ route('personality.index') }}" class="nav-link {{ request()->routeIs('personality.*') ? 'active' : '' }}">Nilai Kepribadian</a> @endcan
                @can('view-raport') <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">Cetak E-Raport</a> @endcan
            </div>
        </div>
        @endcanany

        {{-- ── Group: Pengaturan ── --}}
        @php 
            $isSettingActive = request()->routeIs(['settings.*']);
        @endphp
        @canany(['view-hak-akses', 'view-role', 'view-user', 'view-profil-induk', 'view-profil-app'])
        <div class="nav-dropdown {{ $isSettingActive ? 'open' : '' }}">
            <div class="nav-dropdown-trigger">
                <span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg></span>
                <span>Sistem & Keamanan</span>
                <span class="chevron"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg></span>
            </div>
            <div class="nav-dropdown-content">
                @can('view-hak-akses') <a href="{{ route('permissions.index') }}" class="nav-link {{ request()->routeIs('permissions.*') ? 'active' : '' }}">Hak Akses</a> @endcan
                @can('view-role') <a href="{{ route('roles.index') }}" class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">Manajemen Role</a> @endcan
                @can('view-user') <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">Manajemen User</a> @endcan
                @can('view-profil-induk') <a href="{{ route('settings.school') }}" class="nav-link {{ request()->routeIs('settings.school') ? 'active' : '' }}">Profil Induk</a> @endcan
                @can('view-profil-app') <a href="{{ route('settings.app') }}" class="nav-link {{ request()->routeIs('settings.app') ? 'active' : '' }}">Profil Aplikasi</a> @endcan
            </div>
        </div>
        @endcanany
    </div>
    </div>
</aside>

{{-- ================================================================
     KONTEN UTAMA
================================================================ --}}
<div class="main-wrapper">

    {{-- ── Top Navbar ── --}}
    <header class="topbar" style="background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border-bottom: 1px solid rgba(0,0,82,0.08); padding: 0 2rem; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 1000; box-shadow: 0 4px 20px rgba(0,0,0,0.03); height: var(--header-height);">
        <div style="display: flex; align-items: center; gap: 2.5rem; flex: 1;">
            <div class="topbar-left">
                <div class="page-title" style="font-size: 1.15rem; font-weight: 800; color: var(--primary); letter-spacing: -0.5px;">@yield('page-title', 'Dashboard')</div>
                <div class="breadcrumb" style="font-size: 0.7rem; font-weight: 600; color: var(--text-muted); margin-top: 2px;">
                    <a href="{{ route('dashboard') }}" style="color: var(--text-muted); opacity: 0.7;">🏠 Dashboard</a>
                    @hasSection('breadcrumb')
                        <span style="opacity: 0.4;">/</span>
                        @yield('breadcrumb')
                    @endif
                </div>
            </div>

            {{-- SEARCH MOCKUP (Visual Only) --}}
            <div style="flex: 1; max-width: 400px; position: relative; display: none; @media (min-width: 1024px) { display: block; }">
                <input type="text" placeholder="Cari data, menu, atau bantuan..." style="width: 100%; height: 42px; background: #F1F5F9; border: 1px solid #E2E8F0; border-radius: 12px; padding: 0 1rem 0 2.75rem; font-size: 0.8rem; font-weight: 500; font-family: inherit; transition: 0.2s; outline: none;" onfocus="this.style.background='white'; this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 0 4px rgba(0,0,82,0.05)'" onblur="this.style.background='#F1F5F9'; this.style.borderColor='#E2E8F0'; this.style.boxShadow='none'">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%);"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                <div style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); background: white; border: 1px solid #E2E8F0; color: #94A3B8; font-size: 0.6rem; font-weight: 800; padding: 2px 6px; border-radius: 4px; pointer-events: none;">⌘ K</div>
            </div>
        </div>

        <div class="topbar-right" style="display: flex; align-items: center; gap: 1.5rem;">
            {{-- STATUS CAPSULE --}}
            <div style="display: flex; align-items: center; gap: 0.5rem; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 30px; padding: 0.35rem; padding-right: 1rem;">
                @php 
                    $activeTahun = \App\Models\AcademicYear::where('is_active', true)->first(); 
                    $activeTerm  = $activeTahun ? $activeTahun->activeTerm() : null;
                @endphp
                @if($activeTahun)
                <div style="background: var(--primary); color: white; padding: 0.4rem 0.875rem; border-radius: 30px; font-size: 0.65rem; font-weight: 800; display: flex; align-items: center; gap: 0.5rem; letter-spacing: 0.5px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.8;"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                    {{ $activeTahun->nama }}
                </div>
                <div style="font-size: 0.7rem; font-weight: 700; color: var(--primary); opacity: 0.8; margin-left: 0.25rem;">
                    {{ $activeTerm?->nama ?? 'No Term' }}
                </div>
                @endif
                <div style="width: 1px; height: 16px; background: #E2E8F0; margin: 0 0.5rem;"></div>
                <div style="font-size: 0.7rem; font-weight: 700; color: var(--text-muted); display: flex; align-items: center; gap: 0.4rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    {{ now()->locale('id')->isoFormat('D MMM Y') }}
                </div>
            </div>

            {{-- PROFILE & ACTIONS --}}
            <div style="display: flex; align-items: center; gap: 1rem; border-left: 1px solid #E2E8F0; padding-left: 1.5rem;">
                <div style="text-align: right; line-height: 1.1;">
                    <div style="font-size: 0.85rem; font-weight: 800; color: var(--primary);">{{ auth()->user()->name }}</div>
                    <div style="font-size: 0.6rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">{{ auth()->user()->roles->pluck('name')->map(fn($n) => strtoupper(str_replace('_', ' ', $n)))->implode(', ') }}</div>
                </div>
                
                <div style="width: 40px; height: 40px; background: white; border: 2px solid var(--border); border-radius: 12px; display: flex; align-items: center; justify-content: center; overflow: hidden; box-shadow: var(--shadow-sm);">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=000052&color=FCD526&bold=true" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" style="background: #FEF2F2; color: #EF4444; width: 36px; height: 36px; border-radius: 10px; border: 1px solid #FEE2E2; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: 0.2s;" onmouseover="this.style.background='#FEE2E2'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='#FEF2F2'; this.style.transform='translateY(0)'" title="Sign Out">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </header>

    {{-- ── Flash Messages (global, tampil di semua halaman) ── --}}
    <div style="padding: 0 1.75rem; margin-top: 1.25rem;">
        @if(session('success'))
            <div class="alert alert-success" style="border-radius: 14px; font-weight: 600; border: none; box-shadow: 0 4px 15px rgba(34,197,94,0.1);">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger" style="border-radius: 14px; font-weight: 600; border: none; box-shadow: 0 4px 15px rgba(239,68,68,0.1);">❌ {{ session('error') }}</div>
        @endif
        @if(session('warning'))
            <div class="alert alert-warning" style="border-radius: 14px; font-weight: 600; border: none; box-shadow: 0 4px 15px rgba(245,158,11,0.1);">⚠️ {{ session('warning') }}</div>
        @endif
    </div>

    {{-- ── Main Page Content ── --}}
    <main class="page-content" style="padding-bottom: 4rem;">
        @yield('content')
    </main>

    {{-- ── Footer ── --}}
    <footer style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border-top: 1px solid var(--border); padding: 1rem 2rem; display: flex; align-items: center; justify-content: space-between; position: sticky; bottom: 0; z-index: 900; margin-top: auto;">
        <div style="display: flex; align-items: center; gap: 1.5rem;">
            <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 500;">
                &copy; {{ date('Y') }} <span style="font-weight: 800; color: var(--primary);">{{ \App\Models\SystemSetting::getValue('app.name', 'SIMAD Terpadu') }}</span>. {{ \App\Models\SystemSetting::getValue('app.footer', 'Al-Hikmah Premium v3.0') }}
            </div>
            <div style="width: 4px; height: 4px; background: var(--border-dark); border-radius: 50%;"></div>
            <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600; display: flex; align-items: center; gap: 0.4rem;">
                <span style="background: #F1F5F9; padding: 2px 8px; border-radius: 20px; color: var(--primary);">Environment: Production</span>
            </div>
        </div>
        <div style="display: flex; align-items: center; gap: 1.5rem;">
            <div style="font-size: 0.7rem; font-weight: 800; color: #94A3B8; text-transform: uppercase; letter-spacing: 1px;">
                Heritage LTE Architecture
            </div>
            <div style="height: 20px; width: 1px; background: var(--border);"></div>
            <div style="display: flex; gap: 0.75rem;">
                <div style="width: 28px; height: 28px; background: var(--bg-secondary); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--text-muted);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <div style="width: 28px; height: 28px; background: var(--bg-secondary); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--text-muted);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" x2="12.01" y1="17" y2="17"/></svg>
                </div>
            </div>
        </div>
    </footer>
    {{-- Sidebar Toggle Script --}}
    <script>
        document.querySelectorAll('.nav-dropdown-trigger').forEach(trigger => {
            trigger.addEventListener('click', () => {
                const dropdown = trigger.closest('.nav-dropdown');
                const isOpen = dropdown.classList.contains('open');

                // Close all other dropdowns
                document.querySelectorAll('.nav-dropdown').forEach(d => {
                    if (d !== dropdown) d.classList.remove('open');
                });

                // Toggle current dropdown
                dropdown.classList.toggle('open', !isOpen);
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
