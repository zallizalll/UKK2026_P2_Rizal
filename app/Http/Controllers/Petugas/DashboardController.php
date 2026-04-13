<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\AreaParkir;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $user  = Auth::user();

        // Cards
        $kendaraanMasukHariIni  = Transaksi::whereDate('waktu_masuk', $today)->count();
        $kendaraanKeluarHariIni = Transaksi::whereDate('waktu_keluar', $today)->count();
        $transaksiAktif         = Transaksi::where('status', 'aktif')->count();
        $pendapatanHariIni      = Transaksi::where('status', 'selesai')
            ->whereDate('waktu_keluar', $today)
            ->sum('biaya_total');

        // Area parkir
        $areas = AreaParkir::orderBy('nama_area')->get();

        // Kendaraan sedang parkir (transaksi aktif)
        $transaksiAktifList = Transaksi::with(['kendaraan.tarif', 'area'])
            ->where('status', 'aktif')
            ->latest('waktu_masuk')
            ->take(10)
            ->get();

        // Transaksi terbaru hari ini
        $transaksiTerbaru = Transaksi::with(['kendaraan', 'area'])
            ->whereDate('waktu_masuk', $today)
            ->latest('waktu_masuk')
            ->take(10)
            ->get();

        // Grafik transaksi per bulan tahun ini
        $labelBulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
        $dataGrafik = [];
        for ($i = 1; $i <= 12; $i++) {
            $dataGrafik[] = Transaksi::whereYear('waktu_masuk', date('Y'))
                ->whereMonth('waktu_masuk', $i)
                ->count();
        }

        return view('dashboard.petugas', compact(
            'kendaraanMasukHariIni',
            'kendaraanKeluarHariIni',
            'transaksiAktif',
            'pendapatanHariIni',
            'areas',
            'transaksiAktifList',
            'transaksiTerbaru',
            'labelBulan',
            'dataGrafik',
            'user'
        ));
    }
}
