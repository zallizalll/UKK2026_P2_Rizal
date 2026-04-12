<?php

namespace App\Traits;

use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;

trait LogAktivitasTrait
{
    protected function log(string $aktivitas): void
    {
        LogAktivitas::create([
            'id_user'   => Auth::id() ?? null,
            'aktivitas' => $aktivitas,
        ]);
    }
}
