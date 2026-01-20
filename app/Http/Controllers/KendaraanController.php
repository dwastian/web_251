<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KendaraanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kendaraan = Kendaraan::all();

        return view('kendaraan.index', compact('kendaraan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kendaraan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nopol' => 'required|unique:kendaraan,nopol',
            'namakendaraan' => 'required',
            'jeniskendaraan' => 'required',
            'namadriver' => 'required',
            'kontakdriver' => 'required|string|max:15',
            'tahun' => 'required|integer|between:1900,2155',
            'kapasitas' => 'required|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            try {
                $data['foto'] = $request->foto->store('kendaraan', 'public');
            } catch (\Exception $e) {
                return back()->with('error', 'Gagal mengupload foto: ' . $e->getMessage())->withInput();
            }
        } else {
            $data['foto'] = null;
        }

        Kendaraan::create($data);

        return redirect()->route('kendaraan.index')->with('success', 'Kendaraan berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kendaraan $kendaraan)
    {
        return view('kendaraan.show', compact('kendaraan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kendaraan $kendaraan)
    {
        return view('kendaraan.edit', compact('kendaraan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kendaraan $kendaraan)
    {
        $request->validate([
            'nopol' => 'required|unique:kendaraan,nopol,' . $kendaraan->nopol . ',nopol',
            'namakendaraan' => 'required',
            'jeniskendaraan' => 'required',
            'namadriver' => 'required',
            'kontakdriver' => 'required|string|max:15',
            'tahun' => 'required|integer|between:1900,2155',
            'kapasitas' => 'required|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            try {
                $data['foto'] = $request->foto->store('kendaraan', 'public');
                if ($kendaraan->foto) {
                    Storage::disk('public')->delete($kendaraan->foto);
                }
            } catch (\Exception $e) {
                return back()->with('error', 'Gagal mengupload foto: ' . $e->getMessage())->withInput();
            }
        } else {
            $data['foto'] = $kendaraan->foto;
        }

        $kendaraan->update($data);

        return redirect()->route('kendaraan.index')->with('success', 'Kendaraan berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kendaraan $kendaraan)
    {
        if ($kendaraan->masterkirim()->exists()) {
            return redirect()->route('kendaraan.index')->with('error', 'Kendaraan tidak dapat dihapus karena masih digunakan dalam pengiriman.');
        }

        if ($kendaraan->foto) {
            Storage::disk('public')->delete($kendaraan->foto);
        }

        $kendaraan->delete();

        return redirect()->route('kendaraan.index')->with('success', 'Kendaraan berhasil dihapus.');
    }
}
