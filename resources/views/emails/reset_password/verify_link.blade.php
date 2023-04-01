@component('mail::message')
# Solicitação Automática para Troca de Senha

Olá...

Você solicitou uma troca de senha para sua conta.

## Criar nova senha:

@component('mail::button', ['url' => route('reset.password', ['token' => $token])])
    Resetar a Senha
@endcomponent

Obrigado,<br>
{{ config('app.name') }}
@endcomponent
