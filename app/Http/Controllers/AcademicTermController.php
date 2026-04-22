<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\AcademicTerm;
use Illuminate\Http\Request;

class AcademicTermController extends Controller
{
    /**
     * Tampilkan daftar Kuartal untuk Tahun Ajaran yang sedang AKTIF.
     */
    public function index()
    {
        $activeYear = AcademicYear::where('is_active', true)->first();

        if (!$activeYear) {
            return view('academic-terms.index', [
                'terms' => collect([]),
                'activeYear' => null,
                'error' => 'Belum ada Tahun Ajaran yang aktif. Silakan aktifkan tahun ajaran terlebih dahulu di menu Tahun Ajaran.'
            ]);
        }

        $terms = AcademicTerm::where('academic_year_id', $activeYear->id)
            ->orderBy('id')
            ->get();

        return view('academic-terms.index', compact('terms', 'activeYear'));
    }

    /**
     * Aktifkan satu Kuartal dalam Tahun Ajaran tertentu.
     * Nonaktifkan kuartal lain dalam tahun yang sama.
     */
    public function setActive(AcademicTerm $academicTerm)
    {
        // Pastikan hanya kuartal di tahun yang sama yang di-nonaktifkan
        AcademicTerm::where('academic_year_id', $academicTerm->academic_year_id)
            ->update(['is_active' => false]);

        $academicTerm->update(['is_active' => true]);

        return back()->with('success', "Kuartal '{$academicTerm->nama}' berhasil diaktifkan untuk periode {$academicTerm->academicYear->nama}.");
    }
}
