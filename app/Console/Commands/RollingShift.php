<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RollingShift extends Command
{
    protected $signature = 'shift:rolling';
    protected $description = 'Rolling shift petugas setiap hari (Pagi→Siang→Malam→Pagi)';

    public function handle()
    {
        $affected = DB::table('users')
            ->where('role', 'petugas')
            ->update([
                'shift' => DB::raw('
                    CASE
                        WHEN shift = 1 THEN 2
                        WHEN shift = 2 THEN 3
                        WHEN shift = 3 THEN 1
                        ELSE shift
                    END
                ')
            ]);

        $this->info("Shift berhasil di-rolling! {$affected} petugas diupdate. " . now());
    }
}
