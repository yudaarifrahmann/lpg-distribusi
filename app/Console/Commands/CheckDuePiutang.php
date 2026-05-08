<?php

namespace App\Console\Commands;

use App\Models\Piutang;
use App\Models\User;
use App\Notifications\PiutangJatuhTempoNotification;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CheckDuePiutang extends Command
{
    protected $signature = 'piutang:check-due';
    protected $description = 'Check for due piutang and notify admins';

    public function handle()
    {
        $today = Carbon::today();
        $duePiutangs = Piutang::where('status_piutang', '!=', 'lunas')
            ->whereDate('tanggal_jatuh_tempo', '<=', $today)
            ->get();

        $admins = User::role(['superadmin', 'admin_keuangan'])->get();

        foreach ($duePiutangs as $piutang) {
            foreach ($admins as $admin) {
                $admin->notify(new PiutangJatuhTempoNotification($piutang));
            }
        }

        $this->info('Checked ' . $duePiutangs->count() . ' due piutangs.');
    }
}
