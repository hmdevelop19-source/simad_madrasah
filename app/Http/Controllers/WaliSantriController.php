<?php

namespace App\Http\Controllers;

use App\Models\WaliSantri;
use Illuminate\Http\Request;

class WaliSantriController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $waliList = WaliSantri::withCount('students')
            ->when($search, fn($q) => $q->where('nama_lengkap', 'like', "%{$search}%")
                                        ->orWhere('nik', 'like', "%{$search}%")
                                        ->orWhere('no_whatsapp', 'like', "%{$search}%"))
            ->orderBy('nama_lengkap')
            ->paginate(20);

        return view('wali-santri.index', compact('waliList', 'search'));
    }

    public function create()
    {
        return view('wali-santri.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik'               => 'required|string|size:16|unique:wali_santri,nik',
            'nama_lengkap'      => 'required|string|max:100',
            'hubungan_keluarga' => 'required|in:Ayah,Ibu,Kakek,Nenek,Paman,Bibi,Wali Lainnya',
            'pendidikan_terakhir' => 'required|in:SD/MI,SMP/MTs,SMA/MA,D3,S1,S2,S3,Lainnya',
            'pekerjaan'         => 'required|string|max:100',
            'penghasilan_bulanan' => 'required|in:< 1 Juta,1-3 Juta,3-5 Juta,5-10 Juta,> 10 Juta',
            'no_whatsapp'       => 'required|string|max:20|unique:wali_santri,no_whatsapp',
            'alamat_lengkap'    => 'required|string',
        ], [
            'nik.size'              => 'NIK harus 16 digit.',
            'nik.unique'            => 'NIK sudah terdaftar.',
            'no_whatsapp.unique'    => 'Nomor WhatsApp sudah terdaftar.',
        ]);

        WaliSantri::create($validated);

        return redirect()->route('wali-santri.index')
            ->with('success', "Data Wali Santri '{$validated['nama_lengkap']}' berhasil ditambahkan.");
    }

    public function show(WaliSantri $waliSantri)
    {
        $waliSantri->load(['students.educationLevel']);
        return view('wali-santri.show', compact('waliSantri'));
    }

    public function edit(WaliSantri $waliSantri)
    {
        return view('wali-santri.edit', compact('waliSantri'));
    }

    public function update(Request $request, WaliSantri $waliSantri)
    {
        $validated = $request->validate([
            'nik'               => "required|string|size:16|unique:wali_santri,nik,{$waliSantri->id}",
            'nama_lengkap'      => 'required|string|max:100',
            'hubungan_keluarga' => 'required|in:Ayah,Ibu,Kakek,Nenek,Paman,Bibi,Wali Lainnya',
            'pendidikan_terakhir' => 'required|in:SD/MI,SMP/MTs,SMA/MA,D3,S1,S2,S3,Lainnya',
            'pekerjaan'         => 'required|string|max:100',
            'penghasilan_bulanan' => 'required|in:< 1 Juta,1-3 Juta,3-5 Juta,5-10 Juta,> 10 Juta',
            'no_whatsapp'       => "required|string|max:20|unique:wali_santri,no_whatsapp,{$waliSantri->id}",
            'alamat_lengkap'    => 'required|string',
        ]);

        $waliSantri->update($validated);

        return redirect()->route('wali-santri.index')
            ->with('success', "Data Wali Santri '{$waliSantri->nama_lengkap}' berhasil diperbarui.");
    }

    public function destroy(WaliSantri $waliSantri)
    {
        if ($waliSantri->students()->exists()) {
            return back()->with('error', "Tidak dapat menghapus: wali ini masih memiliki santri terdaftar.");
        }

        $nama = $waliSantri->nama_lengkap;
        $waliSantri->delete();

        return redirect()->route('wali-santri.index')
            ->with('success', "Data Wali Santri '{$nama}' berhasil dihapus.");
    }
}
