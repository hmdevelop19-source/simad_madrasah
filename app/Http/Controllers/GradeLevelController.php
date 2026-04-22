<?php

namespace App\Http\Controllers;

use App\Models\EducationLevel;
use App\Models\GradeLevel;
use Illuminate\Http\Request;

class GradeLevelController extends Controller
{
    public function index(Request $request)
    {
        $search      = $request->get('search');
        $levelFilter = $request->get('education_level_id');

        $gradeLevels = GradeLevel::with('educationLevel')
            ->withCount('classrooms')
            ->when($search, fn($q) => $q->where('nama_tingkat', 'like', "%{$search}%"))
            ->when($levelFilter, fn($q) => $q->where('education_level_id', $levelFilter))
            ->orderBy('education_level_id')
            ->orderBy('nama_tingkat')
            ->paginate(20);

        $educationLevels = EducationLevel::orderBy('kode')->get();

        return view('grade-levels.index', compact('gradeLevels', 'educationLevels', 'search', 'levelFilter'));
    }

    public function create()
    {
        $educationLevels = EducationLevel::orderBy('kode')->get();
        return view('grade-levels.create', compact('educationLevels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'education_level_id' => 'required|exists:education_levels,id',
            'nama_tingkat'       => 'required|string|max:50',
        ]);

        GradeLevel::create($validated);

        return redirect()->route('grade-levels.index')
            ->with('success', "Tingkat Kelas '{$validated['nama_tingkat']}' berhasil ditambahkan.");
    }

    public function edit(GradeLevel $gradeLevel)
    {
        $educationLevels = EducationLevel::orderBy('kode')->get();
        return view('grade-levels.edit', compact('gradeLevel', 'educationLevels'));
    }

    public function update(Request $request, GradeLevel $gradeLevel)
    {
        $validated = $request->validate([
            'education_level_id' => 'required|exists:education_levels,id',
            'nama_tingkat'       => 'required|string|max:50',
        ]);

        $gradeLevel->update($validated);

        return redirect()->route('grade-levels.index')
            ->with('success', "Tingkat Kelas '{$gradeLevel->nama_tingkat}' berhasil diperbarui.");
    }

    public function destroy(GradeLevel $gradeLevel)
    {
        if ($gradeLevel->classrooms()->exists()) {
            return back()->with('error', "Tidak dapat menghapus: tingkat ini masih memiliki kelas.");
        }

        $nama = $gradeLevel->nama_tingkat;
        $gradeLevel->delete();

        return redirect()->route('grade-levels.index')
            ->with('success', "Tingkat Kelas '{$nama}' berhasil dihapus.");
    }
}
