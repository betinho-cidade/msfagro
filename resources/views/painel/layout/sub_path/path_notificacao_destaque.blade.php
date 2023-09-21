@php
    $notificacao_destaque = App\Models\Notificacao::leftJoin('cliente_notificacaos', 'cliente_notificacaos.notificacao_id', '=', 'notificacaos.id')
                                ->select('notificacaos.*')
                                    ->where('notificacaos.status', 'A')
                                    ->where('notificacaos.destaque', 'S')
                                    ->whereRaw('(now() between notificacaos.data_inicio and notificacaos.data_fim)')
                                    ->where(function($query) use ($user)
                                    {
                                        $query->orWhere('cliente_notificacaos.cliente_id', $user->cliente->id);
                                        $query->orWhere('notificacaos.todos', 'S');
                                    })
                                    ->orderBy('notificacaos.data_fim', 'asc')
                                    ->first();
@endphp

@if($notificacao_destaque)
    @if($notificacao_destaque->url_notificacao)
        <a href="javascript:goUrl('{{ $notificacao_destaque->url_notificacao }}');">
            {{$notificacao_destaque->nome_reduzido}}
        </a>
    @else
        {{$notificacao_destaque->nome_reduzido}}
    @endif
@endif
