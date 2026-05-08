<?php

namespace App\Notifications;

use App\Models\Piutang;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PiutangJatuhTempoNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $piutang;

    public function __construct(Piutang $piutang)
    {
        $this->piutang = $piutang;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Piutang Jatuh Tempo',
            'message' => 'Pangkalan ' . $this->piutang->pangkalan->nama_pangkalan . ' memiliki tagihan Rp ' . number_format($this->piutang->sisa_tagihan) . ' yang jatuh tempo pada ' . $this->piutang->tanggal_jatuh_tempo->format('d/m/Y'),
            'piutang_id' => $this->piutang->id,
            'type' => 'warning',
            'url' => route('piutang.show', $this->piutang->id),
        ];
    }
}
