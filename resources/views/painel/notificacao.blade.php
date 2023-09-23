@extends('painel.layout.index')


@section('content')

    <div class="row" style="margin-bottom: 20px;">
        <div class="col-md-12 ">
            <h4>Notificações registradas !!!</h4>
            <hr class="mt-0" />
        </div>

    @if($notificacaos && $notificacaos->count() > 0) 

        @foreach($notificacaos as $notificacao)
        <div class="col-md-6 col-xl-3">
            <div class="card" style="height: 93%;">
                <p style="background: {{($notificacao->ao_vivo) ? '#4e8271' : '#626262'}}; font-size: 12px; text-align: center; padding: 2px 0; margin-bottom: 0; color: #fff;"><i class="mdi mdi-clock-outline"></i> noficação do dia {{$notificacao->data_inicio_reduzida}}</p>
                <div class="card-body" style="padding: 12px;">
                    @if($notificacao->url_notificacao) 
                    <a href="javascript:goUrl('{{ $notificacao->url_notificacao }}');">
                        <h4 class="card-title mt-0">{{ $notificacao->nome }}</h4>
                    </a>
                    @else
                        <h4 class="card-title mt-0">{{ $notificacao->nome }}</h4>   
                    @endif
                    <p class="card-text" style="font-size: 13px; line-height: 16px; max-height: 200px; overflow-y: auto;min-height:80px;">{{ $notificacao->resumo }}</p>
                    @if($notificacao->url_notificacao) 
                        <p style="margin-bottom: 0; text-align: center; background: #5f9c8c2e; font-weight: 900; padding: 4px; font-size: 14px; border-radius: 10px;"><a href="javascript:goUrl('{{ $notificacao->url_notificacao }}');">Vizualizar</a></p>
                    @endif
                </div>
            </div>
        </div>
        @endforeach

        <div class="d-block paginacao-notificacoes">{!! $notificacaos->links() !!}</div>

    @else
        <div class="col-md-6 col-xl-3">
            Nenhuma notificação encontrada
        </div> 
    @endif
    </div>

@endsection
