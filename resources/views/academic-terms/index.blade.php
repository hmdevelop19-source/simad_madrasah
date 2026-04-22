@extends('layouts.app')
@section('title', 'Manajemen Kuartal')
@section('page-title', 'Kuartal / Term Evaluasi')

@section('content')
<div style="max-width: 900px;">
    
    {{-- Header Info Tahun Aktif --}}
    <div style="background: white; border: 1px solid var(--border); border-radius: 16px; padding: 1.5rem; margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between; box-shadow: var(--shadow-sm);">
        <div style="display: flex; align-items: center; gap: 1.25rem;">
            <div style="width: 56px; height: 56px; background: var(--primary-light); color: var(--primary); border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="M10 14h4"/><path d="M12 12v4"/></svg>
            </div>
            <div>
                <div style="font-size: 0.8rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Tahun Ajaran Aktif</div>
                <div style="font-size: 1.25rem; font-weight: 800; color: var(--primary); margin-top: 2px;">
                    {{ $activeYear ? $activeYear->nama : 'TIDAK ADA' }}
                </div>
            </div>
        </div>
        @if($activeYear)
        <div style="text-align: right;">
            <span class="badge" style="background: #D1FAE5; color: #065F46; font-weight: 700; border: 1px solid #A7F3D0; padding: 0.5rem 1rem; border-radius: 30px;">
                STATUS: SISTEM BERJALAN
            </span>
        </div>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom: 2rem; border-radius: 12px; border: none; background: #ECFDF5; color: #065F46; font-weight: 600;">
            {{ session('success') }}
        </div>
    @endif

    @if(!$activeYear)
        <div class="card" style="border: 1px dashed var(--border-dark); background: #FFFBEB;">
            <div style="text-align:center; padding: 4rem 2rem; color: #92400E;">
                <div style="width: 80px; height: 80px; background: #FEF3C7; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                </div>
                <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 0.5rem;">Tahun Ajaran Belum Aktif</h3>
                <p style="font-size: 0.9rem; max-width: 400px; margin: 0 auto 1.5rem; line-height: 1.6;">
                    Anda harus mengaktifkan salah satu Tahun Ajaran terlebih dahulu untuk mengelola kuartal penilaian.
                </p>
                <a href="{{ route('academic-years.index') }}" class="btn btn-primary" style="background: #D97706; border: none; border-radius: 30px; padding: 0.75rem 2rem; font-weight: 700;">
                    Buka Tahun Ajaran
                </a>
            </div>
        </div>
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(400px, 1fr)); gap: 1.5rem;">
            @foreach($terms as $term)
            <div class="card" style="border: 2px solid {{ $term->is_active ? 'var(--primary)' : 'var(--border)' }}; transition: all 0.3s ease; position: relative; overflow: hidden;">
                @if($term->is_active)
                <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: var(--primary); transform: rotate(45deg); display: flex; align-items: flex-end; justify-content: center; padding-bottom: 5px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="transform: rotate(-45deg);"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                @endif

                <div class="card-body" style="padding: 2rem;">
                    <div style="display: flex; gap: 1.5rem; align-items: center;">
                        <div style="width: 64px; height: 64px; background: {{ $term->is_active ? 'var(--primary)' : 'var(--bg-secondary)' }}; color: {{ $term->is_active ? 'white' : 'var(--text-muted)' }}; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: 800; border: 1px solid {{ $term->is_active ? 'var(--primary)' : 'var(--border)' }};">
                            Q{{ $loop->iteration }}
                        </div>
                        <div style="flex: 1;">
                            <h3 style="font-size: 1.15rem; font-weight: 800; color: {{ $term->is_active ? 'var(--primary)' : 'var(--text)' }}; margin: 0;">
                                {{ $term->nama }}
                            </h3>
                            <p style="font-size: 0.85rem; color: var(--text-secondary); margin-top: 4px; line-height: 1.4;">
                                Periode penilaian ke-{{ $loop->iteration }} untuk tahun akademik {{ $activeYear->nama }}.
                            </p>
                        </div>
                    </div>

                    <div style="margin-top: 2rem; pt: 1.5rem; border-top: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between;">
                        <div>
                            @if($term->is_active)
                                <span style="display: flex; align-items: center; gap: 0.4rem; color: var(--success); font-weight: 700; font-size: 0.85rem;">
                                    <span style="width: 8px; height: 8px; background: var(--success); border-radius: 50%; box-shadow: 0 0 8px var(--success);"></span>
                                    SEDANG BERJALAN
                                </span>
                            @else
                                <span style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600;">MENUNGGU AKTIVASI</span>
                            @endif
                        </div>

                        @if(!$term->is_active)
                            @can('edit-kuartal')
                            <form action="{{ route('academic-terms.set-active', $term) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-primary btn-sm" style="background: var(--accent); color: var(--primary); font-weight: 700; border: none; border-radius: 30px; padding: 0.5rem 1.25rem;">
                                    AKTIFKAN
                                </button>
                            </form>
                            @endcan
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        {{-- Footer Note --}}
        <div style="margin-top: 3rem; background: var(--bg-secondary); border-radius: 12px; padding: 1.5rem; border: 1px solid var(--border); border-left: 4px solid var(--primary);">
            <div style="display: flex; gap: 1rem;">
                <div style="color: var(--primary); margin-top: 2px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="16" y2="12"/><line x1="12" x2="12.01" y1="8" y2="8"/></svg>
                </div>
                <div style="font-size: 0.875rem; color: var(--text-secondary); line-height: 1.6;">
                    <strong style="color: var(--text);">Penting:</strong> Mengaktifkan kuartal akan mengalihkan semua proses input nilai dan rekapitulasi raport ke periode tersebut. Pastikan proses penilaian pada kuartal sebelumnya telah selesai sebelum berpindah.
                </div>
            </div>
        </div>
    @endif

</div>
@endsection
