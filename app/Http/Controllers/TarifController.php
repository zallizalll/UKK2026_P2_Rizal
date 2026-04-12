<?php

namespace App\Http\Controllers;

use App\Models\Tarif;
use App\Traits\LogAktivitasTrait;
use Illuminate\Http\Request;

class TarifController extends Controller
{
    use LogAktivitasTrait;

    public function index()
    {
        $tarifs = Tarif::orderBy('id_tarif', 'desc')->get();
        return view('pages.tarif.index', compact('tarifs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_kendaraan' => 'required|string|max:255',
            'tarif_per_jam'   => 'required|numeric|min:0',
        ]);

        Tarif::create([
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'tarif_per_jam'   => $request->tarif_per_jam,
        ]);

        $this->log('Tambah tarif: ' . $request->jenis_kendaraan . ' — Rp ' . number_format($request->tarif_per_jam, 0, ',', '.') . '/jam');

        return redirect()->route('admin.tarif')->with('success', 'Tarif berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $tarif = Tarif::findOrFail($id);

        $request->validate([
            'jenis_kendaraan' => 'required|string|max:255',
            'tarif_per_jam'   => 'required|numeric|min:0',
        ]);

        $tarif->update([
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'tarif_per_jam'   => $request->tarif_per_jam,
        ]);

        $this->log('Update tarif: ' . $request->jenis_kendaraan . ' — Rp ' . number_format($request->tarif_per_jam, 0, ',', '.') . '/jam');

        return redirect()->route('admin.tarif')->with('success', 'Tarif berhasil diupdate!');
    }

    public function destroy($id)
    {
        $tarif = Tarif::findOrFail($id);

        $this->log('Hapus tarif: ' . $tarif->jenis_kendaraan . ' — Rp ' . number_format($tarif->tarif_per_jam, 0, ',', '.') . '/jam');

        $tarif->delete();

        return redirect()->route('admin.tarif')->with('success', 'Tarif berhasil dihapus!');
    }

    public function print()
    {
        $tarifs = Tarif::orderBy('jenis_kendaraan', 'asc')->get();
        return view('pages.tarif.print', compact('tarifs'));
    }
}
