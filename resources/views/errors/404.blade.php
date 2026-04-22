@extends('layouts.auth')

@section('title', '404 — Halaman Tidak Ditemukan')

@section('content')
<div style="text-align:center;padding:1rem 0;">
    <div style="font-size:5rem;margin-bottom:1rem;">🔍</div>
    <h1 style="font-size:1.5rem;font-weight:700;color:#1E293B;margin-bottom:.5rem;">Halaman Tidak Ditemukan</h1>
    <p style="color:#64748B;font-size:.9rem;margin-bottom:1.75rem;">
        Halaman yang Anda cari tidak ada atau telah dipindahkan.<br>
        Periksa URL dan coba lagi.
    </p>
    <a href="{{ route('dashboard') }}"
       style="display:inline-flex;align-items:center;gap:.5rem;padding:.75rem 1.5rem;background:#4F46E5;color:white;border-radius:10px;font-weight:500;text-decoration:none;font-size:.9rem;">
        🏠 Kembali ke Dashboard
    </a>
    <p style="margin-top:1.5rem;font-size:.775rem;color:#94A3B8;">Error Code: 404 Not Found</p>
</div>
@endsection
