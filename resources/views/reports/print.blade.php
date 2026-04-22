<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raport — {{ $student->nama_lengkap }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 9pt; color: #000; background: #fff; }

        @media print {
            /* F4 size is 215mm x 330mm */
            @page { size: 215mm 330mm portrait; margin: 15mm 15mm 15mm 15mm; }
            body { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
            .no-print { display: none !important; }
            .page-break { page-break-before: always; }
        }

        .container { max-width: 210mm; margin: 0 auto; padding: 15mm; }

        /* KOP Surat */
        .kop-surat-img { width: 100%; max-height: 120px; object-fit: contain; margin-bottom: 20px; border-bottom: 3px double #000; padding-bottom: 5px; }

        /* Header / Identitas santri */
        .kop-tabel { width: 100%; border-collapse: collapse; margin-bottom: 12px; font-size: 9pt; border: 1px solid #000; }
        .kop-tabel td { padding: 4px 6px; }

        /* Tabel utama */
        table.tabel-utama { width: 100%; border-collapse: collapse; margin-bottom: 12px; font-size: 9pt; }
        table.tabel-utama th, table.tabel-utama td { border: 1px solid #000; padding: 4px 6px; vertical-align: middle; }
        table.tabel-utama th { text-align: center; font-weight: bold; }
        .text-center { text-align: center; }
        .text-red { color: #D8000C !important; }

        /* Tabel Kepribadian & Presensi Sejajar */
        .bottom-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 12px; align-items: start; }
        table.tabel-sub { width: 100%; border-collapse: collapse; font-size: 9pt; }
        table.tabel-sub th, table.tabel-sub td { border: 1px solid #000; padding: 4px 6px; }
        table.tabel-sub th { text-align: center; font-weight: bold; }

        /* Kritik Saran */
        .kritik-saran { font-size: 9pt; margin-bottom: 25px; }

        /* Ttd */
        .ttd-grid { display: grid; grid-template-columns: 1fr 1fr; margin-top: 10px; font-size: 9pt; gap: 20px;}
        .ttd-box { text-align: center; }
        .ttd-box.right { text-align: right; display:flex; flex-direction:column; align-items: flex-end;}
        .ttd-box.center-bottom { grid-column: 1 / 3; text-align: center; margin-top: -15px;}
        .ttd-space { height: 75px; }
        .garis-bawah { text-decoration: underline; font-weight: bold; }

        .print-btn { position: fixed; top: 15px; right: 15px; background: #4F46E5; color: white; border: none; padding: 8px 20px; border-radius: 6px; cursor: pointer; font-size: 13px; z-index: 100; }
    </style>
</head>
<body>

<button class="print-btn no-print" onclick="window.print()">🖨️ Cetak Raport</button>

@php
// Helper untuk konversi angka ke ejaan kas madrasah (ex: 60 -> Enam Puluh Kosong, 0 -> Kosong, 28 -> Dua Puluh Delapan)
function terbilangKhusus($nilai) {
    if(!is_numeric($nilai)) return "";
    $nilai = round($nilai); // bulatkan
    $huruf = [0=>"Kosong", 1=>"Satu", 2=>"Dua", 3=>"Tiga", 4=>"Empat", 5=>"Lima", 6=>"Enam", 7=>"Tujuh", 8=>"Delapan", 9=>"Sembilan"];
    
    if($nilai < 10) {
        return $huruf[$nilai];
    } elseif ($nilai >= 11 && $nilai <= 19) {
        if ($nilai == 11) return "Sebelas";
        return $huruf[$nilai % 10] . " Belas";
    } elseif ($nilai == 10) {
        return "Sepuluh";
    } elseif ($nilai == 100) {
        return "Seratus"; // atau Satu Ratus Kosong
    } else {
        $puluhan = floor($nilai / 10);
        $satuan = $nilai % 10;
        return $huruf[$puluhan] . " Puluh " . $huruf[$satuan];
    }
}

function terbilangDesimal($nilai) {
    if(!is_numeric($nilai)) return "";
    // misal: 51.5 -> array(51, 5)
    $strNilai = str_replace(',', '.', (string)$nilai);
    if(strpos($strNilai, '.') === false) {
        return terbilangKhusus($nilai);
    }
    $exp = explode('.', $strNilai);
    $bulat = terbilangKhusus((int)$exp[0]);
    // ambil 1 digit desimal
    $huruf = [0=>"Kosong", 1=>"Satu", 2=>"Dua", 3=>"Tiga", 4=>"Empat", 5=>"Lima", 6=>"Enam", 7=>"Tujuh", 8=>"Delapan", 9=>"Sembilan"];
    $digitDesimal = substr($exp[1], 0, 1);
    $desimal = $huruf[(int)$digitDesimal];
    return $bulat . " Koma " . $desimal;
}

function getPredikat($nilai, $kkm) {
    if(!is_numeric($nilai)) return "-";
    // Logika predikat standar:
    if($nilai >= 90) return 'A';
    if($nilai >= 80) return 'B';
    if($nilai >= $kkm) return 'C';
    return 'D';
}
@endphp

<div class="container">

    {{-- KOP SURAT (Dinamis dari Education Level) --}}
    @php
        $eduLevel = $history->classroom?->gradeLevel?->educationLevel;
    @endphp
    @if($eduLevel && $eduLevel->kop_surat)
        <img src="{{ asset('storage/' . $eduLevel->kop_surat) }}" alt="KOP Surat" class="kop-surat-img">
    @else
        {{-- Fallback jika unit belum upload KOP Surat --}}
        <div style="text-align:center; border-bottom:3px double #000; padding-bottom:10px; margin-bottom:15px;">
            <h2 style="font-size:14pt; font-weight:bold; margin-bottom:4px;">LAPORAN HASIL BELAJAR PESERTA DIDIK</h2>
            <h1 style="font-size:16pt; font-weight:bold; margin-bottom:4px;">{{ strtoupper($eduLevel->nama ?? 'Madrasah') }}</h1>
            <p style="font-size:10pt;">Tahun Ajaran {{ $history->academicYear?->nama }}</p>
        </div>
    @endif

    {{-- Identitas --}}
    <table class="kop-tabel">
        <tr>
            <td style="width: 15%;">ID/No. Induk</td>
            <td style="width: 40%;">: {{ $student->nis ?? '-' }}</td>
            <td style="width: 15%;">Kuartal</td>
            <td style="width: 30%;">: {{ explode(' — ', $history->academicYear?->periode)[1] ?? 'I' }}</td>
        </tr>
        <tr>
            <td>Nama</td>
            <td style="font-weight: bold;">: {{ strtoupper($student->nama_lengkap) }}</td>
            <td>Kelas</td>
            <td>: {{ $history->classroom?->nama_kelas ?? '-' }}</td>
        </tr>
        <tr>
            <td>Nama Madrasah</td>
            <td>: {{ strtoupper($history->classroom?->gradeLevel?->educationLevel?->nama ?? 'Madrasah') }}</td>
            <td>Tahun Ajaran</td>
            <td>: {{ explode(' — ', $history->academicYear?->nama)[0] ?? '-' }}</td>
        </tr>
    </table>

    {{-- Tabel Utama Penilaian --}}
    <table class="tabel-utama">
        <thead>
            <tr>
                <th rowspan="2" style="width: 30px;">No.</th>
                <th rowspan="2" style="width: 250px;">Mata Pelajaran</th>
                <th rowspan="2" style="width: 40px;">KKM</th>
                <th colspan="2">Penilaian</th>
                <th rowspan="2" style="width: 60px;">Predikat</th>
            </tr>
            <tr>
                <th style="width: 50px;">Angka</th>
                <th>Huruf</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalNilai = 0;
                $mapelCount = 0;
            @endphp
            @foreach($curricula as $i => $cur)
                @php
                    $nilaiRow = $gradeMap[$cur->id] ?? [];
                    // Rata-rata dari form nilai yg diinput (recap)
                    $allNilai = array_filter($nilaiRow, fn($v) => $v !== null);
                    // Rata-rata 1 decimal
                    $rataRata = count($allNilai) > 0 ? round(array_sum($allNilai)/count($allNilai), 1) : null;
                    $kkm      = $cur->kkm;
                    $tuntas   = $rataRata !== null && $rataRata >= $kkm;
                    $kelasRed = ($rataRata !== null && !$tuntas) ? 'text-red font-weight-bold' : '';
                    
                    if($rataRata !== null) {
                        $totalNilai += $rataRata;
                        $mapelCount++;
                    }
                @endphp
                <tr>
                    <td class="text-center">{{ $i+1 }}</td>
                    <td>{{ strtoupper($cur->subject?->nama_mapel) }}</td>
                    <td class="text-center">{{ $kkm }}</td>
                    <td class="text-center {{ $kelasRed }}" style="font-weight: bold;">
                        {{ $rataRata ?? '-' }}
                    </td>
                    <td class="{{ $kelasRed }}" style="font-style: italic;">
                        {{ $rataRata !== null ? terbilangDesimal($rataRata) : '-' }}
                    </td>
                    <td class="text-center {{ $kelasRed }}" style="font-weight: bold;">
                        {{ $rataRata !== null ? getPredikat($rataRata, $kkm) : '-' }}
                    </td>
                </tr>
            @endforeach
            
            {{-- Baris Jumlah & Rerata --}}
            @php
                $rerataSemua = $mapelCount > 0 ? round($totalNilai / $mapelCount, 1) : 0;
            @endphp
            <tr>
                <td colspan="3" class="text-center" style="font-weight: bold;">Jumlah</td>
                <td class="text-center" style="font-weight: bold;">{{ $totalNilai > 0 ? str_replace('.', ',', $totalNilai) : '-' }}</td>
                <td colspan="2" style="font-weight: bold; font-style: italic;">
                    {{ $totalNilai > 0 ? terbilangDesimal($totalNilai) : '-' }}
                </td>
            </tr>
            <tr>
                <td colspan="3" class="text-center" style="font-weight: bold;">Rerata</td>
                <td class="text-center" style="font-weight: bold;">{{ $rerataSemua > 0 ? str_replace('.', ',', $rerataSemua) : '-' }}</td>
                <td colspan="2" style="font-weight: bold; font-style: italic;">
                    {{ $rerataSemua > 0 ? terbilangDesimal($rerataSemua) : '-' }}
                </td>
            </tr>
        </tbody>
    </table>

    {{-- Kepribadian & Presensi --}}
    <div class="bottom-grid">
        {{-- Kiri: Kepribadian --}}
        <div>
            <table class="tabel-sub">
                <thead>
                    <tr>
                        <th style="width: 10%;">No</th>
                        <th style="width: 50%;">Aspek yang Dinilai</th>
                        <th style="width: 40%;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">1</td>
                        <td>Kelakuan</td>
                        <td class="text-center"><b>{{ $personalityMap['Kelakuan'] ?? '-' }}</b></td>
                    </tr>
                    <tr>
                        <td class="text-center">2</td>
                        <td>Kerajinan</td>
                        <td class="text-center"><b>{{ $personalityMap['Kerajinan'] ?? '-' }}</b></td>
                    </tr>
                    <tr>
                        <td class="text-center">3</td>
                        <td>Kebersihan</td>
                        <td class="text-center"><b>{{ $personalityMap['Kebersihan'] ?? '-' }}</b></td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Kanan: Presensi --}}
        <div>
            <table class="tabel-sub">
                <thead>
                    <tr>
                        <th style="width: 65%;">Daftar Presensi</th>
                        <th style="width: 35%;">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Alpa</td>
                        <td class="text-center">{{ $attendanceSummary['Alpha'] ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td>Izin</td>
                        <td class="text-center">{{ $attendanceSummary['Izin'] ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td>Sakit</td>
                        <td class="text-center">{{ $attendanceSummary['Sakit'] ?? 0 }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Kritik dan Saran --}}
    <div class="kritik-saran">
        Kritik dan saran:<br>
        <div style="font-style: italic; margin-top: 5px; text-align: center; font-size: 9pt;">
            Belajarlah lebih giat lagi dan jangan menyerah, agar mendapatkan prestasi yang lebih baik!
        </div>
    </div>

    {{-- Tanda Tangan --}}
    <div style="text-align: right; font-size: 9pt; margin-bottom: 15px; padding-right: 30px;">
        Pamekasan, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
    </div>

    <div style="display: flex; justify-content: space-between; padding: 0 40px; font-size: 9pt; text-align: center;">
        <div style="width: 220px;">
            <p style="font-style: italic;">Orang Tua/Wali</p>
            <div style="height: 65px;"></div>
            @php
                $namaWali = $student->wali?->nama_lengkap ?? '.........................';
            @endphp
            <p style="text-decoration: underline; font-weight: bold;">{{ strtoupper($namaWali) }}</p>
        </div>
        <div style="width: 220px;">
            <p style="font-style: italic;">Wali Kelas</p>
            <div style="height: 65px;"></div>
            <p style="text-decoration: underline; font-weight: bold;">{{ strtoupper($history->classroom?->teacher?->nama_lengkap ?? 'GURU WALIKELAS') }}</p>
        </div>
    </div>

    <div style="text-align: center; margin-top: 15px; font-size: 9pt;">
        <p style="font-style: italic;">Mengetahui,<br>Kepala Madrasah</p>
        <div style="height: 65px;"></div>
        <p style="text-decoration: underline; font-weight: bold;">MAHFUD ANWAR, S.PD</p>
    </div>

</div>

<script>
// Auto print jika parameter auto=1
const urlParams = new URLSearchParams(window.location.search);
if (urlParams.get('auto') === '1') { window.onload = () => window.print(); }
</script>

</body>
</html>
