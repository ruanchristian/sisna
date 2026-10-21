<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;
use OwenIt\Auditing\Contracts\Audit;

class AuditTelegramNotification extends Notification implements ShouldQueue {
    use Queueable;

    public function __construct(public Audit $audit) {}

    public function via(object $notifiable): array {
        return ['telegram'];
    }

    public function toTelegram(object $notifiable): TelegramMessage {
        $label = match ($this->audit->auditable_type) {
            'App\\Models\\User' => 'Usuário',
            'App\\Models\\SelectiveProcess' => 'Processo Seletivo',
            'App\\Models\\Student' => 'Aluno',
            default => 'Registro',
        };

        // tipo do evento
        $type = match ($this->audit->event) {
            'created' => 'criado',
            'updated' => 'atualizado',
            'deleted' => 'excluído',
            default => $this->audit->event,
        };

        $oldValues = json_encode($this->audit->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '{}';
        $newValues = json_encode($this->audit->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '{}';

        $message = sprintf(
            "<b>⚠️ Alerta de auditoria:</b>\n\n".
            "<b>Tipo:</b> %s\n".
            "<b>Modelo:</b> %s\n".
            "<b>Usuário responsável:</b> %s\n".
            "<b>IP:</b> %s\n".
            "<b>URL da requisição:</b> %s\n\n".
            "<b>Valores antigos:</b>\n<pre>%s</pre>\n\n".
            "<b>Valores novos:</b>\n<pre>%s</pre>",
            $label. ' ' . $type,
            $this->audit->auditable_type,
            $this->audit->user->name ?? 'Sistema',
            $this->audit->ip_address ?? '-',
            $this->audit->url ?? '-',
            $oldValues,
            $newValues
        );

        return TelegramMessage::create($message)
            ->options(['parse_mode' => 'HTML']);
    }
}