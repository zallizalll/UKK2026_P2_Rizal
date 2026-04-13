<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

    public function bayar($id)
    {
        $transaksi = Transaksi::with(['kendaraan.tarif', 'area', 'user'])
            ->findOrFail($id);
        return view('pages.petugas.transaksi.bayar', compact('transaksi'));
    }

    public function prosesBayar(Request $request, $id)
    {
        $request->validate([
            'metode_pembayaran' => 'required|in:cash,qris',
        ]);

        $transaksi = Transaksi::findOrFail($id);

        if ($transaksi->status !== 'pending') {
            return redirect()->route('petugas.transaksi')
                ->with('error', 'Transaksi ini tidak dalam status pending.');
        }

        $transaksi->update([
            'metode_pembayaran' => $request->metode_pembayaran,
            'status'            => 'selesai',
        ]);

        return redirect()->route('petugas.transaksi.struk', $transaksi->id_transaksi)
            ->with('success', 'Pembayaran berhasil!');
    }

    public function struk($id)
    {
        $transaksi = Transaksi::with(['kendaraan.tarif', 'area', 'user'])
            ->findOrFail($id);
        return view('pages.petugas.transaksi.struk', compact('transaksi'));
    }
}
