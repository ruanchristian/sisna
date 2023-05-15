<x-mail::message>
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# @lang('ERRO!')
@else
# @lang('Olá!')
@endif
@endif

<span>Você está recebendo este email porque recebemos uma solicitação de redefinição de senha para sua conta no SISNA.</span>

{{-- Action Button --}}
@isset($actionText)
<?php
    $color = match ($level) {
        'success', 'error' => $level,
        default => 'primary',
    };
?>
<x-mail::button :url="$actionUrl" :color="$color">
Redefinir senha
</x-mail::button>
@endisset

<span>Este link de redefinição de senha irá expirar em 60 minutos.</span>
<span>Se você não solicitou uma redefinição de senha, nenhuma ação é necessária.</span>

Atenciosamente,<br>
Equipe {{ config('app.name') }}

{{-- Subcopy --}}
@isset($actionText)
<x-slot:subcopy>
@lang(
    "Se você estiver com problemas ao clicar no botão \"Redefinir senha\", clique nesse link: ",
    [
        'actionText' => $actionText,
    ]
) <span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
</x-slot:subcopy>
@endisset
</x-mail::message>
