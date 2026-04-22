<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\EducationLevel;
use App\Models\GradeLevel;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ClassroomController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:view-rombel', only: ['index', 'show']),
            new Middleware('can:create-rombel', only: ['create', 'store']),
            new Middleware('can:edit-rombel', only: ['edit', 'update']),
            new Middleware('can:delete-rombel', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $levelFilter = $request->get('education_level_id');

        $classrooms = Classroom::with(['gradeLevel.educationLevel', 'waliKelas'])
            ->when($levelFilter, fn($q) => $q->whereHas('gradeLevel', fn($q2) =>
                $q2->where('education_level_id', $levelFilter)))
            ->orderBy('nama_kelas')
            ->paginate(20);

        $educationLevels = EducationLevel::orderBy('kode')->get();

        return view('classrooms.index', compact('classrooms', 'educationLevels', 'levelFilter'));
    }

    public function create()
    {
        $gradeLevels = GradeLevel::with('educationLevel')->orderBy('education_level_id')->orderBy('nama_tingkat')->get();
        $teachers    = Teacher::where('is_active', true)->orderBy('nama_lengkap')->get();

        return view('classrooms.create', compact('gradeLevels', 'teachers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'grade_level_id'  => 'required|exists:grade_levels,id',
            'nama_kelas'      => 'required|string|max:50',
            'wali_kelas_id'   => 'nullable|exists:teachers,id',
        ]);

        Classroom::create($validated);

        return redirect()->route('classrooms.index')
            ->with('success', "Kelas '{$validated['nama_kelas']}' berhasil ditambahkan.");
    }

    public function edit(Classroom $classroom)
    {
        $gradeLevels = GradeLevel::with('educationLevel')->orderBy('education_level_id')->orderBy('nama_tingkat')->get();
        $teachers    = Teacher::where('is_active', true)->orderBy('nama_lengkap')->get();

        return view('classrooms.edit', compact('classroom', 'gradeLevels', 'teachers'));
    }

    public function update(Request $request, Classroom $classroom)
    {
        $validated = $request->validate([
            'grade_level_id' => 'required|exists:grade_levels,id',
            'nama_kelas'     => 'required|string|max:50',
            'wali_kelas_id'  => 'nullable|exists:teachers,id',
        ]);

        $classroom->update($validated);

        return redirect()->route('classrooms.index')
            ->with('success', "Kelas '{$classroom->nama_kelas}' berhasil diperbarui.");
    }

    public function destroy(Classroom $classroom)
    {
        if ($classroom->studentHistories()->exists()) {
            return back()->with('error', "Tidak dapat menghapus: kelas ini memiliki riwayat santri.");
        }

        $nama = $classroom->nama_kelas;
        $classroom->delete();

        return redirect()->route('classrooms.index')
            ->with('success', "Kelas '{$nama}' berhasil dihapus.");
    }
}
