<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::with(['kendaraan.tarif', 'area'])
            ->orderBy('waktu_masuk', 'desc')
            ->get();

        return view('pages.owner.transaksi.index', compact('transaksis'));
    }

    public function print()
    {
        $transaksis = Transaksi::with(['kendaraan.tarif', 'area'])
            ->orderBy('waktu_masuk', 'desc')
            ->get();

        return view('pages.owner.transaksi.print', compact('transaksis'));
    }
}
