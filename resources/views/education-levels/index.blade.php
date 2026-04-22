@extends('layouts.app')
@section('title', 'Manajemen Unit')
@section('page-title', 'Unit Pendidikan')

@section('content')

{{-- ================================================================
     BANNER & SUMMARY STATS
================================================================ --}}
<div style="background: white; border: 1px solid var(--border); border-radius: 20px; padding: 1.5rem; margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between; box-shadow: var(--shadow-sm);">
    <div style="display: flex; align-items: center; gap: 1.5rem;">
        <div style="width: 60px; height: 60px; background: var(--primary-light); color: var(--primary); border-radius: 16px; display: flex; align-items: center; justify-content: center; box-shadow: inset 0 0 0 1px rgba(0,0,82,0.1);">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
        </div>
        <div>
            <h2 style="font-size: 1.25rem; font-weight: 800; color: var(--primary); margin: 0;">Fondasi Pendidikan</h2>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-top: 2px;">Kelola struktur jenjang pendidikan yang ada di lembaga ini.</p>
        </div>
    </div>
    
    <div style="display: flex; gap: 2rem; text-align: right; padding-right: 1rem;">
        <div>
            <div style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">Total Unit</div>
            <div style="font-size: 1.5rem; font-weight: 900; color: var(--primary);">{{ $levels->total() }}</div>
        </div>
        <div style="width: 1px; height: 40px; background: var(--border); align-self: center;"></div>
        <div>
            <div style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">Santri Lintas Unit</div>
            <div style="font-size: 1.5rem; font-weight: 900; color: var(--highlight);">{{ number_format($levels->sum('students_count')) }}</div>
        </div>
    </div>
</div>

{{-- ================================================================
     TOOLBAR & SEARCH
================================================================ --}}
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <form method="GET" style="display: flex; gap: 0.75rem; flex: 1; max-width: 400px;">
        <div style="position: relative; width: 100%;">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari kode atau nama unit..." class="form-control" style="padding-left: 2.5rem; border-radius: 30px; height: 45px; box-shadow: var(--shadow-sm);">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
        </div>
        @if($search)
            <a href="{{ route('education-levels.index') }}" class="btn btn-outline" style="border-radius: 30px; height: 45px; display: flex; align-items: center; justify-content: center; padding: 0 1.25rem;">✕</a>
        @endif
    </form>
    
    @can('create-unit')
    <a href="{{ route('education-levels.create') }}" class="btn btn-primary" style="height: 45px; display: flex; align-items: center; gap: 0.5rem; border-radius: 30px; padding: 0 2rem; font-weight: 700; background: var(--primary); box-shadow: var(--shadow-lg);">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
        TAMBAH UNIT
    </a>
    @endcan
</div>

@if(session('success'))
<div class="alert alert-success" style="border-radius: 12px; margin-bottom: 2rem; border: none; background: #ECFDF5; color: #065F46; font-weight: 600;">
    {{ session('success') }}
</div>
@endif

{{-- ================================================================
     DYNAMIC GRID CARDS
================================================================ --}}
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 2rem;">
    @forelse($levels as $level)
    <div class="unit-card" style="background: white; border: 1px solid var(--border); border-radius: 24px; padding: 2rem; position: relative; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: var(--shadow-sm); overflow: hidden;">
        {{-- Deco --}}
        <div style="position: absolute; right: -20px; top: -20px; width: 100px; height: 100px; background: var(--bg-secondary); border-radius: 50%; opacity: 0.5; z-index: 0;"></div>
        
        <div style="position: relative; z-index: 1;">
            {{-- Initials & Code --}}
            <div style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 1.5rem;">
                <div style="width: 54px; height: 54px; background: var(--primary); color: var(--accent); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 1.1rem; box-shadow: 0 10px 20px rgba(0,0,82,0.15);">
                    {{ strtoupper($level->kode) }}
                </div>
                {{-- Actions --}}
                <div style="display: flex; gap: 0.5rem;">
                    @can('edit-unit')
                    <a href="{{ route('education-levels.edit', $level) }}" class="btn btn-outline btn-sm" style="background: white; border-radius: 30px; font-weight: 700; font-size: 0.7rem; border-color: var(--border);">
                        EDIT
                    </a>
                    @endcan
                    @can('delete-unit')
                    <form action="{{ route('education-levels.destroy', $level) }}" method="POST" onsubmit="return confirm('Hapus unit {{ $level->nama }}?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm" style="background: #FEE2E2; color: #B91C1C; border: 1px solid #FECACA; border-radius: 30px; font-weight: 700; font-size: 0.7rem;">
                            HAPUS
                        </button>
                    </form>
                    @endcan
                </div>
            </div>

            {{-- Title --}}
            <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--primary); margin: 0 0 0.5rem 0; line-height: 1.2;">
                {{ $level->nama }}
            </h3>
            
            <div style="height: 1px; width: 40px; background: var(--highlight); margin-bottom: 1.5rem;"></div>

            {{-- Stats Pill --}}
            <div style="display: inline-flex; align-items: center; gap: 0.6rem; background: var(--bg-secondary); border: 1px solid var(--border); padding: 0.5rem 1rem; border-radius: 30px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--primary);"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                <span style="font-size: 0.85rem; font-weight: 800; color: var(--primary);">{{ number_format($level->students_count) }}</span>
                <span style="font-size: 0.8rem; font-weight: 600; color: var(--text-muted);">Santri Aktif</span>
            </div>
        </div>

        {{-- Hover Deco --}}
        <div class="card-glow" style="position: absolute; inset: 0; border: 2px solid var(--primary); border-radius: 24px; opacity: 0; transition: opacity 0.2s; pointer-events: none;"></div>
    </div>
    @empty
    <div style="grid-column: 1 / -1; background: white; border: 1px dashed var(--border-dark); border-radius: 24px; padding: 5rem 2rem; text-align: center;">
        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--text-muted); opacity: 0.4; margin-bottom: 1rem;"><path d="M8 3H5a2 2 0 0 0-2 2v3"/><path d="M21 8V5a2 2 0 0 0-2-2h-3"/><path d="M3 16v3a2 2 0 0 0 2 2h3"/><path d="M16 21h3a2 2 0 0 0 2-2v-3"/><circle cx="12" cy="12" r="3"/></svg>
        <h4 style="font-weight: 700; color: var(--text); font-size: 1.1rem; margin-bottom: 0.5rem;">Tidak ada unit ditemukan</h4>
        <p style="color: var(--text-muted); font-size: 0.85rem;">Mungkin perlu menyesuaikan pencarian atau menambahkan unit baru.</p>
    </div>
    @endforelse
</div>

<div style="margin-top: 3rem; display: flex; justify-content: center;">
    {{ $levels->withQueryString()->links() }}
</div>

<style>
    .unit-card:hover {
        transform: translateY(-8px);
        border-color: var(--primary);
        box-shadow: var(--shadow-lg);
    }
    .unit-card:hover .card-glow {
        opacity: 0.2;
    }
</style>

@endsection
