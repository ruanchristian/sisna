<?php

namespace App\Listeners;

use App\Models\SelectiveProcess;
use App\Models\User;
use App\Notifications\AuditTelegramNotification;
use Illuminate\Support\Facades\Notification;
use OwenIt\Auditing\Events\Audited;

class SendTelegramAuditNotification {

    public function handle(Audited $event): void {
        $audit = $event->audit;

        $user = new User;
        Notification::send($user, new AuditTelegramNotification($audit));
    }
}