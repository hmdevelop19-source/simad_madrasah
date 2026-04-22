@extends('layouts.app')
@section('title', 'Raport — ' . $student->nama_lengkap)
@section('page-title', 'E-Raport')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:.75rem;margin-bottom:1rem;">
    <div style="display:flex;align-items:center;gap:.75rem;">
        <a href="{{ route('reports.index', ['academic_year_id'=>$yearId,'grade_level_id'=>$history->classroom?->grade_level_id]) }}"
           class="btn btn-outline btn-sm">← Kembali</a>
        <div>
            <span style="font-size:.78rem;color:var(--text-muted);">E-Raport</span>
            <h3 style="font-size:.95rem;font-weight:700;margin:0;">{{ $student->nama_lengkap }}</h3>
        </div>
    </div>
    <div style="display:flex;gap:.5rem;">
        <a href="{{ route('reports.print', [$student, 'academic_year_id'=>$yearId]) }}" target="_blank"
           class="btn btn-primary btn-sm">🖨️ Cetak Raport</a>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 320px;gap:1rem;align-items:start;">

    {{-- Kiri: Nilai --}}
    <div>
        {{-- Info santri --}}
        <div class="card" style="margin-bottom:1rem;">
            <div style="padding:1rem 1.25rem;display:grid;grid-template-columns:1fr 1fr;gap:.5rem .75rem;font-size:.82rem;">
                <div><span style="color:var(--text-muted);">Nama Lengkap</span><br><strong>{{ $student->nama_lengkap }}</strong></div>
                <div><span style="color:var(--text-muted);">NIS</span><br><strong>{{ $student->nis ?? '–' }}</strong></div>
                <div><span style="color:var(--text-muted);">Kelas</span><br><strong>{{ $history->classroom?->nama_kelas }}</strong></div>
                <div><span style="color:var(--text-muted);">Tahun Ajaran</span><br><strong>{{ $history->academicYear?->nama }} — {{ $history->academicYear?->periode }}</strong></div>
                <div><span style="color:var(--text-muted);">Unit Pendidikan</span><br><strong>{{ $history->classroom?->gradeLevel?->educationLevel?->nama }}</strong></div>
                <div>
                    <span style="color:var(--text-muted);">Status Kenaikan</span><br>
                    @php
                        $sc = match($history->status_kenaikan ?? '') {
                            'Naik Kelas'   => ['#DCFCE7','#166534'],
                            'Tinggal Kelas'=> ['#FEF9C3','#854D0E'],
                            'Lulus'        => ['#EDE9FE','#5B21B6'],
                            default        => ['#F1F5F9','#475569'],
                        };
                    @endphp
                    <span style="background:{{ $sc[0] }};color:{{ $sc[1] }};padding:2px 10px;border-radius:999px;font-size:.72rem;font-weight:700;">
                        {{ $history->status_kenaikan ?? 'Belum Ditentukan' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Tabel Nilai --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title" style="font-size:.88rem;">📚 Nilai Mata Pelajaran</span>
            </div>
            <div class="table-wrap">
                @php
                    // Deteksi kolom yang memang ada datanya di raport santri ini
                    $kolomTampil = [];
                    foreach($jenisNilaiList as $j) {
                        foreach($gradeMap as $curId => $rows) {
                            if(isset($rows[$j])) { $kolomTampil[] = $j; break; }
                        }
                    }
                    if(empty($kolomTampil)) $kolomTampil = $jenisNilaiList;
                @endphp
                <table>
                    <thead>
                        <tr>
                            <th>Mata Pelajaran</th>
                            <th style="text-align:center;width:55px;">KKM</th>
                            @foreach($kolomTampil as $j)
                            <th style="text-align:center;width:65px;font-size:.75rem;">{{ $j }}</th>
                            @endforeach
                            <th style="text-align:center;width:80px;">Rata-rata</th>
                            <th style="text-align:center;width:75px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($curricula as $cur)
                        @php
                            $nilaiRow = $gradeMap[$cur->id] ?? [];
                            // Kumpulkan kolom nilai yang berlaku untuk kurikulum ini
                            $kolomCur = array_filter($jenisNilaiList, fn($j) => isset($nilaiRow[$j]));
                            if (empty($kolomCur)) $kolomCur = $jenisNilaiList; // fallback semua kolom
                            $allNilai = array_filter($nilaiRow, fn($v) => $v !== null);
                            $rataRata = count($allNilai) > 0 ? round(array_sum($allNilai)/count($allNilai),1) : null;
                            $kkm      = $cur->kkm;
                            $tuntas   = $rataRata !== null && $rataRata >= $kkm;
                        @endphp
                        <tr style="{{ $rataRata !== null && !$tuntas ? 'background:#FFF8F8;' : '' }}">
                            <td>
                                <span style="background:var(--primary)22;color:var(--primary);padding:1px 6px;border-radius:4px;font-size:.68rem;font-weight:700;font-family:monospace;margin-right:.35rem;">{{ $cur->subject?->kode_mapel }}</span>
                                {{ $cur->subject?->nama_mapel }}
                            </td>
                            <td style="text-align:center;font-size:.78rem;color:var(--text-muted);">{{ $kkm }}</td>
                            @foreach($kolomTampil as $j)
                            @php $n = $nilaiRow[$j] ?? null; @endphp
                            <td style="text-align:center;font-size:.85rem;">
                                @if($n !== null)
                                    {{ $n }}
                                @else
                                    <span style="color:var(--border);font-size:.78rem;">–</span>
                                @endif
                            </td>
                            @endforeach
                            <td style="text-align:center;font-weight:700;font-size:.9rem;color:{{ $rataRata !== null ? ($tuntas ? '#16A34A' : '#DC2626') : 'var(--text-muted)' }};">
                                {{ $rataRata ?? '–' }}
                            </td>
                            <td style="text-align:center;">
                                @if($rataRata !== null)
                                <span style="font-size:.7rem;font-weight:700;color:{{ $tuntas ? '#16A34A' : '#DC2626' }};">
                                    {{ $tuntas ? '✓ Tuntas' : '✗ Remedi' }}
                                </span>
                                @else
                                <span style="color:var(--border);font-size:.75rem;">–</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Kanan: Kehadiran --}}
    <div>
        <div class="card" style="position:sticky;top:1rem;">
            <div class="card-header"><span class="card-title" style="font-size:.88rem;">✅ Rekap Kehadiran</span></div>
            <div style="padding:1.25rem;display:flex;flex-direction:column;gap:.75rem;">
                @php
                    $kd = [
                        'Hadir'=>['#16A34A','#DCFCE7'],'Sakit'=>['#D97706','#FEF9C3'],
                        'Izin'=>['#2563EB','#DBEAFE'],'Alpha'=>['#DC2626','#FEE2E2']
                    ];
                @endphp
                @foreach($kd as $status => [$color, $bg])
                @php $cnt = $attendanceSummary[$status]; $total = $attendanceSummary['total']; @endphp
                <div style="display:flex;align-items:center;gap:.75rem;">
                    <div style="width:36px;height:36px;border-radius:8px;background:{{ $bg }};display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;">
                        {{ ['Hadir'=>'✅','Sakit'=>'🤒','Izin'=>'📋','Alpha'=>'❌'][$status] }}
                    </div>
                    <div style="flex:1;">
                        <div style="display:flex;justify-content:space-between;font-size:.8rem;">
                            <span style="color:var(--text-muted);">{{ $status }}</span>
                            <span style="font-weight:700;color:{{ $color }};">{{ $cnt }} hari</span>
                        </div>
                        <div style="height:5px;background:var(--border);border-radius:3px;margin-top:3px;">
                            <div style="height:5px;background:{{ $color }};border-radius:3px;width:{{ $total > 0 ? round($cnt/$total*100) : 0 }}%;"></div>
                        </div>
                    </div>
                </div>
                @endforeach
                <div style="border-top:1px solid var(--border);padding-top:.75rem;display:flex;justify-content:space-between;font-size:.82rem;">
                    <span style="color:var(--text-muted);">Total Pertemuan</span>
                    <strong>{{ $attendanceSummary['total'] }} hari</strong>
                </div>
                @if($attendanceSummary['total'] > 0)
                <div style="text-align:center;background:var(--primary)11;border-radius:var(--radius);padding:.75rem;">
                    <div style="font-size:.72rem;color:var(--text-muted);">Persentase Kehadiran</div>
                    <div style="font-size:1.75rem;font-weight:800;color:var(--primary);">
                        {{ round($attendanceSummary['Hadir'] / $attendanceSummary['total'] * 100, 1) }}%
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

</div>

@endsection
