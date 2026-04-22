@extends('layouts.app')
@section('title', 'Rollover — Step 1')
@section('page-title', '🔍 Step 1: Review Tahun Ajaran')
@section('breadcrumb') <a href="{{ route('rollover.index') }}">Year-End Rollover</a> › Step 1 @endsection

@section('content')

{{-- Step Indicator --}}
<div style="display:flex;gap:.5rem;align-items:center;margin-bottom:1.5rem;">
    <span style="background:var(--primary);color:white;width:28px;height:28px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem;">1</span>
    <span style="height:2px;width:60px;background:var(--border);"></span>
    <span style="background:var(--border);color:var(--text-muted);width:28px;height:28px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem;">2</span>
    <span style="height:2px;width:60px;background:var(--border);"></span>
    <span style="background:var(--border);color:var(--text-muted);width:28px;height:28px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem;">3</span>
</div>

<div style="max-width:760px;">

    <div class="card" style="margin-bottom:1.25rem;">
        <div class="card-header">
            <span class="card-title">📊 Ringkasan Tahun Ajaran: {{ $activeYear->nama }} — {{ $activeYear->periode }}</span>
        </div>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1px;background:var(--border);">
            @foreach([
                ['label'=>'Santri Aktif','value'=>$summary['santri_aktif'],'icon'=>'🧑‍🎓','color'=>''],
                ['label'=>'Sudah Ditempatkan','value'=>$summary['santri_ditempatkan'],'icon'=>'✅','color'=>'#16A34A'],
                ['label'=>'Belum Ditempatkan','value'=>$summary['santri_belum'],'icon'=>'⚠️','color'=>$summary['santri_belum']>0?'#D97706':''],
                ['label'=>'Total Kelas','value'=>$summary['kelas'],'icon'=>'🏫','color'=>''],
                ['label'=>'Belum Dinilai','value'=>$summary['status_belum'],'icon'=>'📋','color'=>$summary['status_belum']>0?'var(--danger)':''],
            ] as $item)
            <div style="background:white;padding:1.25rem 1.5rem;text-align:center;">
                <div style="font-size:1.5rem;margin-bottom:.25rem;">{{ $item['icon'] }}</div>
                <div style="font-size:1.75rem;font-weight:700;{{ $item['color'] ? "color:{$item['color']};" : '' }}">{{ $item['value'] }}</div>
                <div style="font-size:.75rem;color:var(--text-muted);">{{ $item['label'] }}</div>
            </div>
            @endforeach
        </div>
    </div>

    @if($summary['santri_belum'] > 0)
    <div class="alert alert-warning">
        ⚠️ <strong>{{ $summary['santri_belum'] }} santri belum ditempatkan.</strong>
        Disarankan untuk menempatkan semua santri sebelum rollover.
        <a href="{{ route('student-placements.index', ['status'=>'belum']) }}" style="font-weight:600;color:inherit;text-decoration:underline;">Lihat santri →</a>
    </div>
    @endif

    @if($summary['status_belum'] > 0)
    <div class="alert alert-warning">
        ⚠️ <strong>{{ $summary['status_belum'] }} santri belum memiliki status kenaikan.</strong>
        Anda bisa menetapkannya di <strong>Step 2</strong>.
    </div>
    @endif

    @if($summary['santri_belum'] == 0 && $summary['status_belum'] == 0)
    <div class="alert alert-success">
        ✅ Semua data sudah siap! Anda dapat melanjutkan ke langkah berikutnya.
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <p style="font-size:.9rem;color:var(--text-secondary);line-height:1.7;margin-bottom:1.25rem;">
                Dengan melanjutkan, Anda mengonfirmasi bahwa data tahun ajaran <strong>{{ $activeYear->nama }}</strong> sudah diperiksa
                dan siap untuk proses kenaikan kelas.
            </p>
            <div style="display:flex;gap:.75rem;align-items:center;">
                <a href="{{ route('rollover.index') }}" class="btn btn-outline">← Kembali</a>
                <form action="{{ route('rollover.step1.process') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">Lanjut ke Step 2 →</button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
