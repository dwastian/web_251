<?php

namespace App\Http\Controllers;

use App\Models\Gudang;
use Illuminate\Http\Request;

class GudangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $gudang = Gudang::all();

        return view('gudang.index', compact('gudang'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('gudang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kodegudang' => 'required|unique:gudang,kodegudang',
            'namagudang' => 'required',
            'alamat' => 'required',
            'kontak' => 'required',
            'kapasitas' => 'required|numeric',
        ]);

        Gudang::create($request->all());

        return redirect()->route('gudang.index')->with('success', 'Gudang berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Gudang $gudang)
    {
        return view('gudang.show', compact('gudang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gudang $gudang)
    {
        return view('gudang.edit', compact('gudang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gudang $gudang)
    {
        $request->validate([
            'kodegudang' => 'required|unique:gudang,kodegudang,' . $gudang->kodegudang . ',kodegudang',
            'namagudang' => 'required',
            'alamat' => 'required',
            'kontak' => 'required',
            'kapasitas' => 'required|numeric',
        ]);

        $gudang->update($request->all());

        return redirect()->route('gudang.index')->with('success', 'Gudang berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     * Remove the specified resource from storage.
     */
    public function destroy(Gudang $gudang)
    {
        if ($gudang->produk()->exists()) {
            return redirect()->route('gudang.index')->with('error', 'Gudang tidak dapat dihapus karena masih memiliki produk.');
        }

        $gudang->delete();

        return redirect()->route('gudang.index')->with('success', 'Gudang berhasil dihapus.');
    }
}
