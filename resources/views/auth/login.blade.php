{{--
    View: auth/login.blade.php
    Extends layout auth yang minimalis (card putih di atas gradient).
    Dibandingkan versi lama (standalone HTML), versi ini jauh lebih clean:
    - Tidak ada duplikasi CSS/HTML boilerplate
    - Hanya berisi konten yang spesifik untuk halaman login
--}}
@extends('layouts.auth')

@section('title', 'Login')

@section('content')

    {{-- ===== LOGO & JUDUL ===== --}}
    <div style="display:flex;flex-direction:column;align-items:center;margin-bottom:2rem;">
        <div style="width:64px;height:64px;background:linear-gradient(135deg,#4F46E5,#3730A3);border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:28px;margin-bottom:1rem;box-shadow:0 8px 24px rgba(79,70,229,0.3);">
            🏫
        </div>
        <h1 style="font-size:1.75rem;font-weight:700;color:#1E293B;letter-spacing:-0.5px;">SIMAD</h1>
        <p style="color:#64748B;font-size:0.875rem;margin-top:0.25rem;text-align:center;">Sistem Informasi Manajemen Madrasah Terpadu</p>
    </div>

    {{-- ===== ALERT MESSAGES ===== --}}
    @if ($errors->any())
        <div class="alert alert-danger">⚠️ {{ $errors->first() }}</div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">✅ {{ session('success') }}</div>
    @endif

    {{-- ===== FORM LOGIN ===== --}}
    <form action="{{ route('login.post') }}" method="POST">
        @csrf

        {{-- Email --}}
        <div style="margin-bottom:1.25rem;">
            <label style="display:block;font-size:.875rem;font-weight:500;color:#1E293B;margin-bottom:.5rem;">Email</label>
            <div style="position:relative;">
                <span style="position:absolute;left:1rem;top:50%;transform:translateY(-50%);color:#94A3B8;">✉️</span>
                <input type="email" name="email" id="email"
                    value="{{ old('email') }}"
                    placeholder="superadmin@simad.sch.id"
                    required autofocus autocomplete="email"
                    style="width:100%;padding:.75rem 1rem .75rem 2.75rem;border:1.5px solid #E2E8F0;border-radius:10px;font-size:.9375rem;color:#1E293B;background:#F8FAFC;outline:none;font-family:Inter,sans-serif;transition:border-color .2s,box-shadow .2s;"
                    onfocus="this.style.borderColor='#4F46E5';this.style.boxShadow='0 0 0 3px rgba(79,70,229,.12)'"
                    onblur="this.style.borderColor='#E2E8F0';this.style.boxShadow='none'">
            </div>
            @error('email')<p style="color:#EF4444;font-size:.8rem;margin-top:.375rem;">{{ $message }}</p>@enderror
        </div>

        {{-- Password --}}
        <div style="margin-bottom:1.25rem;">
            <label style="display:block;font-size:.875rem;font-weight:500;color:#1E293B;margin-bottom:.5rem;">Password</label>
            <div style="position:relative;">
                <span style="position:absolute;left:1rem;top:50%;transform:translateY(-50%);color:#94A3B8;">🔑</span>
                <input type="password" name="password" id="password"
                    placeholder="••••••••"
                    required autocomplete="current-password"
                    style="width:100%;padding:.75rem 2.75rem .75rem 2.75rem;border:1.5px solid #E2E8F0;border-radius:10px;font-size:.9375rem;color:#1E293B;background:#F8FAFC;outline:none;font-family:Inter,sans-serif;transition:border-color .2s,box-shadow .2s;"
                    onfocus="this.style.borderColor='#4F46E5';this.style.boxShadow='0 0 0 3px rgba(79,70,229,.12)'"
                    onblur="this.style.borderColor='#E2E8F0';this.style.boxShadow='none'">
                <button type="button" 
                    style="position:absolute;right:1rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;padding:0;font-size:1.1rem;color:#94A3B8;display:flex;align-items:center;"
                    onclick="const pwd = document.getElementById('password'); const isPass = pwd.type === 'password'; pwd.type = isPass ? 'text' : 'password'; this.innerHTML = isPass ? '🙈' : '👁️';"
                    title="Tampilkan/Sembunyikan password">
                    👁️
                </button>
            </div>
        </div>

        {{-- Remember Me --}}
        <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:1.5rem;">
            <input type="checkbox" id="remember" name="remember" style="width:16px;height:16px;accent-color:#4F46E5;cursor:pointer;">
            <label for="remember" style="font-size:.875rem;color:#64748B;cursor:pointer;">Ingat saya selama 30 hari</label>
        </div>

        {{-- Submit --}}
        <button type="submit" id="btnLogin"
            style="width:100%;padding:.875rem;background:linear-gradient(135deg,#4F46E5,#3730A3);color:white;border:none;border-radius:10px;font-size:1rem;font-weight:600;font-family:Inter,sans-serif;cursor:pointer;box-shadow:0 4px 16px rgba(79,70,229,.35);transition:transform .15s,box-shadow .15s;"
            onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 8px 24px rgba(79,70,229,.45)'"
            onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 16px rgba(79,70,229,.35)'">
            Masuk ke SIMAD
        </button>
    </form>

    <div style="text-align:center;margin-top:1.5rem;padding-top:1.5rem;border-top:1px solid #E2E8F0;color:#94A3B8;font-size:.8125rem;">
        © {{ date('Y') }} SIMAD — Madrasah Diniyah Management System
    </div>

@endsection
