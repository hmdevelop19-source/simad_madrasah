<?php

namespace App\Http\Controllers;

use App\Models\EducationLevel;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

/**
 * EducationLevelController — Modul 1: Unit Pendidikan
 *
 * CRUD untuk tabel education_levels.
 * Data ini adalah fondasi paling dasar sistem SIMAD.
 * Setiap unit (TK, MI, MTs, Ulya) memiliki santri, guru, dan kelas sendiri.
 */
class EducationLevelController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:view-unit', only: ['index', 'show']),
            new Middleware('can:create-unit', only: ['create', 'store']),
            new Middleware('can:edit-unit', only: ['edit', 'update']),
            new Middleware('can:delete-unit', only: ['destroy']),
        ];
    }

    /** Tampilkan daftar semua Unit Pendidikan */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $levels = EducationLevel::withCount([
            'students' => fn($q) => $q->where('status_aktif', 'Aktif'),
        ])
        ->when($search, fn($q) => $q->where('nama', 'like', "%{$search}%")
                                    ->orWhere('kode', 'like', "%{$search}%"))
        ->orderBy('kode')
        ->paginate(15);

        return view('education-levels.index', compact('levels', 'search'));
    }

    /** Form tambah unit pendidikan baru */
    public function create()
    {
        return view('education-levels.create');
    }

    /** Proses simpan unit pendidikan baru */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:10|unique:education_levels,kode',
            'nama' => 'required|string|max:100|unique:education_levels,nama',
            'kop_surat' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'kode.unique' => 'Kode unit pendidikan sudah digunakan.',
            'nama.unique' => 'Nama unit pendidikan sudah terdaftar.',
            'kop_surat.image' => 'File KOP harus berupa gambar.',
            'kop_surat.max'   => 'Ukuran file maksimal 2MB.',
        ]);

        $data = [
            'kode' => $validated['kode'],
            'nama' => $validated['nama'],
        ];

        if ($request->hasFile('kop_surat')) {
            $path = $request->file('kop_surat')->store('kop_surat', 'public');
            $data['kop_surat'] = $path;
        }

        EducationLevel::create($data);

        return redirect()->route('education-levels.index')
            ->with('success', "Unit Pendidikan '{$validated['nama']}' berhasil ditambahkan.");
    }

    /** Form edit unit pendidikan */
    public function edit(EducationLevel $educationLevel)
    {
        return view('education-levels.edit', compact('educationLevel'));
    }

    /** Proses update unit pendidikan */
    public function update(Request $request, EducationLevel $educationLevel)
    {
        $validated = $request->validate([
            'kode' => "required|string|max:10|unique:education_levels,kode,{$educationLevel->id}",
            'nama' => "required|string|max:100|unique:education_levels,nama,{$educationLevel->id}",
            'kop_surat' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = [
            'kode' => $validated['kode'],
            'nama' => $validated['nama'],
        ];

        if ($request->hasFile('kop_surat')) {
            // Hapus file lama jika ada
            if ($educationLevel->kop_surat && \Illuminate\Support\Facades\Storage::disk('public')->exists($educationLevel->kop_surat)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($educationLevel->kop_surat);
            }
            $path = $request->file('kop_surat')->store('kop_surat', 'public');
            $data['kop_surat'] = $path;
        }

        $educationLevel->update($data);

        return redirect()->route('education-levels.index')
            ->with('success', "Unit Pendidikan '{$educationLevel->nama}' berhasil diperbarui.");
    }

    /** Hapus unit pendidikan (hanya jika tidak ada santri aktif) */
    public function destroy(EducationLevel $educationLevel)
    {
        // Cegah hapus jika masih ada santri aktif terikat ke unit ini
        if ($educationLevel->students()->where('status_aktif', 'Aktif')->exists()) {
            return back()->with('error', "Tidak dapat menghapus unit '{$educationLevel->nama}' karena masih ada santri aktif.");
        }

        $nama = $educationLevel->nama;
        $educationLevel->delete();

        return redirect()->route('education-levels.index')
            ->with('success', "Unit Pendidikan '{$nama}' berhasil dihapus.");
    }
}
