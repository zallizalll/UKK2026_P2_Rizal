<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\Tarif;
use App\Models\User;
use App\Traits\LogAktivitasTrait;
use Illuminate\Http\Request;

class KendaraanController extends Controller
{
    use LogAktivitasTrait;

    public function index()
    {
        $kendaraans = Kendaraan::with(['tarif', 'user'])->latest('Created_at')->get();
        $tarifs     = Tarif::orderBy('jenis_kendaraan')->get();
        $users      = User::orderBy('name')->get();
        return view('pages.kendaraan.index', compact('kendaraans', 'tarifs', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'plat_nomor' => 'required|string|max:255|unique:kendaraan,plat_nomor',
            'warna'      => 'required|string|max:255',
            'id_Tarif'   => 'required|exists:tarif,id_tarif',
            'id_user'    => 'required|exists:users,id_user',
        ]);

        $kendaraan = Kendaraan::create([
            'plat_nomor' => strtoupper($request->plat_nomor),
            'warna'      => $request->warna,
            'status'     => 'masuk',
            'id_Tarif'   => $request->id_Tarif,
            'id_user'    => $request->id_user,
        ]);

        $jenis = $kendaraan->tarif->jenis_kendaraan ?? '-';
        $this->log('Tambah kendaraan: ' . strtoupper($request->plat_nomor) . ' (' . $request->warna . ') — ' . $jenis);

        return redirect()->route('admin.kendaraan')->with('success', 'Kendaraan berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $kendaraan = Kendaraan::findOrFail($id);

        $request->validate([
            'plat_nomor' => 'required|string|max:255|unique:kendaraan,plat_nomor,' . $id . ',id_kendaraan',
            'warna'      => 'required|string|max:255',
            'status'     => 'required|in:masuk,keluar',
            'id_Tarif'   => 'required|exists:tarif,id_tarif',
            'id_user'    => 'required|exists:users,id_user',
        ]);

        $kendaraan->update([
            'plat_nomor' => strtoupper($request->plat_nomor),
            'warna'      => $request->warna,
            'status'     => $request->status,
            'id_Tarif'   => $request->id_Tarif,
            'id_user'    => $request->id_user,
        ]);

        $this->log('Update kendaraan: ' . strtoupper($request->plat_nomor) . ' — status: ' . $request->status);

        return redirect()->route('admin.kendaraan')->with('success', 'Data kendaraan berhasil diupdate!');
    }

    public function destroy($id)
    {
        $kendaraan = Kendaraan::findOrFail($id);

        $this->log('Hapus kendaraan: ' . $kendaraan->plat_nomor . ' (' . $kendaraan->warna . ')');

        $kendaraan->delete();

        return redirect()->route('admin.kendaraan')->with('success', 'Kendaraan berhasil dihapus!');
    }

    public function print()
    {
        $kendaraans = Kendaraan::with(['tarif', 'user'])->latest('Created_at')->get();
        return view('pages.kendaraan.print', compact('kendaraans'));
    }
}
