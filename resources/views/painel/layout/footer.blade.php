
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                            MFS Agro <script>document.write(new Date().getFullYear())</script> © Cityinbag.
                            </div>
                            <div class="col-sm-6">
                                <div class="text-sm-right d-none d-sm-block">
                                @if(!Str::startsWith(Request::path(), 'efetivo') && !Str::startsWith(Request::path(), 'movimentacao') && !Str::startsWith(Request::path(), 'financeiro') && !Str::startsWith(Request::path(), 'relatorio'))
                                    <a href="javascript:history.back()" style="color: #656565;">
                                    <i class="nav-icon fas fa-arrow-left" style="font-size: 14px;margin-right: 4px;"></i> Retornar página anterior</a>
                                @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
