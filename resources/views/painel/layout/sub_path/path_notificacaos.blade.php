@php
    $notificacaos = App\Models\Notificacao::leftJoin('cliente_notificacaos', 'cliente_notificacaos.notificacao_id', '=', 'notificacaos.id')
                      ->select('notificacaos.*')
                        ->where('notificacaos.status', 'A')
                        ->whereRaw('(now() between notificacaos.data_inicio and notificacaos.data_fim)')
                        ->where(function($query) use ($user)
                        {
                            $query->orWhere('cliente_notificacaos.cliente_id', $user->cliente_user->cliente->id);
                            $query->orWhere('notificacaos.todos', 'S');
                        })
                        ->orderBy('notificacaos.data_fim', 'desc')
                        ->take(4)
                        ->get();
@endphp

@if($notificacaos && $notificacaos->count() > 0)
<div class="dropdown d-inline-block">
     <button type="button" class="btn header-item noti-icon waves-effect"
       id="page-header-notificacaos-dropdown" data-toggle="dropdown" aria-expanded="false">
         <i class="ri-notification-3-line"></i>
         <span class="noti-dot"></span>
     </button>
     <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-0"
            aria-labelledby="page-header-notificacaos-dropdown" style="">
         <div class="p-3" style="background: #f1f6f57a;padding: 12px 10px !important;">
             <div class="row align-items-center">
                 <div class="col">
                     <h6 class="m-0" style="font-weight: 600;"> Painel de notificações </h6>
                 </div>
                 <div class="col-auto">
                     <a href="{{route('painel.notificacao')}}" class="small"> Ver Todas</a>
                 </div>
             </div>
         </div>
         <div data-simplebar="init" style="max-height: 230px;min-height: 230px;padding-top: 10px;">
         <div class="simplebar-wrapper" style="margin: 0px;">
            <div class="simplebar-height-auto-observer-wrapper">
                <div class="simplebar-height-auto-observer"></div>
            </div>
            <div class="simplebar-mask notificacoes-lives">
                <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                <div class="simplebar-content-wrapper" style="height: auto;overflow-y: scroll;padding-top: 10px !important;">
                <div class="simplebar-content" style="padding: 0px;">

                @foreach($notificacaos as $notificacao)
				 <!-- <a href="" class="text-reset notification-item"> -->
					 <div class="d-block">
						 <div class="flex-1" style="margin: 0 10px 10px; padding-bottom: 10px; border-bottom: 1px solid #5f9c8c3d;">
                            <h6 class="mb-1">
                                <a href="{{ route('painel.notificacao')}}">
                                    {{$notificacao->nome_reduzido}}
                                </a>
                            </h6>

                            <div class="font-size-12 text-muted">
                                    <p class="mb-1">{!!$notificacao->resumo!!}</p>
                                    <p class="mb-0" style="background: #5f9c8c17; padding: 3px 10px; border-radius: 6px;"><i class="mdi mdi-clock-outline"></i> {{$notificacao->tempo_inicio}}
                                    <span style="float:right;text-size:6px;">término {{$notificacao->termina_em}}</span>
                                    </p>
                            </div>
						 </div>
					 </div>
				 <!-- </a>				 -->
                @endforeach

                </div>
                </div>
                </div>
            </div>
        <div class="simplebar-placeholder" style="width: 0px; height: 0px;"></div></div><div class="simplebar-track simplebar-horizontal" style="visibility: hidden;"><div class="simplebar-scrollbar" style="transform: translate3d(0px, 0px, 0px); display: none;"></div></div><div class="simplebar-track simplebar-vertical" style="visibility: hidden;"><div class="simplebar-scrollbar" style="transform: translate3d(0px, 0px, 0px); display: none; height: 155px;"></div></div></div>

     </div>
 </div>
@endif
