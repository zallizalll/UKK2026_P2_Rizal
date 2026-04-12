<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;

class LogAktivitasController extends Controller
{
    public function index()
    {
        $logs = LogAktivitas::with('user')
            ->orderBy('waktu_aktivitas', 'desc')
            ->paginate(20);

        return view('pages.log.index', compact('logs'));
    }

    public function print()
    {
        $logs = LogAktivitas::with('user')
            ->orderBy('waktu_aktivitas', 'desc')
            ->get();

        return view('pages.log.print', compact('logs'));
    }
}
