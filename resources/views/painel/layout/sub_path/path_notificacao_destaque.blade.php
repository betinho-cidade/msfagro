@php
    $notificacao_destaque = App\Models\Notificacao::leftJoin('cliente_notificacaos', 'cliente_notificacaos.notificacao_id', '=', 'notificacaos.id')
                                ->select('notificacaos.*')
                                    ->where('notificacaos.status', 'A')
                                    ->where('notificacaos.destaque', 'S')
                                    ->whereRaw('(now() between notificacaos.data_inicio and notificacaos.data_fim)')
                                    ->where(function($query) use ($user)
                                    {
                                        $query->orWhere('cliente_notificacaos.cliente_id', $user->cliente_user->cliente->id);
                                        $query->orWhere('notificacaos.todos', 'S');
                                    })
                                    ->orderBy('notificacaos.data_fim', 'asc')
                                    ->first();
@endphp

@can('cliente_notificacao')
@if($notificacao_destaque)
    <a href="{{ route('painel.notificacao') }}" style=" color: #252b3b !important;">
        <div style="margin-bottom: -6px; background: #252b3b38; padding: 0 20px 0 15px; margin-top: 8px; color: #252b3b; border-radius: 5px; font-size: 13px;">
            <div class="spinner-grow text-primary m-1" role="status" style=" color: #252b3b !important;    width: 13px;  height: 13px; "> <span class="sr-only">Loading...</span></div>
            {{$notificacao_destaque->nome_reduzido}}
        </div>
    </a>
@endif
@endcan
