<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Curriculum;
use App\Models\Grade;
use App\Models\GradeLevel;
use App\Models\Student;
use App\Models\StudentHistory;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // =========================================================================
    // INDEX — pilih santri untuk lihat raport
    // =========================================================================

    public function index(Request $request)
    {
        $activeYear    = AcademicYear::where('is_active', true)->first();
        $academicYears = AcademicYear::orderByDesc('is_active')->orderByDesc('created_at')->get();
        $gradeLevels   = GradeLevel::with('educationLevel')->orderBy('education_level_id')->get();

        $yearId       = $request->get('academic_year_id', $activeYear?->id);
        $gradeLevelId = $request->get('grade_level_id');

        $histories = collect();

        if ($gradeLevelId && $yearId) {
            $classroomIds = Classroom::where('grade_level_id', $gradeLevelId)->pluck('id');

            $histories = StudentHistory::with(['student', 'classroom'])
                ->whereIn('class_id', $classroomIds)
                ->where('academic_year_id', $yearId)
                ->get()
                ->sortBy('student.nama_lengkap');
        }

        return view('reports.index', compact(
            'academicYears', 'gradeLevels', 'yearId', 'gradeLevelId', 'histories'
        ));
    }

    // =========================================================================
    // SHOW — raport lengkap per santri
    // =========================================================================

    public function show(Request $request, Student $student)
    {
        $activeYear    = AcademicYear::where('is_active', true)->first();
        $academicYears = AcademicYear::orderByDesc('is_active')->orderByDesc('created_at')->get();
        $yearId        = $request->get('academic_year_id', $activeYear?->id);

        // Ambil history santri untuk tahun ajaran ini
        $history = StudentHistory::with(['classroom.gradeLevel.educationLevel', 'academicYear'])
            ->where('student_id', $student->id)
            ->where('academic_year_id', $yearId)
            ->first();

        if (!$history) {
            return redirect()->route('reports.index')->with('error', 'Data penempatan santri tidak ditemukan untuk tahun ajaran ini.');
        }

        // Ambil kurikulum (semua mapel untuk grade_level + tahun ajaran ini)
        $curricula = Curriculum::with('subject')
            ->where('academic_year_id', $yearId)
            ->where('grade_level_id', $history->classroom->grade_level_id)
            ->orderBy('subject_id')
            ->get();

        // Ambil semua nilai santri ini untuk semua kurikulum tersebut
        $grades = Grade::where('student_id', $student->id)
            ->whereIn('curriculum_id', $curricula->pluck('id'))
            ->get();

        // Pivot: [curriculum_id][jenis_nilai] = nilai
        $gradeMap = [];
        foreach ($grades as $g) {
            $gradeMap[$g->curriculum_id][$g->jenis_nilai] = $g->nilai;
        }

        // Rekap kehadiran (semua bulan di tahun ajaran ini)
        $attendances = Attendance::where('student_id', $student->id)
            ->where('class_id', $history->class_id)
            ->get();

        $attendanceSummary = [
            'Hadir' => $attendances->where('status', 'Hadir')->count(),
            'Sakit' => $attendances->where('status', 'Sakit')->count(),
            'Izin'  => $attendances->where('status', 'Izin')->count(),
            'Alpha' => $attendances->where('status', 'Alpha')->count(),
            'total' => $attendances->count(),
        ];

        $jenisNilaiList = Grade::JENIS_NILAI;

        return view('reports.show', compact(
            'student', 'history', 'curricula', 'gradeMap',
            'attendanceSummary', 'jenisNilaiList', 'academicYears', 'yearId'
        ));
    }

    // =========================================================================
    // PRINT — layout cetak A4
    // =========================================================================

    public function printReport(Request $request, Student $student)
    {
        $yearId   = $request->get('academic_year_id');
        $history  = StudentHistory::with(['classroom.gradeLevel.educationLevel', 'academicYear'])
            ->where('student_id', $student->id)
            ->where('academic_year_id', $yearId)
            ->firstOrFail();

        $curricula = Curriculum::with('subject')
            ->where('academic_year_id', $yearId)
            ->where('grade_level_id', $history->classroom->grade_level_id)
            ->orderBy('subject_id')
            ->get();

        $grades = Grade::where('student_id', $student->id)
            ->whereIn('curriculum_id', $curricula->pluck('id'))
            ->get();

        $gradeMap = [];
        foreach ($grades as $g) {
            $gradeMap[$g->curriculum_id][$g->jenis_nilai] = $g->nilai;
        }

        $attendances = Attendance::where('student_id', $student->id)
            ->where('class_id', $history->class_id)
            ->get();

        $attendanceSummary = [
            'Hadir' => $attendances->where('status', 'Hadir')->count(),
            'Sakit' => $attendances->where('status', 'Sakit')->count(),
            'Izin'  => $attendances->where('status', 'Izin')->count(),
            'Alpha' => $attendances->where('status', 'Alpha')->count(),
            'total' => $attendances->count(),
        ];

        // Ambil data nilai kepribadian
        $personalities = \App\Models\PersonalityGrade::where('student_id', $student->id)
            ->where('academic_year_id', $yearId)
            ->get();

        $personalityMap = [];
        foreach ($personalities as $p) {
            $personalityMap[$p->aspek] = $p->predikat;
        }

        $jenisNilaiList = Grade::JENIS_NILAI;

        return view('reports.print', compact(
            'student', 'history', 'curricula', 'gradeMap',
            'attendanceSummary', 'personalityMap', 'jenisNilaiList', 'yearId'
        ));
    }
}
