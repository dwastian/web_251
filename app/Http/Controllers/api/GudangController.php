<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Gudang;
use Illuminate\Http\Request;

class GudangController extends Controller
{
    public function index()
    {
        $gudang = Gudang::all();

        return response()->json([
            'message' => 'Data gudang.',
            'data' => $gudang
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kodegudang' => 'required|unique:gudang,kodegudang',
            'namagudang' => 'required',
            'alamat' => 'required',
            'kontak' => 'required',
            'kapasitas' => 'required|numeric',
        ]);

        $gudang = Gudang::create($validated);

        return response()->json([
            'message' => 'Gudang berhasil dibuat.',
            'data' => $gudang
        ], 201); // Created
    }

    public function show(Gudang $gudang)
    {
        return response()->json([
            'message' => 'Detail gudang.',
            'data' => $gudang
        ], 200);
    }

    public function update(Request $request, Gudang $gudang)
    {
        $validated = $request->validate([
            'kodegudang' => 'required|unique:gudang,kodegudang,' . $gudang->kodegudang . ',kodegudang',
            'namagudang' => 'required',
            'alamat' => 'required',
            'kontak' => 'required',
            'kapasitas' => 'required|numeric',
        ]);

        $gudang->update($validated);

        return response()->json([
            'message' => 'Gudang berhasil diupdate.',
            'data' => $gudang
        ], 200);
    }

    public function destroy(Gudang $gudang)
    {
        if ($gudang->produk()->exists()) {
            return response()->json([
                'message' => 'Gudang tidak dapat dihapus karena masih memiliki produk.'
            ], 409); // Conflict
        }

        $gudang->delete();

        return response()->json([
            'message' => 'Gudang berhasil dihapus.'
        ], 200);
    }
}

