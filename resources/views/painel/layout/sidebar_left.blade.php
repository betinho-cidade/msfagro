            <!-- ========== Left Sidebar Start ========== -->
            <div class="vertical-menu">

                <div data-simplebar class="h-100">

                    <!--- Sidemenu -->
                    <div id="sidebar-menu">
                        <!-- Left Menu Start -->

                        <ul class="metismenu list-unstyled" id="side-menu">


                            @if($user->roles->contains('name', 'Gestor'))
                            <!-- Menus Relacioandos a administração - Acesso somente para GESTOR - INICIO-->

                            <li class="menu-title">GESTÃO ORGANIZACIONAL</li>

                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="ri-bar-chart-2-line"></i>
                                    <span>Gestão</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                                    <li><a href="{{ route('relatorio_gestao.index') }}">Relatório</a></i>
                                </a>
                            </li>   

                                </ul>
                            </li>

                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="ri-database-line"></i>
                                    <span>Cadastros Base</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{ route('usuario.index') }}">Usuários</a></li>
                                    <li><a href="{{ route('aliquota.index') }}">Alíquotas</a></li>
                                    <li><a href="{{ route('categoria.index') }}">Categorias</a></li>
                                    <li><a href="{{ route('cliente.index') }}">Clientes</a></li>
                                    <li><a href="{{ route('notificacao.index') }}">Notificações</a></li>
                                </ul>
                            </li>

                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="ri-map-pin-line"></i>
                                    <span>Google Maps</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{ route('googlemap.show', ['googlemap' => 1]) }}">Parametrização</a></li>
                                    <li><a href="{{ route('googlemap.index') }}">Resumo Mensal</a></li>
                                </ul>
                            </li>

                            <!-- Menus Relacioandos a administração - Acesso somente para GESTOR - FIM-->
                            @endif

                            @if($user->roles->contains('name', 'Cliente'))
                            <!-- Menus Relacioandos ao cliente - Acesso somente para CLIENTE - INICIO-->

                            <li class="menu-title">GESTÃO DO CLIENTE</li>

                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="ri-database-line"></i>
                                    <span>Cadastros</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    @can('view_usuario_cliente')
                                        <li><a href="{{ route('usuario_cliente.index') }}">Usuários</a></li>
                                    @endcan                                    
                                    @can('view_produtor')
                                        <li><a href="{{ route('produtor.index') }}">Produtores</a></li>
                                    @endcan
                                    @can('view_forma_pagamento')
                                        <li><a href="{{ route('forma_pagamento.index') }}">Formas de Pagamento</a></li>
                                    @endcan
                                    @can('view_fazenda')
                                        <li><a href="{{ route('fazenda.index') }}">Fazendas</a></li>
                                    @endcan
                                    @can('view_empresa')
                                        <li><a href="{{ route('empresa.index') }}">Empresas</a></li>
                                    @endcan
                                </ul>
                            </li>

                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="mdi mdi-database-plus"></i>
                                    <span>Lançamentos</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    @can('view_lancamento')
                                        @if($user->cliente_user->cliente->tipo == 'AG')
                                            <li><a href="{{ route('lancamento.index', ['aba' => 'MF']) }}">Movimento Financeiro</a></li>
                                        @else
                                            <li><a href="{{ route('lancamento.index', ['aba' => 'EP']) }}">Efetivo Pecuário</a></li>
                                            <li><a href="{{ route('lancamento.index', ['aba' => 'MF']) }}">Movimento Financeiro</a></li>
                                        @endif
                                    @endcan
                                </ul>
                            </li>

                            @can('view_financeiro')
                                <li><a href="{{ route('financeiro.index') }}"><i class="fas fa-money-bill"></i><span> Financeiro</span></a></li>
                            @endcan
                            @can('view_lucro')
                                <li><a href="{{ route('lucro.index') }}"><i class="fas fa-money-bill"></i><span> Distribuição Lucros</span></a></li>
                            @endcan
                            @can('view_relatorio')
                                <li><a href="{{ route('relatorio.index') }}"><i class="far fa-chart-bar"></i><span>Relatório</span></a></li>   
                            @endcan
                            @can('view_notificacao')
                                <li><a href="{{ route('painel.notificacao') }}"><i class="ri-notification-3-line"></i><span>Notificações</span></a></li>   
                            @endcan
                            @can('view_relatorio_maps')
                                <li><a href="{{ route('relatorio.geomaps') }}"><i class="fas fa-map-marker-alt"></i><span>Maps</span></a></li>   
                            @endcan
                            <li style="background: #30374a;"><a href="https://api.whatsapp.com/send/?phone=5571997081850&type=phone_number&app_absent=0" target="_blank"><i class="fab fa-whatsapp"></i><span>Suporte</span></a></li>                               

                            <!-- Menus Relacioandos ao cliente - Acesso somente para CLIENTE - FIM-->
                            @endif

                        </ul>

                    </div>
                    <!-- Sidebar -->
                </div>
            </div>
            <!-- Left Sidebar End -->
