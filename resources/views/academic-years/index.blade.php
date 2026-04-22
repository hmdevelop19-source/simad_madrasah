@extends('layouts.app')
@section('title', 'Tahun Ajaran')
@section('page-title', 'Tahun Ajaran')

@section('content')
<div class="card">
    <div class="card-header" style="border-bottom: 2px solid var(--border);">
        <span class="card-title" style="display: flex; align-items: center; gap: 0.5rem; color: var(--primary);">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
            Daftar Tahun Ajaran
        </span>
        @can('create-tahun')
        <a href="{{ route('academic-years.create') }}" class="btn btn-primary btn-sm" style="background: var(--primary); border-radius: 30px; padding: 0.5rem 1.25rem;">
            + Tambah Baru
        </a>
        @endcan
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="padding-left: 1.5rem;">Tahun Ajaran</th>
                    <th style="text-align:center;">Status</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($academicYears as $year)
                <tr style="{{ $year->is_active ? 'background: #f8fafc;' : '' }}">
                    <td style="padding: 1.5rem 1rem 1.5rem 1.5rem;">
                        <div style="font-weight:700; font-size: 1.05rem; color: var(--primary); letter-spacing: -0.3px;">{{ $year->nama }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 4px; display: flex; align-items: center; gap: 0.4rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            Dibuat: {{ $year->created_at->format('d M Y') }}
                        </div>
                    </td>
                    <td style="text-align:center;">
                        @if($year->is_active)
                            <span class="badge" style="background: #D1FAE5; color: #065F46; font-weight: 700; border: 1px solid #A7F3D0; padding: 0.4rem 0.75rem;">AKTIF</span>
                        @else
                            <span class="badge" style="background: #F3F4F6; color: #6B7280; font-weight: 600; padding: 0.4rem 0.75rem;">ARSIP</span>
                        @endif
                    </td>
                    <td style="text-align:center; padding-right: 1.5rem;">
                        <div style="display:flex;gap:.5rem;justify-content:center;">
                            @if(!$year->is_active)
                                @can('edit-tahun')
                                <form action="{{ route('academic-years.set-active', $year) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-sm" style="background: var(--accent); color: var(--primary); font-weight: 700; border: none; padding: 0.4rem 1rem; border-radius: 30px;"
                                        onclick="return confirm('Jadikan tahun ajaran ini aktif?')">AKTIFKAN</button>
                                </form>
                                @endcan
                            @endif

                            @can('edit-tahun')
                            <a href="{{ route('academic-years.edit', $year) }}" class="btn btn-outline btn-sm" style="border-radius: 30px; padding: 0.4rem 1rem;">
                                EDIT
                            </a>
                            @endcan

                            @if(!$year->is_active)
                                @can('delete-tahun')
                                <form action="{{ route('academic-years.destroy', $year) }}" method="POST"
                                    onsubmit="return confirm('Hapus tahun ajaran ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm" style="background:#FEE2E2;color:#B91C1C;border:1px solid #FECACA;">🗑️</button>
                                </form>
                                @endcan
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" style="text-align:center;color:var(--text-muted);padding:4rem;">Belum ada tahun ajaran.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $academicYears->links() }}</div>
</div>
@endsection
