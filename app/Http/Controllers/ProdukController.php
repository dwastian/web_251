<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index()
    {
        $produk = Produk::with('gudang')->get();

        return view('produk.index', compact('produk'));
    }

    public function create()
    {
        return view('produk.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kodeproduk' => 'required|unique:produk,kodeproduk',
            'nama' => 'required',
            'satuan' => 'required',
            'harga' => 'required|numeric',
            'kodegudang' => 'required|exists:gudang,kodegudang',
            'gambar' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->gambar->store('produk', 'public');
        } else {
            $data['gambar'] = null;
        }

        Produk::create($data);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil dibuat.');
    }

    public function show(Produk $produk)
    {
        return view('produk.show', compact('produk'));
    }

    public function edit(Produk $produk)
    {
        return view('produk.edit', compact('produk'));
    }

    public function update(Request $request, Produk $produk)
    {
        $request->validate([
            'kodeproduk' => 'required|unique:produk,kodeproduk,' . $produk->kodeproduk . ',kodeproduk',
            'nama' => 'required',
            'satuan' => 'required',
            'harga' => 'required|numeric',
            'kodegudang' => 'required|exists:gudang,kodegudang',
            'gambar' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->gambar->store('produk', 'public');
            if ($produk->gambar) {
                Storage::disk('public')->delete($produk->gambar);
            }
        } else {
            $data['gambar'] = $produk->gambar;
        }

        $produk->update($data);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diupdate.');
    }

    public function destroy(Produk $produk)
    {
        if ($produk->detailkirim()->exists()) {
            return redirect()->route('produk.index')->with('error', 'Produk tidak dapat dihapus karena masih digunakan dalam pengiriman.');
        }

        if ($produk->gambar) {
            Storage::disk('public')->delete($produk->gambar);
        }
        $produk->delete();

        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus.');
    }

    public function getProduk($id) {
        $produk = Produk::find($id);
        if ($produk) {
            return response()->json([
                'status' => 'success',
                'data' => $produk
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }
    }
}
