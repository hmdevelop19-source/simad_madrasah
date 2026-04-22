@extends('layouts.app')
@section('title', 'Rollover — Step 3')
@section('page-title', '🚀 Step 3: Eksekusi Rollover')
@section('breadcrumb') <a href="{{ route('rollover.index') }}">Year-End Rollover</a> › Step 3 @endsection

@section('content')

{{-- Step Indicator --}}
<div style="display:flex;gap:.5rem;align-items:center;margin-bottom:1.5rem;">
    <span style="background:var(--success);color:white;width:28px;height:28px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem;">✓</span>
    <span style="height:2px;width:60px;background:var(--success);"></span>
    <span style="background:var(--success);color:white;width:28px;height:28px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem;">✓</span>
    <span style="height:2px;width:60px;background:var(--primary);"></span>
    <span style="background:var(--primary);color:white;width:28px;height:28px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem;">3</span>
</div>

<div style="max-width:800px;">

    {{-- Preview Summary --}}
    <div class="card" style="margin-bottom:1.25rem;">
        <div class="card-header">
            <span class="card-title">📊 Preview Hasil Rollover dari {{ $activeYear->nama }}</span>
        </div>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1px;background:var(--border);">
            @foreach([
                ['label'=>'Naik Kelas','value'=>$preview['naik'],'icon'=>'⬆️','color'=>'#16A34A','bg'=>'#F0FDF4'],
                ['label'=>'Tinggal Kelas','value'=>$preview['tinggal'],'icon'=>'↩️','color'=>'#D97706','bg'=>'#FFFBEB'],
                ['label'=>'Lulus','value'=>$preview['lulus'],'icon'=>'🎓','color'=>'#4F46E5','bg'=>'#EEF2FF'],
                ['label'=>'Mutasi','value'=>$preview['mutasi'],'icon'=>'🔀','color'=>'#9333EA','bg'=>'#F5F3FF'],
                ['label'=>'Belum Ditentukan','value'=>$preview['belum'],'icon'=>'❓','color'=>$preview['belum']>0?'var(--danger)':'var(--text-muted)','bg'=>$preview['belum']>0?'#FEF2F2':'white'],
                ['label'=>'Entri Kurikulum','value'=>$preview['kurikulum'],'icon'=>'📚','color'=>'#0891B2','bg'=>'#ECFEFF'],
            ] as $item)
            <div style="background:{{ $item['bg'] }};padding:1.25rem 1rem;text-align:center;">
                <div style="font-size:1.5rem;margin-bottom:.2rem;">{{ $item['icon'] }}</div>
                <div style="font-size:1.75rem;font-weight:700;color:{{ $item['color'] }};">{{ $item['value'] }}</div>
                <div style="font-size:.7rem;color:var(--text-muted);margin-top:.2rem;">{{ $item['label'] }}</div>
            </div>
            @endforeach
        </div>
        @if($preview['belum'] > 0)
        <div style="padding:.75rem 1.25rem;background:#FEF2F2;border-top:1px solid #FECACA;">
            <span style="color:#991B1B;font-size:.825rem;">⚠️ <strong>{{ $preview['belum'] }} santri</strong> belum ditentukan statusnya — akan dilewati saat rollover.</span>
        </div>
        @endif
    </div>

    {{-- Form Tahun Ajaran Baru --}}
    <div class="card" style="margin-bottom:1.25rem;">
        <div class="card-header">
            <span class="card-title">🗓️ Buat Tahun Ajaran Baru</span>
        </div>
        <div class="card-body">
            @if($errors->any())
            <div class="alert alert-danger">
                <ul style="margin:0;padding-left:1.25rem;">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form id="rolloverForm" action="{{ route('rollover.step3.process') }}" method="POST">
                @csrf
                <div style="margin-bottom:1.5rem;">
                    <div class="form-group" style="margin-bottom:1rem;">
                        <label class="form-label">Nama Tahun Ajaran Baru <span style="color:var(--danger);">*</span></label>
                        <input type="text" name="nama_tahun_baru" class="form-control {{ $errors->has('nama_tahun_baru') ? 'is-invalid' : '' }}"
                               placeholder="cth: 2026/2027" value="{{ old('nama_tahun_baru') }}" required>
                        @error('nama_tahun_baru')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div style="background: var(--bg-secondary); padding: 1rem; border-radius: var(--radius); border: 1px dashed var(--border-dark);">
                        <div style="display: flex; align-items: flex-start; gap: 0.75rem;">
                            <span style="font-size: 1.25rem;">ℹ️</span>
                            <div>
                                <div style="font-size: 0.875rem; font-weight: 600; color: var(--text);">Kuartal Otomatis</div>
                                <p style="font-size: 0.775rem; color: var(--text-secondary); margin-top: 2px; line-height: 1.4;">
                                    Sistem akan otomatis membuat <strong>4 Kuartal</strong> (Kuartal 1-4) untuk tahun baru ini. Kuartal 1 akan diatur sebagai aktif secara default.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kotak Konfirmasi --}}
                <div style="background:#FEF2F2;border:2px solid #FECACA;border-radius:var(--radius);padding:1.25rem;margin-top:1.25rem;">
                    <p style="font-size:.875rem;color:#991B1B;font-weight:600;margin-bottom:.75rem;">
                        ⚠️ Tindakan ini TIDAK DAPAT DIBATALKAN. Baca dan centang pernyataan di bawah:
                    </p>
                    <ul style="font-size:.825rem;color:#7F1D1D;padding-left:1.25rem;margin-bottom:1rem;line-height:2;">
                        <li>Tahun ajaran <strong>{{ $activeYear->nama }}</strong> akan dinonaktifkan</li>
                        <li>Santri dengan status <strong>Lulus</strong> akan diubah status menjadi "Lulus"</li>
                        <li>Santri dengan status <strong>Mutasi</strong> akan diubah status menjadi "Mutasi"</li>
                        <li>Santri <strong>Naik Kelas</strong> akan dipindahkan ke tingkat berikutnya</li>
                        <li>Tahun ajaran baru <strong id="namaBaruLabel">–</strong> akan dibuat dan diaktifkan</li>
                        <li style="color:#0E7490;">📚 <strong>{{ $preview['kurikulum'] }} entri kurikulum</strong> (mapel + KKM) akan otomatis terduplikat ke tahun baru — Anda bisa hapus mapel yang tidak dipakai setelahnya.</li>
                    </ul>
                    <label style="display:flex;align-items:center;gap:.6rem;cursor:pointer;font-size:.875rem;color:#991B1B;font-weight:600;">
                        <input type="checkbox" name="konfirmasi" value="1" id="konfirmasiCheck" style="width:16px;height:16px;">
                        Saya memahami dan mengonfirmasi eksekusi Year-End Rollover ini.
                    </label>
                    @error('konfirmasi')<div style="color:var(--danger);font-size:.775rem;margin-top:.4rem;">{{ $message }}</div>@enderror
                </div>

                <div style="display:flex;gap:.75rem;margin-top:1.25rem;">
                    <a href="{{ route('rollover.step2') }}" class="btn btn-outline">← Kembali ke Step 2</a>
                    <button type="button" id="rolloverBtn" class="btn btn-danger" disabled
                            onclick="document.getElementById('confirmModal').style.display='flex'"
                            style="padding:.6rem 1.5rem;opacity:.5;cursor:not-allowed;">
                        🚀 Jalankan Year-End Rollover
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

