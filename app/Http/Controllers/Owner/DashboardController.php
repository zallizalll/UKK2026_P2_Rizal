<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Kendaraan;
use App\Models\AreaParkir;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ===== CARDS =====
        $totalPendapatan      = Transaksi::where('status', 'selesai')->sum('biaya_total');
        $pendapatanHariIni    = Transaksi::where('status', 'selesai')
            ->whereDate('waktu_keluar', today())->sum('biaya_total');
        $pendapatanBulanIni   = Transaksi::where('status', 'selesai')
            ->whereMonth('waktu_keluar', now()->month)
            ->whereYear('waktu_keluar', now()->year)
            ->sum('biaya_total');
        $totalTransaksi       = Transaksi::where('status', 'selesai')->count();
        $transaksiHariIni     = Transaksi::whereDate('waktu_masuk', today())->count();
        $kendaraanParkir      = Transaksi::where('status', 'aktif')->count();
        $transaksiPending     = Transaksi::where('status', 'pending')->count();

        // ===== GRAFIK TRANSAKSI PER BULAN =====
        $labelBulan = [];
        $dataGrafik = [];
        $dataGrafikPendapatan = [];
        for ($i = 11; $i >= 0; $i--) {
            $bulan = Carbon::now()->subMonths($i);
            $labelBulan[] = $bulan->translatedFormat('M Y');
            $dataGrafik[] = Transaksi::where('status', 'selesai')
                ->whereMonth('waktu_keluar', $bulan->month)
                ->whereYear('waktu_keluar', $bulan->year)
                ->count();
            $dataGrafikPendapatan[] = Transaksi::where('status', 'selesai')
                ->whereMonth('waktu_keluar', $bulan->month)
                ->whereYear('waktu_keluar', $bulan->year)
                ->sum('biaya_total');
        }

        // ===== AREA PARKIR =====
        $areas = AreaParkir::orderBy('nama_area')->get();

        // ===== KENDARAAN SEDANG PARKIR =====
        $kendaraanParkirList = Transaksi::with(['kendaraan.tarif', 'area'])
            ->where('status', 'aktif')
            ->latest('waktu_masuk')
            ->get();

        // ===== REKAP KENDARAAN =====
        $rekapKendaraan = Kendaraan::with(['tarif', 'user', 'transaksis'])
            ->latest('Created_at')
            ->get();

        // ===== REKAP TRANSAKSI =====
        $rekapTransaksi = Transaksi::with(['kendaraan.tarif', 'area', 'user'])
            ->latest('waktu_masuk')
            ->get();

        // ===== TRANSAKSI TERBARU HARI INI =====
        $transaksiTerbaru = Transaksi::with(['kendaraan', 'area'])
            ->whereDate('waktu_masuk', today())
            ->latest('waktu_masuk')
            ->take(10)
            ->get();

            return view('dashboard.owner', compact(
            'user',
            'totalPendapatan',
            'pendapatanHariIni',
            'pendapatanBulanIni',
            'totalTransaksi',
            'transaksiHariIni',
            'kendaraanParkir',
            'transaksiPending',
            'labelBulan',
            'dataGrafik',
            'dataGrafikPendapatan',
            'areas',
            'kendaraanParkirList',
            'rekapKendaraan',
            'rekapTransaksi',
            'transaksiTerbaru',
        ));
    }
}
