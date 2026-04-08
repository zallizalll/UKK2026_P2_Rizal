<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\Tarif;
use App\Models\User;
use Illuminate\Http\Request;

class KendaraanController extends Controller
{
    public function index()
    {
        $kendaraans = Kendaraan::with(['tarif', 'user'])->latest('Created_at')->get();
        $tarifs = Tarif::all();
        $users  = User::all();
        return view('pages.kendaraan.index', compact('kendaraans', 'tarifs', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'plat_nomor' => 'required|string|max:255|unique:kendaraan,plat_nomor',
            'warna'      => 'required|string|max:255',
            'status'     => 'required|in:masuk,keluar',
            'id_Tarif'   => 'required|exists:tarif,id_Tarif',
            'id_user'    => 'required|exists:users,id_user',
        ]);

        Kendaraan::create($request->only('plat_nomor', 'warna', 'status', 'id_Tarif', 'id_user'));

        return view('pages.kendaraan.index')
            ->with('success', 'Kendaraan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $kendaraan = Kendaraan::findOrFail($id);

        $request->validate([
            'plat_nomor' => 'required|string|max:255|unique:kendaraan,plat_nomor,' . $id . ',id_kendaraan',
            'warna'      => 'required|string|max:255',
            'status'     => 'required|in:masuk,keluar',
            'id_Tarif'   => 'required|exists:tarif,id_Tarif',
            'id_user'    => 'required|exists:users,id_user',
        ]);

        $kendaraan->update($request->only('plat_nomor', 'warna', 'status', 'id_Tarif', 'id_user'));

        return view('pages.kendaraan.index')
            ->with('success', 'Data kendaraan berhasil diupdate.');
    }

    public function destroy($id)
    {
        Kendaraan::findOrFail($id)->delete();

        return view('pages.kendaraan.index')
            ->with('success', 'Kendaraan berhasil dihapus.');
    }
}