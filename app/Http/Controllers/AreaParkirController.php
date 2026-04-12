<?php

namespace App\Http\Controllers;

use App\Models\AreaParkir;
use App\Traits\LogAktivitasTrait;
use Illuminate\Http\Request;

class AreaParkirController extends Controller
{
    use LogAktivitasTrait;

    public function index()
    {
        $areas = AreaParkir::orderBy('nama_area', 'asc')->get();
        return view('pages.area.index', compact('areas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_area' => 'required|string|max:255|unique:area_parkir,nama_area',
            'kapasitas' => 'required|integer|min:1',
        ]);

        AreaParkir::create([
            'nama_area' => $request->nama_area,
            'kapasitas' => $request->kapasitas,
            'terisi'    => 0,
        ]);

        $this->log('Tambah area parkir: ' . $request->nama_area . ' — kapasitas ' . $request->kapasitas . ' slot');

        return redirect()->route('admin.area')->with('success', 'Area parkir berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $area = AreaParkir::findOrFail($id);

        $request->validate([
            'nama_area' => 'required|string|max:255|unique:area_parkir,nama_area,' . $id . ',id_area',
            'kapasitas' => 'required|integer|min:' . $area->terisi,
        ]);

        $area->update([
            'nama_area' => $request->nama_area,
            'kapasitas' => $request->kapasitas,
        ]);

        $this->log('Update area parkir: ' . $request->nama_area . ' — kapasitas ' . $request->kapasitas . ' slot');

        return redirect()->route('admin.area')->with('success', 'Area parkir berhasil diupdate!');
    }

    public function destroy($id)
    {
        $area = AreaParkir::findOrFail($id);

        if ($area->terisi > 0) {
            return redirect()->route('admin.area')->with('error', 'Area masih terisi kendaraan, tidak bisa dihapus!');
        }

        $this->log('Hapus area parkir: ' . $area->nama_area . ' — kapasitas ' . $area->kapasitas . ' slot');

        $area->delete();

        return redirect()->route('admin.area')->with('success', 'Area parkir berhasil dihapus!');
    }

    public function print()
    {
        $areas = AreaParkir::orderBy('nama_area', 'asc')->get();
        return view('pages.area.print', compact('areas'));
    }
}
