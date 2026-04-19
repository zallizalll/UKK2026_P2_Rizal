<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('shift:rolling')->dailyAt('02:00');
