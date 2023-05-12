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
                                    <i class="ri-store-2-line"></i>
                                    <span>Gestão</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                                </ul>
                            </li>

                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="ri-store-2-line"></i>
                                    <span>Cadastros Base</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{ route('usuario.index') }}">Usuários</a></li>
                                    <li><a href="{{ route('aliquota.index') }}">Alíquotas</a></li>
                                    <li><a href="{{ route('categoria.index') }}">Categorias</a></li>
                                    <li><a href="{{ route('cliente.index') }}">Clientes</a></li>
                                </ul>
                            </li>
                            <!-- Menus Relacioandos a administração - Acesso somente para GESTOR - FIM-->
                            @endif

                            @if($user->roles->contains('name', 'Cliente') && $user->cliente)
                            <!-- Menus Relacioandos ao cliente - Acesso somente para CLIENTE - INICIO-->

                            <li class="menu-title">GESTÃO DO CLIENTE</li>

                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="ri-store-2-line"></i>
                                    <span>Cadastros</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{ route('forma_pagamento.index') }}">Formas de Pagamento</a></li>
                                    <li><a href="{{ route('fazenda.index') }}">Fazendas</a></li>
                                    <li><a href="{{ route('empresa.index') }}">Empresas</a></li>
                                    <li><a href="{{ route('produtor.index') }}">Produtores</a></li>
                                </ul>
                            </li>

                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="ri-store-2-line"></i>
                                    <span>Lançamentos</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{ route('lancamento.index') }}">Efetivo / Movimento Fiscal</a></li>
                                </ul>
                            </li>

                            <!-- Menus Relacioandos ao cliente - Acesso somente para CLIENTE - FIM-->
                            @endif

                        </ul>

                    </div>
                    <!-- Sidebar -->
                </div>
            </div>
            <!-- Left Sidebar End -->
