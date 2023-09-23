           <header id="page-topbar">
                <div class="navbar-header">
                    <div class="d-flex">
                        <!-- LOGO -->
                        <div class="navbar-brand-box" style="text-align: center;padding:0;">
                            <a href="{{route('painel')}}" class="logo logo-dark">
                                <span class="logo-sm" style="color: #fff;">
                                    <img style="max-width: 50px;" src="{{asset('nazox/assets/images/logo-sm-light.png')}}" alt="" height="">
                                </span>
                                <span class="logo-lg">
                                    <img style="max-width: 120px;" src="{{asset('nazox/assets/images/logo-dark.png')}}" alt="" height="">
                                </span>
                            </a>

                            <a href="{{route('painel')}}" class="logo logo-light">
                                <span class="logo-sm" style="color: #fff;">
                                    <img style="max-width: 50px;" src="{{asset('nazox/assets/images/logo-sm-light.png')}}" alt="" height="">
                                </span>
                                <span class="logo-lg">
                                    <img style="max-width: 120px;" src="{{asset('nazox/assets/images/logo-light.png')}}" alt="" height="">
                                </span>
                            </a>
                        </div>

                        <form id="menu_abertura" action="" method="post">
                        @csrf
                        <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect" id="vertical-menu-btn" onclick="menu_aberto();">
                            <i class="ri-menu-2-line align-middle"></i>
                        </button>
                        </form>

                    </div> 

                    @if($user->roles->contains('name', 'Cliente') && $user->cliente)
                        <div style="text-align: center;">
                            <span class="tag-cliente">{{ $user->cliente->nome ?? '...'}}</span>
                            
                            <!-- Notificação Destaque - INI -->
                            @includeif('painel.layout.sub_path.path_notificacao_destaque')
                            <!-- Notificação Destaque - FIM -->
                        </div>
                    @endif
                    <div class="d-flex">

                        <!-- Notificações do Site - INI -->
                        @if($user && $user->cliente)
                            @includeif('painel.layout.sub_path.path_notificacaos')    
                        @endif
                        <!-- Notificações do Site - FIM -->

                        <div class="dropdown d-inline-block user-dropdown">
                            <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img class="rounded-circle header-profile-user" src="{{($user->membro) ? $user->membro->imagem : asset('images/avatar/avatar.png')}}"
                                    alt="Header Avatar">
                                <span class="d-none d-xl-inline-block ml-1">{{$user->name}}</span>
                                <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <!-- item-->
                                <a class="dropdown-item" href="{{ route('usuario_logado.show', compact('user')) }}"><i class="ri-user-line align-middle mr-1"></i> Meus Dados</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="{{route('logout')}}"><i class="ri-shut-down-line align-middle mr-1 text-danger"></i> Sair</a>
                            </div>
                        </div>

                        @isset($search_tools)
                        <div class="dropdown d-inline-block">
                            <button type="button" class="btn header-item noti-icon right-bar-toggle waves-effect">
                                <i class="ri-settings-2-line"></i>
                            </button>
                        </div>
                        @endisset

                    </div>
                </div>
            </header>

