<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KendaraanController extends Controller
{
    public function index()
    {
        $kendaraan = Kendaraan::paginate(10);

        return response()->json($kendaraan, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nopol' => 'required|unique:kendaraan,nopol',
            'namakendaraan' => 'required',
            'jeniskendaraan' => 'required',
            'namadriver' => 'required',
            'kontakdriver' => 'required|string|max:15',
            'tahun' => 'required|integer|between:1900,2155',
            'kapasitas' => 'required|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        $data = $validated;

        if ($request->hasFile('foto')) {
            try {
                $data['foto'] = $request->foto->store('kendaraan', 'public');
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Gagal mengupload foto.',
                    'error' => $e->getMessage()
                ], 500);
            }
        }

        $kendaraan = Kendaraan::create($data);

        return response()->json([
            'message' => 'Kendaraan berhasil dibuat.',
            'data' => $kendaraan
        ], 201);
    }

    public function show(Kendaraan $kendaraan)
    {
        return response()->json([
            'message' => 'Detail kendaraan.',
            'data' => $kendaraan
        ], 200);
    }

    public function update(Request $request, Kendaraan $kendaraan)
    {
        $validated = $request->validate([
            'nopol' => 'required|unique:kendaraan,nopol,' . $kendaraan->nopol . ',nopol',
            'namakendaraan' => 'required',
            'jeniskendaraan' => 'required',
            'namadriver' => 'required',
            'kontakdriver' => 'required|string|max:15',
            'tahun' => 'required|integer|between:1900,2155',
            'kapasitas' => 'required|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        $data = $validated;

        if ($request->hasFile('foto')) {
            try {
                $data['foto'] = $request->foto->store('kendaraan', 'public');

                if ($kendaraan->foto) {
                    Storage::disk('public')->delete($kendaraan->foto);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Gagal mengupload foto.',
                    'error' => $e->getMessage()
                ], 500);
            }
        } else {
            $data['foto'] = $kendaraan->foto;
        }

        $kendaraan->update($data);

        return response()->json([
            'message' => 'Kendaraan berhasil diupdate.',
            'data' => $kendaraan
        ], 200);
    }

    public function destroy(Kendaraan $kendaraan)
    {
        if ($kendaraan->masterkirim()->exists()) {
            return response()->json([
                'message' => 'Kendaraan "' . $kendaraan->nopol . '" tidak dapat dihapus karena masih digunakan dalam pengiriman.'
            ], 409); // Conflict
        }

        if ($kendaraan->foto) {
            Storage::disk('public')->delete($kendaraan->foto);
        }

        $kendaraan->delete();

        return response()->json([
            'message' => 'Kendaraan berhasil dihapus.'
        ], 200);
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;
        if (!$ids || !is_array($ids)) {
            return response()->json(['message' => 'Tidak ada data yang dipilih.'], 400);
        }

        $kendaraans = Kendaraan::whereIn('nopol', $ids)->get();
        $restricted = [];

        foreach ($kendaraans as $k) {
            if ($k->masterkirim()->exists()) {
                $restricted[] = $k->nopol;
            }
        }

        if (!empty($restricted)) {
            return response()->json([
                'message' => 'Beberapa kendaraan tidak dapat dihapus karena masih digunakan dalam pengiriman: ' . implode(', ', $restricted)
            ], 409);
        }

        foreach ($kendaraans as $k) {
            if ($k->foto) Storage::disk('public')->delete($k->foto);
            $k->delete();
        }

        return response()->json([
            'message' => count($ids) . ' kendaraan berhasil dihapus.'
        ], 200);
    }
}
