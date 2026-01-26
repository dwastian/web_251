<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index()
    {
        $produk = Produk::with('gudang')->paginate(10);
        return response()->json($produk);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kodeproduk' => 'required|unique:produk,kodeproduk',
            'nama' => 'required',
            'satuan' => 'required',
            'harga' => 'required|numeric',
            'kodegudang' => 'required|exists:gudang,kodegudang',
            'gambar' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->gambar->store('produk', 'public');
        }

        $produk = Produk::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dibuat.',
            'data' => $produk
        ], 201);
    }

    public function show(Produk $produk)
    {
        return response()->json([
            'success' => true,
            'data' => $produk->load('gudang')
        ]);
    }

    public function update(Request $request, Produk $produk)
    {
        $validated = $request->validate([
            'kodeproduk' => 'required|unique:produk,kodeproduk,' . $produk->kodeproduk . ',kodeproduk',
            'nama' => 'required',
            'satuan' => 'required',
            'harga' => 'required|numeric',
            'kodegudang' => 'required|exists:gudang,kodegudang',
            'gambar' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->gambar->store('produk', 'public');
            if ($produk->gambar) Storage::disk('public')->delete($produk->gambar);
        }

        $produk->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil diupdate.',
            'data' => $produk
        ]);
    }

    public function destroy(Produk $produk)
    {
        if ($produk->detailkirim()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak dapat dihapus karena masih digunakan dalam pengiriman.'
            ], 409);
        }

        if ($produk->gambar) Storage::disk('public')->delete($produk->gambar);

        $produk->delete();

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dihapus.'
        ]);
    }

    public function getProduk($id)
    {
        $produk = Produk::find($id);
        if ($produk) {
            return response()->json([
                'success' => true,
                'data' => $produk
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Produk tidak ditemukan'
        ], 404);
    }
}
