<?php

namespace App\Listeners;

use App\Notifications\AuditTelegramNotification;
use Illuminate\Support\Facades\Notification;
use OwenIt\Auditing\Events\Audited;

class SendTelegramAuditNotification {

    public function handle(Audited $event): void {
        $chatId = env('TELEGRAM_CHAT_ID');

        Notification::route('telegram', $chatId)
                ->notify(new AuditTelegramNotification($event->audit));
    }
}