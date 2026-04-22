<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $subjects = Subject::when($search, fn($q) => $q->where('nama_mapel', 'like', "%{$search}%")
                                                       ->orWhere('kode_mapel', 'like', "%{$search}%"))
            ->orderBy('kode_mapel')
            ->paginate(20);

        return view('subjects.index', compact('subjects', 'search'));
    }

    public function create()
    {
        return view('subjects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_mapel' => 'required|string|max:20|unique:subjects,kode_mapel',
            'nama_mapel' => 'required|string|max:100',
        ], ['kode_mapel.unique' => 'Kode mata pelajaran sudah digunakan.']);

        Subject::create($validated);

        return redirect()->route('subjects.index')
            ->with('success', "Mata Pelajaran '{$validated['nama_mapel']}' berhasil ditambahkan.");
    }

    public function edit(Subject $subject)
    {
        return view('subjects.edit', compact('subject'));
    }

    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'kode_mapel' => "required|string|max:20|unique:subjects,kode_mapel,{$subject->id}",
            'nama_mapel' => 'required|string|max:100',
        ]);

        $subject->update($validated);

        return redirect()->route('subjects.index')
            ->with('success', "Mata Pelajaran '{$subject->nama_mapel}' berhasil diperbarui.");
    }

    public function destroy(Subject $subject)
    {
        if ($subject->curriculums()->exists()) {
            return back()->with('error', "Tidak dapat menghapus: mapel ini terikat ke kurikulum aktif.");
        }

        $nama = $subject->nama_mapel;
        $subject->delete();

        return redirect()->route('subjects.index')
            ->with('success', "Mata Pelajaran '{$nama}' berhasil dihapus.");
    }
}