{{-- Modal Konfirmasi Final --}}
<div id="confirmModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:var(--radius-lg);padding:2rem;max-width:400px;width:90%;box-shadow:var(--shadow-lg);text-align:center;">
        <div style="font-size:2.5rem;margin-bottom:.75rem;">🚨</div>
        <h3 style="font-size:1.1rem;font-weight:700;margin-bottom:.5rem;">Konfirmasi Terakhir</h3>
        <p style="font-size:.875rem;color:var(--text-secondary);margin-bottom:1.25rem;line-height:1.6;">
            Apakah Anda benar-benar yakin ingin menjalankan Year-End Rollover?<br>
            <strong>Tindakan ini tidak dapat dibatalkan.</strong>
        </p>
        <div style="display:flex;gap:.75rem;justify-content:center;">
            <button onclick="document.getElementById('confirmModal').style.display='none'" class="btn btn-outline">Batal</button>
            <button onclick="document.getElementById('rolloverForm').submit()" class="btn btn-danger">Ya, Jalankan Rollover</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Update label nama baru
document.querySelector('[name="nama_tahun_baru"]').addEventListener('input', function() {
    document.getElementById('namaBaruLabel').textContent = this.value || '–';
});

// Enable tombol rollover hanya jika checkbox dicentang
document.getElementById('konfirmasiCheck').addEventListener('change', function() {
    const btn = document.getElementById('rolloverBtn');
    btn.disabled = !this.checked;
    btn.style.opacity = this.checked ? '1' : '.5';
    btn.style.cursor = this.checked ? 'pointer' : 'not-allowed';
});
</script>
@endpush
@endsection
