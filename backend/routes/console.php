<?php

use App\Jobs\ExpirarUsuariosJob;
use Illuminate\Support\Facades\Schedule;

Schedule::job(new ExpirarUsuariosJob())->dailyAt('00:00')->name('expirar-usuarios');
