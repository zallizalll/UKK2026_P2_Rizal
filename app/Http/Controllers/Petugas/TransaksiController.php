<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::with(['kendaraan.tarif', 'area', 'user'])
            ->latest('waktu_masuk')
            ->get();
        return view('pages.petugas.transaksi.index', compact('transaksis'));
    }

    public function struk($id)
    {
        $transaksi = Transaksi::with(['kendaraan.tarif', 'area', 'user'])
            ->findOrFail($id);
        return view('pages.petugas.transaksi.struk', compact('transaksi'));
    }
}
