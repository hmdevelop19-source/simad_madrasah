<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\StudentHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    // =========================================================================
    // INDEX — form input harian
    // =========================================================================

    public function index(Request $request)
    {
        $activeYear  = AcademicYear::where('is_active', true)->first();
        $classrooms  = Classroom::with('gradeLevel.educationLevel')->orderBy('grade_level_id')->get();
        $classId     = $request->get('class_id');
        $tanggal     = $request->get('tanggal', today()->toDateString());

        $students    = collect();
        $existing    = collect();
        $classroom   = null;

        if ($classId) {
            $classroom = Classroom::with('gradeLevel.educationLevel')->find($classId);

            // Ambil santri yang ditempatkan di kelas ini (via student_histories)
            $students = StudentHistory::with('student')
                ->where('class_id', $classId)
                ->when($activeYear, fn($q) => $q->where('academic_year_id', $activeYear->id))
                ->get()
                ->pluck('student')
                ->filter()
                ->sortBy('nama_lengkap');

            // Ambil presensi yang sudah ada untuk kelas + tanggal ini
            $existing = Attendance::where('class_id', $classId)
                ->where('tanggal', $tanggal)
                ->get()
                ->keyBy('student_id');
        }

        return view('attendances.index', compact(
            'classrooms', 'classId', 'tanggal', 'students',
            'existing', 'activeYear', 'classroom'
        ));
    }

    // =========================================================================
    // STORE — simpan presensi bulk (upsert)
    // =========================================================================

    public function store(Request $request)
    {
        $request->validate([
            'class_id'   => 'required|exists:classrooms,id',
            'tanggal'    => 'required|date',
            'statuses'   => 'required|array|min:1',
            'statuses.*' => 'in:Hadir,Sakit,Izin,Alpha',
        ]);

        $classId  = $request->class_id;
        $tanggal  = $request->tanggal;
        $statuses = $request->statuses;       // [student_id => status]
        $kets     = $request->keterangans ?? []; // [student_id => keterangan]

        DB::transaction(function () use ($classId, $tanggal, $statuses, $kets) {
            foreach ($statuses as $studentId => $status) {
                Attendance::updateOrCreate(
                    ['student_id' => $studentId, 'class_id' => $classId, 'tanggal' => $tanggal],
                    ['status' => $status, 'keterangan' => $kets[$studentId] ?? null]
                );
            }
        });

        return redirect()->route('attendances.index', ['class_id' => $classId, 'tanggal' => $tanggal])
            ->with('success', '✅ Presensi berhasil disimpan untuk ' . count($statuses) . ' santri.');
    }

    // =========================================================================
    // RECAP — rekap kehadiran per kelas per bulan
    // =========================================================================

    public function recap(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $classrooms = Classroom::with('gradeLevel.educationLevel')->orderBy('grade_level_id')->get();
        $classId    = $request->get('class_id');
        $bulan      = $request->get('bulan', date('m'));
        $tahun      = $request->get('tahun', date('Y'));

        $students   = collect();
        $recap      = [];
        $classroom  = null;

        if ($classId) {
            $classroom = Classroom::with('gradeLevel.educationLevel')->find($classId);

            $students = StudentHistory::with('student')
                ->where('class_id', $classId)
                ->when($activeYear, fn($q) => $q->where('academic_year_id', $activeYear->id))
                ->get()
                ->pluck('student')
                ->filter()
                ->sortBy('nama_lengkap');

            // Ambil semua presensi bulan ini untuk kelas tersebut
            $allAttendances = Attendance::where('class_id', $classId)
                ->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $bulan)
                ->get();

            foreach ($students as $student) {
                $stu = $allAttendances->where('student_id', $student->id);
                $recap[$student->id] = [
                    'Hadir' => $stu->where('status', 'Hadir')->count(),
                    'Sakit' => $stu->where('status', 'Sakit')->count(),
                    'Izin'  => $stu->where('status', 'Izin')->count(),
                    'Alpha' => $stu->where('status', 'Alpha')->count(),
                    'total' => $stu->count(),
                ];
            }
        }

        $bulanOptions = [
            '01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April',
            '05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus',
            '09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember',
        ];

        return view('attendances.recap', compact(
            'classrooms', 'classId', 'bulan', 'tahun',
            'students', 'recap', 'classroom', 'bulanOptions'
        ));
    }
}
