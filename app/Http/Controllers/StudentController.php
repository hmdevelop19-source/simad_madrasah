<?php

namespace App\Http\Controllers;

use App\Models\EducationLevel;
use App\Models\Student;
use App\Models\WaliSantri;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $search      = $request->get('search');
        $levelFilter = $request->get('education_level_id');
        $statusFilter= $request->get('status_aktif', 'Aktif');

        $students = Student::with(['educationLevel', 'wali'])
            ->when($search, fn($q) => $q->where('nama_lengkap', 'like', "%{$search}%")
                                        ->orWhere('nisn', 'like', "%{$search}%")
                                        ->orWhere('nik', 'like', "%{$search}%"))
            ->when($levelFilter, fn($q) => $q->where('current_level_id', $levelFilter))
            ->when($statusFilter, fn($q) => $q->where('status_aktif', $statusFilter))
            ->orderBy('nama_lengkap')
            ->paginate(20);

        $educationLevels = EducationLevel::orderBy('kode')->get();
        $statusOptions   = ['Aktif', 'Lulus', 'Mutasi', 'Keluar'];

        return view('students.index', compact('students', 'educationLevels', 'search', 'levelFilter', 'statusFilter', 'statusOptions'));
    }

    public function create()
    {
        $educationLevels = EducationLevel::orderBy('kode')->get();
        $waliList        = WaliSantri::orderBy('nama_lengkap')->get();

        // Enum options untuk form inline wali baru
        $hubunganOptions    = ['Ayah', 'Ibu', 'Kakek', 'Nenek', 'Paman', 'Bibi', 'Wali Lainnya'];
        $pendidikanOptions  = ['SD/MI', 'SMP/MTs', 'SMA/MA', 'D3', 'S1', 'S2', 'S3', 'Lainnya'];
        $penghasilanOptions = ['< 1 Juta', '1-3 Juta', '3-5 Juta', '5-10 Juta', '> 10 Juta'];

        return view('students.create', compact(
            'educationLevels', 'waliList',
            'hubunganOptions', 'pendidikanOptions', 'penghasilanOptions'
        ));
    }

    public function store(Request $request)
    {
        // ── Validasi data Santri ─────────────────────────────────────────────
        $rules = [
            'nisn'             => 'nullable|string|size:10|unique:students,nisn',
            'nik'              => 'required|string|size:16|unique:students,nik',
            'no_kk'            => 'required|string|max:16',
            'nama_lengkap'     => 'required|string|max:100',
            'tempat_lahir'     => 'required|string|max:50',
            'tanggal_lahir'    => 'required|date',
            'jenis_kelamin'    => 'required|in:L,P',
            'current_level_id' => 'required|exists:education_levels,id',
            'status_aktif'     => 'required|in:Aktif,Lulus,Mutasi,Keluar',
            'mode_wali'        => 'required|in:existing,new',
        ];

        // ── Validasi kondisional: mode wali ──────────────────────────────────
        if ($request->input('mode_wali') === 'existing') {
            $rules['wali_id'] = 'required|exists:wali_santri,id';
        } else {
            // Daftarkan wali baru — semua field wajib
            $rules['wali_baru.nik']               = 'required|string|size:16|unique:wali_santri,nik';
            $rules['wali_baru.nama_lengkap']       = 'required|string|max:100';
            $rules['wali_baru.hubungan_keluarga']  = 'required|in:Ayah,Ibu,Kakek,Nenek,Paman,Bibi,Wali Lainnya';
            $rules['wali_baru.no_whatsapp']        = 'required|string|max:15|unique:wali_santri,no_whatsapp';
            $rules['wali_baru.alamat_lengkap']     = 'required|string';
            $rules['wali_baru.pekerjaan']          = 'nullable|string|max:100';
            $rules['wali_baru.pendidikan_terakhir']= 'nullable|in:SD/MI,SMP/MTs,SMA/MA,D3,S1,S2,S3,Lainnya';
            $rules['wali_baru.penghasilan_bulanan']= 'nullable|in:< 1 Juta,1-3 Juta,3-5 Juta,5-10 Juta,> 10 Juta';
        }

        $validated = $request->validate($rules, [
            'nisn.size'                     => 'NISN harus 10 digit.',
            'nik.size'                      => 'NIK santri harus 16 digit.',
            'nik.unique'                    => 'NIK santri sudah terdaftar.',
            'wali_id.required'              => 'Pilih wali santri terlebih dahulu.',
            'wali_baru.nik.size'            => 'NIK wali harus 16 digit.',
            'wali_baru.nik.unique'          => 'NIK wali sudah terdaftar di sistem.',
            'wali_baru.no_whatsapp.unique'  => 'No. WhatsApp wali sudah terdaftar.',
        ]);

        return \Illuminate\Support\Facades\DB::transaction(function () use ($validated, $request) {
            // ── 1. Buat wali baru jika mode = new ───────────────────────────
            if ($validated['mode_wali'] === 'new') {
                $wali = WaliSantri::create($validated['wali_baru']);
                $validated['wali_id'] = $wali->id;
            }

            // ── 2. Buat santri ───────────────────────────────────────────────
            $santriData = collect($validated)
                ->only(['nisn','nik','no_kk','nama_lengkap','tempat_lahir','tanggal_lahir',
                        'jenis_kelamin','current_level_id','status_aktif','wali_id'])
                ->toArray();

            $student = Student::create($santriData);

            $waliNama = $validated['mode_wali'] === 'new'
                ? $validated['wali_baru']['nama_lengkap']
                : optional(WaliSantri::find($validated['wali_id']))->nama_lengkap;

            $msg = "Data Santri '{$student->nama_lengkap}' berhasil didaftarkan";
            if ($waliNama) $msg .= " beserta Wali '{$waliNama}'";

            return redirect()->route('students.index')->with('success', $msg . '.');
        });
    }

    public function show(Student $student)
    {
        $student->load(['educationLevel', 'wali', 'histories.academicYear', 'histories.classroom.gradeLevel']);
        return view('students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $educationLevels = EducationLevel::orderBy('kode')->get();
        $waliList        = WaliSantri::orderBy('nama_lengkap')->get();
        $statusOptions   = ['Aktif', 'Lulus', 'Mutasi', 'Keluar'];

        return view('students.edit', compact('student', 'educationLevels', 'waliList', 'statusOptions'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'nisn'             => "nullable|string|size:10|unique:students,nisn,{$student->id}",
            'nik'              => "required|string|size:16|unique:students,nik,{$student->id}",
            'no_kk'            => 'required|string|max:16',
            'nama_lengkap'     => 'required|string|max:100',
            'tempat_lahir'     => 'required|string|max:50',
            'tanggal_lahir'    => 'required|date',
            'jenis_kelamin'    => 'required|in:L,P',
            'current_level_id' => 'required|exists:education_levels,id',
            'wali_id'          => 'nullable|exists:wali_santri,id',
            'status_aktif'     => 'required|in:Aktif,Lulus,Mutasi,Keluar',
        ]);

        $student->update($validated);

        return redirect()->route('students.index')
            ->with('success', "Data Santri '{$student->nama_lengkap}' berhasil diperbarui.");
    }

    public function destroy(Student $student)
    {
        $nama = $student->nama_lengkap;
        $student->delete(); // SoftDelete

        return redirect()->route('students.index')
            ->with('success', "Data Santri '{$nama}' berhasil dihapus (dapat dipulihkan).");
    }
}
