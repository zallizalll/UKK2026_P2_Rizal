<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\Tarif;
use App\Models\Transaksi;
use App\Models\AreaParkir;
use App\Traits\LogAktivitasTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class KendaraanController extends Controller
{
    use LogAktivitasTrait;

    public function index()
    {
        $kendaraans = Kendaraan::with(['tarif', 'user', 'transaksis.area'])
            ->latest('Created_at')->get();
        $tarifs = Tarif::orderBy('jenis_kendaraan')->get();
        $areas  = AreaParkir::where('terisi', '<', \DB::raw('kapasitas'))
            ->orderBy('nama_area')->get();
        return view('pages.petugas.kendaraan.index', compact('kendaraans', 'tarifs', 'areas'));
    }

    public function masuk(Request $request)
    {
        $request->validate([
            'plat_nomor' => 'required|string|max:255|unique:kendaraan,plat_nomor',
            'warna'      => 'required|string|max:255',
            'id_Tarif'   => 'required|exists:tarif,id_tarif',
            'id_area'    => 'required|exists:area_parkir,id_area',
        ]);

        // Cek area masih ada slot
        $area = AreaParkir::findOrFail($request->id_area);
        if ($area->terisi >= $area->kapasitas) {
            return redirect()->route('petugas.kendaraan')
                ->with('error', 'Area ' . $area->nama_area . ' sudah penuh!');
        }

        // Buat kendaraan
        $kendaraan = Kendaraan::create([
            'plat_nomor' => strtoupper($request->plat_nomor),
            'warna'      => $request->warna,
            'status'     => 'masuk',
            'id_Tarif'   => $request->id_Tarif,
            'id_user'    => Auth::id(),
        ]);

        // Buat transaksi otomatis
        Transaksi::create([
            'id_kendaraan' => $kendaraan->id_kendaraan,
            'id_tarif'     => $request->id_Tarif,
            'id_area'      => $request->id_area,
            'id_user'      => Auth::id(),
            'waktu_masuk'  => Carbon::now(),
            'status'       => 'aktif',
        ]);

        // Update slot area +1
        $area->increment('terisi');

        $this->log('Kendaraan masuk: ' . strtoupper($request->plat_nomor) . ' (' . $request->warna . ') — area: ' . $area->nama_area);

        return redirect()->route('petugas.kendaraan')
            ->with('success', 'Kendaraan ' . strtoupper($request->plat_nomor) . ' berhasil dicatat masuk!');
    }

    public function keluar($id)
    {
        $kendaraan = Kendaraan::findOrFail($id);

        if ($kendaraan->status === 'keluar') {
            return redirect()->route('petugas.kendaraan')
                ->with('error', 'Kendaraan sudah tercatat keluar!');
        }

        $transaksi = Transaksi::where('id_kendaraan', $id)
            ->where('status', 'aktif')->first();

        if ($transaksi) {
            $masuk       = Carbon::parse($transaksi->waktu_masuk);
            $keluar      = Carbon::now();
            $totalMenit  = $masuk->diffInMinutes($keluar);
            $durasiJam   = max(1, (int) ceil($totalMenit / 60));
            $durasiMenit = $totalMenit % 60;
            $biayaTotal  = $durasiJam * $transaksi->tarif->tarif_per_jam;

            // Status jadi PENDING dulu, belum selesai
            $transaksi->update([
                'waktu_keluar' => $keluar,
                'durasi_jam'   => $durasiJam,
                'durasi_menit' => $durasiMenit,
                'durasi'       => $durasiJam . ' jam ' . $durasiMenit . ' menit',
                'biaya_total'  => $biayaTotal,
                'status'       => 'pending', // ← pending, bukan selesai
            ]);

            $area = $transaksi->area;
            if ($area && $area->terisi > 0) $area->decrement('terisi');
        }

        $kendaraan->update(['status' => 'keluar']);

        $this->log('Kendaraan keluar: ' . $kendaraan->plat_nomor . ' — menunggu pembayaran');

        // Redirect ke halaman pembayaran (bukan struk)
        return redirect()->route('petugas.transaksi.bayar', $transaksi->id_transaksi);
    }
}
