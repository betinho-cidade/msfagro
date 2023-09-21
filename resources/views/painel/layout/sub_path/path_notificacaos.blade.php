@php
    $notificacaos = App\Models\Notificacao::leftJoin('cliente_notificacaos', 'cliente_notificacaos.notificacao_id', '=', 'notificacaos.id')
                      ->select('notificacaos.*')
                        ->where('notificacaos.status', 'A')
                        ->whereRaw('(now() between notificacaos.data_inicio and notificacaos.data_fim)')
                        ->where(function($query) use ($user)
                        {
                            $query->orWhere('cliente_notificacaos.cliente_id', $user->cliente->id);
                            $query->orWhere('notificacaos.todos', 'S');
                        })
                        ->orderBy('notificacaos.data_fim', 'asc')
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
         <div class="p-3">
             <div class="row align-items-center">
                 <div class="col">
                     <h6 class="m-0"> Notificações registradas... </h6>
                 </div>
                 <div class="col-auto">
                     <a href="{{route('notificacao.index')}}" class="small"> Ver Todas</a>
                 </div>
             </div>
         </div>
         <div data-simplebar="init" style="max-height: 230px;">
         <div class="simplebar-wrapper" style="margin: 0px;">
            <div class="simplebar-height-auto-observer-wrapper">
                <div class="simplebar-height-auto-observer"></div>
            </div>
            <div class="simplebar-mask">
                <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                <div class="simplebar-content-wrapper" style="height: auto; overflow: hidden;">
                <div class="simplebar-content" style="padding: 0px;">

                @foreach($notificacaos as $notificacao)
				 <a href="" class="text-reset notification-item">
					 <div class="d-flex">
						 <div class="avatar-xs me-3">
							 <span class="avatar-title bg-success rounded-circle font-size-16">
								 <i class="ri-checkbox-circle-line"></i>
							 </span>
						 </div>
						 <div class="flex-1">
                            @if($notificacao->url_notificacao)
                                <h6 class="mb-1">
                                    <a href="javascript:goUrl('{{ $notificacao->url_notificacao }}');">
                                        {{$notificacao->nome_reduzido}}
                                    </a>
                                </h6>
                            @else
                                <h6 class="mb-1">{{$notificacao->nome_reduzido}}</h6>
                            @endif
                            <div class="font-size-12 text-muted">
                                    <p class="mb-1">{{$notificacao->resumo_reduzido}}</p>
                                    <p class="mb-0"><i class="mdi mdi-clock-outline"></i> {{$notificacao->tempo_inicio}}
                                    <span style="float:right;text-size:6px;">término {{$notificacao->termina_em}}&nbsp;</span>
                                    </p>
                            </div>  
						 </div>
					 </div>
				 </a>				
                @endforeach

                </div>
                </div>
                </div>
            </div>
        <div class="simplebar-placeholder" style="width: 0px; height: 0px;"></div></div><div class="simplebar-track simplebar-horizontal" style="visibility: hidden;"><div class="simplebar-scrollbar" style="transform: translate3d(0px, 0px, 0px); display: none;"></div></div><div class="simplebar-track simplebar-vertical" style="visibility: hidden;"><div class="simplebar-scrollbar" style="transform: translate3d(0px, 0px, 0px); display: none; height: 155px;"></div></div></div>
         <div class="p-2 border-top">
             <div class="d-grid">
                 <a class="btn btn-sm btn-link font-size-14 text-center" href="{{route('notificacao.index')}}">
                     <i class="mdi mdi-arrow-right-circle me-1"></i> Ver Todas..
                 </a>
             </div>
         </div>
     </div>
 </div>
@endif
