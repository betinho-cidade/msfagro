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

                            @if($user->roles->contains('name', 'Cliente') && $user->cliente)
                            <!-- Menus Relacioandos ao cliente - Acesso somente para CLIENTE - INICIO-->

                            <li class="menu-title">GESTÃO DO CLIENTE</li>

                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="ri-database-line"></i>
                                    <span>Cadastros</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{ route('produtor.index') }}">Produtores</a></li>
                                    <li><a href="{{ route('forma_pagamento.index') }}">Formas de Pagamento</a></li>
                                    @if($user->cliente->tipo !== 'AG')                                                              
                                        <li><a href="{{ route('fazenda.index') }}">Fazendas</a></li>
                                    @endif
                                    <li><a href="{{ route('empresa.index') }}">Empresas</a></li>
                                </ul>
                            </li>

                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="mdi mdi-database-plus"></i>
                                    <span>Lançamentos</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    @if($user->cliente->tipo == 'AG')
                                        <li><a href="{{ route('lancamento.index', ['aba' => 'MF']) }}">Movimento Financeiro</a></li>
                                    @else
                                        <li><a href="{{ route('lancamento.index', ['aba' => 'EP']) }}">Efetivo Pecuário</a></li>
                                        <li><a href="{{ route('lancamento.index', ['aba' => 'MF']) }}">Movimento Financeiro</a></li>
                                    @endif
                                </ul>
                            </li>

                            <li>
                                <a href="{{ route('financeiro.index') }}">
                                    <i class="fas fa-money-bill"></i>
                                    <span>Financeiro</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('relatorio.index') }}">
                                    <i class="far fa-chart-bar"></i>
                                    <span>Relatório</span>
                                </a>
                            </li>   
                            
                            @if($user->cliente->tipo !== 'AG')                                    
                            <li>
                                <a href="{{ route('relatorio.geomaps') }}">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>Maps</span>
                                </a>
                            </li>   
                            @endif

                            <!-- Menus Relacioandos ao cliente - Acesso somente para CLIENTE - FIM-->
                            @endif

                        </ul>

                    </div>
                    <!-- Sidebar -->
                </div>
            </div>
            <!-- Left Sidebar End -->
