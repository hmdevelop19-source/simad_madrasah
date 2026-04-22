<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\AcademicTerm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * AcademicYearController — Modul 2: Tahun Ajaran
 *
 * Mundur ke sistem periode:
 *   - Kuartal : Kuartal 1 / 2 / 3 / 4 (Dikelola di AcademicTermController)
 *
 * Aturan penting: hanya SATU yang boleh is_active = true.
 * Saat setActive() dipanggil, semua record lain di-nonaktifkan dulu.
 */
class AcademicYearController extends Controller
{
    public function index()
    {
        $academicYears = AcademicYear::with('terms')
            ->orderByDesc('is_active')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('academic-years.index', compact('academicYears'));
    }

    public function create()
    {
        return view('academic-years.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:20|unique:academic_years,nama',
        ]);

        return DB::transaction(function () use ($validated, $request) {
            // Jika checkbox is_active dicentang, nonaktifkan yang lain terlebih dahulu
            if ($request->boolean('is_active')) {
                AcademicYear::query()->update(['is_active' => false]);
                $validated['is_active'] = true;
            } else {
                $validated['is_active'] = false;
            }

            $year = AcademicYear::create($validated);

            // AUTO-GENERATE 4 QUARTERS
            foreach (AcademicTerm::PERIODE_KUARTAL as $index => $termName) {
                AcademicTerm::create([
                    'academic_year_id' => $year->id,
                    'nama'             => $termName,
                    'is_active'        => $index === 0, // Kuartal 1 aktif secara default
                ]);
            }

            return redirect()->route('academic-years.index')
                ->with('success', "Tahun Ajaran '{$year->nama}' berhasil ditambahkan. Silakan buka menu 'Kuartal' untuk mengaktifkan periode penilaian.");
        });
    }

    public function edit(AcademicYear $academicYear)
    {
        return view('academic-years.edit', compact('academicYear'));
    }

    public function update(Request $request, AcademicYear $academicYear)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:20|unique:academic_years,nama,' . $academicYear->id,
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        if ($validated['is_active']) {
            AcademicYear::where('id', '!=', $academicYear->id)->update(['is_active' => false]);
        }

        $academicYear->update($validated);

        return redirect()->route('academic-years.index')
            ->with('success', "Tahun Ajaran '{$academicYear->nama}' berhasil diperbarui.");
    }

    /**
     * Route khusus: jadikan satu tahun ajaran AKTIF.
     * Nonaktifkan semua yang lain dulu, baru aktifkan yang dipilih.
     */
    public function setActive(AcademicYear $academicYear)
    {
        AcademicYear::query()->update(['is_active' => false]);
        $academicYear->update(['is_active' => true]);

        return back()->with('success', "Tahun Ajaran '{$academicYear->nama}' sekarang AKTIF.");
    }

    public function destroy(AcademicYear $academicYear)
    {
        if ($academicYear->is_active) {
            return back()->with('error', 'Tidak dapat menghapus Tahun Ajaran yang sedang aktif.');
        }

        if ($academicYear->studentHistories()->exists() || $academicYear->curriculums()->exists()) {
            return back()->with('error', "Tidak dapat menghapus: sudah ada data akademik terikat ke tahun ajaran ini.");
        }

        $nama = $academicYear->nama;
        $academicYear->delete();

        return redirect()->route('academic-years.index')
            ->with('success', "Tahun Ajaran '{$nama}' berhasil dihapus.");
    }
}
