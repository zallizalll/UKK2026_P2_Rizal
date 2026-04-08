<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Cards
        $totalKendaraan = DB::table('kendaraan')->count();
        $totalTarif     = DB::table('tarif')->count();
        $totalArea      = DB::table('area_parkir')->count();
        $totalUser      = User::count();

        // Grafik — pakai waktu_masuk bukan created_at
        $grafikData = DB::table('transaksi')
            ->selectRaw('MONTH(waktu_masuk) as bulan, COUNT(*) as total')
            ->whereYear('waktu_masuk', date('Y'))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $labelBulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $dataGrafik = array_fill(0, 12, 0);
        foreach ($grafikData as $item) {
            $dataGrafik[$item->bulan - 1] = $item->total;
        }

        // Tabel transaksi terbaru — pakai waktu_masuk
        $transaksiTerbaru = DB::table('transaksi')
            ->orderBy('waktu_masuk', 'desc')
            ->limit(10)
            ->get();

        // Data kendaraan parkir
        $kendaraanParkir = DB::table('kendaraan')
            ->orderBy('id_kendaraan', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard.admin', compact(
            'totalKendaraan',
            'totalTarif',
            'totalArea',
            'totalUser',
            'labelBulan',
            'dataGrafik',
            'transaksiTerbaru',
            'kendaraanParkir'
        ));
    }
}
