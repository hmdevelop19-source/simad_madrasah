<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Curriculum;
use App\Models\GradeLevel;
use App\Models\Subject;
use Illuminate\Http\Request;

class CurriculumController extends Controller
{
    public function index(Request $request)
    {
        $yearFilter   = $request->get('academic_year_id');
        $gradeFilter  = $request->get('grade_level_id');
        $viewType     = $request->get('view');

        $academicYears = AcademicYear::orderByDesc('is_active')->orderByDesc('created_at')->get();
        $gradeLevels   = GradeLevel::with('educationLevel')->orderBy('education_level_id')->get();

        // 1. DETAIL VIEW: Fetch specific subject entries
        $detailRows       = null;
        $activeGradeLevel = null;
        if ($viewType === 'detail' && $yearFilter && $gradeFilter) {
            $detailRows = Curriculum::with(['subject', 'academicYear', 'gradeLevel.educationLevel'])
                ->where('academic_year_id', $yearFilter)
                ->where('grade_level_id', $gradeFilter)
                ->get()
                ->sortBy(fn($c) => $c->subject?->kode_mapel);
            
            $activeGradeLevel = GradeLevel::with('educationLevel')->find($gradeFilter);
        }

        // 2. SUMMARY DATA: Count subjects per grade level
        $summary = Curriculum::with(['academicYear', 'gradeLevel.educationLevel'])
            ->when($yearFilter, fn($q) => $q->where('academic_year_id', $yearFilter))
            ->selectRaw('academic_year_id, grade_level_id, COUNT(*) as jumlah_mapel')
            ->groupBy('academic_year_id', 'grade_level_id')
            ->orderBy('academic_year_id', 'desc')
            ->orderBy('grade_level_id')
            ->get();

        // Group: academic_year_id → education_level_id → [rows]
        $grouped = $summary->groupBy('academic_year_id')->map(function ($yearRows) {
            return $yearRows->groupBy(fn($r) => $r->gradeLevel?->education_level_id ?? 0);
        });

        return view('curriculums.index', compact(
            'grouped', 'academicYears', 'gradeLevels', 'yearFilter', 'detailRows', 'activeGradeLevel'
        ));
    }


    public function create()
    {
        $academicYears = AcademicYear::orderByDesc('is_active')->orderByDesc('created_at')->get();
        $gradeLevels   = GradeLevel::with('educationLevel')->orderBy('education_level_id')->get();
        $subjects      = Subject::orderBy('kode_mapel')->get();

        return view('curriculums.create', compact('academicYears', 'gradeLevels', 'subjects'));
    }

    /**
     * Bulk store: simpan banyak mapel sekaligus untuk 1 kelas + 1 tahun ajaran.
     */
    public function store(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'grade_level_id'   => 'required|exists:grade_levels,id',
            'subject_ids'      => 'required|array|min:1',
            'subject_ids.*'    => 'exists:subjects,id',
            'kkm_default'      => 'required|integer|min:0|max:100',
        ], [
            'subject_ids.required' => 'Pilih minimal 1 mata pelajaran.',
        ]);

        $yearId   = $request->academic_year_id;
        $levelId  = $request->grade_level_id;
        $kkm      = $request->kkm_default;
        $inserted = 0;
        $skipped  = 0;

        foreach ($request->subject_ids as $subjectId) {
            $exists = Curriculum::where('academic_year_id', $yearId)
                ->where('grade_level_id', $levelId)
                ->where('subject_id', $subjectId)
                ->exists();

            if ($exists) { $skipped++; continue; }

            Curriculum::create([
                'academic_year_id' => $yearId,
                'grade_level_id'   => $levelId,
                'subject_id'       => $subjectId,
                'kkm'              => $kkm,
            ]);
            $inserted++;
        }

        $msg = "{$inserted} mapel berhasil ditambahkan ke kurikulum.";
        if ($skipped) $msg .= " ({$skipped} dilewati karena sudah ada.)";

        return redirect()->route('curriculums.index', [
            'academic_year_id' => $yearId,
            'grade_level_id'   => $levelId,
        ])->with('success', $msg);
    }

    /**
     * Duplikat seluruh kurikulum dari kelas sumber ke kelas tujuan.
     */
    public function duplicate(Request $request)
    {
        $request->validate([
            'src_academic_year_id'  => 'required|exists:academic_years,id',
            'src_grade_level_id'    => 'required|exists:grade_levels,id',
            'dst_academic_year_id'  => 'required|exists:academic_years,id',
            'dst_grade_level_id'    => 'required|exists:grade_levels,id',
        ]);

        $sources = Curriculum::where('academic_year_id', $request->src_academic_year_id)
            ->where('grade_level_id', $request->src_grade_level_id)
            ->get();

        if ($sources->isEmpty()) {
            return back()->with('error', 'Kurikulum sumber tidak ditemukan atau kosong.');
        }

        $inserted = 0; $skipped = 0;
        foreach ($sources as $src) {
            $exists = Curriculum::where('academic_year_id', $request->dst_academic_year_id)
                ->where('grade_level_id', $request->dst_grade_level_id)
                ->where('subject_id', $src->subject_id)
                ->exists();

            if ($exists) { $skipped++; continue; }

            Curriculum::create([
                'academic_year_id' => $request->dst_academic_year_id,
                'grade_level_id'   => $request->dst_grade_level_id,
                'subject_id'       => $src->subject_id,
                'kkm'              => $src->kkm,
            ]);
            $inserted++;
        }

        $msg = "{$inserted} mapel berhasil diduplikat.";
        if ($skipped) $msg .= " ({$skipped} dilewati.)";

        return redirect()->route('curriculums.index', [
            'academic_year_id' => $request->dst_academic_year_id,
            'grade_level_id'   => $request->dst_grade_level_id,
        ])->with('success', $msg);
    }

    public function edit(Curriculum $curriculum)
    {
        $curriculum->load(['academicYear', 'gradeLevel', 'subject']);
        $academicYears = AcademicYear::orderByDesc('is_active')->get();
        $gradeLevels   = GradeLevel::with('educationLevel')->get();
        $subjects      = Subject::orderBy('kode_mapel')->get();

        return view('curriculums.edit', compact('curriculum', 'academicYears', 'gradeLevels', 'subjects'));
    }

    public function update(Request $request, Curriculum $curriculum)
    {
        $validated = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'grade_level_id'   => 'required|exists:grade_levels,id',
            'subject_id'       => 'required|exists:subjects,id',
            'kkm'              => 'required|integer|min:0|max:100',
        ]);

        $curriculum->update($validated);

        return redirect()->route('curriculums.index')
            ->with('success', 'Entri kurikulum berhasil diperbarui.');
    }

    public function destroy(Curriculum $curriculum)
    {
        if ($curriculum->grades()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus: sudah ada nilai santri terikat ke kurikulum ini.');
        }

        $curriculum->delete();

        return redirect()->route('curriculums.index')
            ->with('success', 'Entri kurikulum berhasil dihapus.');
    }
}
