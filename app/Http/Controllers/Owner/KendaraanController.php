<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;

class KendaraanController extends Controller
{
    public function index()
    {
        $kendaraans = Kendaraan::with(['tarif', 'user', 'transaksis.area'])
            ->latest()
            ->get();

        return view('pages.owner.kendaraan.index', compact('kendaraans'));
    }

    public function print()
    {
        $kendaraans = Kendaraan::with(['tarif', 'user', 'transaksis.area'])
            ->latest()
            ->get();

        return view('pages.owner.kendaraan.print', compact('kendaraans'));
    }
}
