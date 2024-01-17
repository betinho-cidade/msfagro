@extends('painel.layout.index')


@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Informações da Notificação</h4>
        </div>
    </div>
</div>

@if(session()->has('message.level'))
<div class="row">
    <div class="col-12">
        <div class="alert alert-{{ session('message.level') }}">
        {!! session('message.content') !!}
        </div>
    </div>
</div>
@endif

@if ($errors->any())
    <div class="row">
        <div class="col-12">
            <div class="alert alert-danger">
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
        </div>
    </div>
@endif

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
            <!-- FORMULÁRIO - INICIO -->

            <h4 class="card-title">Formulário de Atualização - Notificação</h4>
            <p class="card-title-desc">A Notificação cadastrada estará disponível para os clientes do sistema.</p>

            <form name="edit_notificacao" method="POST" action="{{route('notificacao.update', compact('notificacao'))}}"  class="needs-validation" accept-charset="utf-8" enctype="multipart/form-data" novalidate>
                @csrf
                @method('PUT')
                <div class="bg-soft-primary p-3 rounded" style="margin-bottom:10px;">
                    <h5 class="text-primary font-size-14" style="margin-bottom: 0px;">Dados Notificação</h5>
                </div>

                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="nome">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" value="{{$notificacao->nome}}" placeholder="Informe o nome da Notificação" required>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="form-group">
                            <label for="resumo">Resumo</label>
                            <textarea class="form-control" name="resumo" id="resumo" rows="3" maxlength="500" placeholder="Informe o resumo da Notificação" required>{{$notificacao->resumo}}</textarea>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="data_inicio">Data Início</label>
                            <input type="date" class="form-control" id="data_inicio" name="data_inicio" value="{{$notificacao->data_inicio_ajustada}}" placeholder="Informe a data início da Notificação" required>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="hora_inicio">Hora Início</label>
                            <input type="time" class="form-control" id="hora_inicio" name="hora_inicio" value="{{$notificacao->hora_inicio_ajustada}}" placeholder="Informe a hora início da Notificação" required>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>                    

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="data_fim">Data Fim</label>
                            <input type="date" class="form-control" id="data_fim" name="data_fim" value="{{$notificacao->data_fim_ajustada}}" placeholder="Informe a data final da Notificação" required>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="hora_fim">Hora Fim</label>
                            <input type="time" class="form-control" id="hora_fim" name="hora_fim" value="{{$notificacao->hora_fim_ajustada}}" placeholder="Informe a hora final da Notificação" required>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                </div>
                <p></p>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="url_notificacao">URL <i>*disponível em texto "mais informações"</i></label>
                            <input type="url" class="form-control" id="url_notificacao" name="url_notificacao" value="{{$notificacao->url_notificacao}}" placeholder="Informe a url para download da Notificação">
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="destaque">Destaque ?</label>
                            <select id="destaque" name="destaque" class="form-control" required>
                                <option value="">---</option>
                                <option value="S" {{($notificacao->destaque == 'S') ? 'selected' : '' }}>Sim</option>
                                <option value="N" {{($notificacao->destaque == 'N') ? 'selected' : '' }}>Não</option>
                            </select>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>                      
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="todos">Todos os Clientes ?</label>
                            <select id="todos" name="todos" class="form-control" required>
                                <option value="">---</option>
                                <option value="S" {{($notificacao->todos == 'S') ? 'selected' : '' }}>Sim</option>
                                <option value="N" {{($notificacao->todos == 'N') ? 'selected' : '' }}>Não</option>
                            </select>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>                    
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="situacao">Situação</label>
                            <select id="situacao" name="situacao" class="form-control" required>
                                <option value="">---</option>
                                <option value="A" {{($notificacao->status == 'A') ? 'selected' : '' }}>Ativo</option>
                                <option value="I" {{($notificacao->status == 'I') ? 'selected' : '' }}>Inativo</option>
                            </select>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                </div>

                <button class="btn btn-primary" style="float:right" type="submit">Atualizar Cadastro</button>
            </form>


            <div class="bg-soft-primary p-3 rounded" style="margin-top:60px;margin-bottom:10px;">
                <h5 class="text-primary font-size-14" style="margin-bottom: 0px;">Clientes Vinculados</h5>
            </div>

            <!-- Nav tabs - LISTA AULA/BANNER/AVALIAÇÃO - INI -->
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#clientes" role="tab">
                        <span class="d-block d-sm-none"><i class="ri-checkbox-circle-line"></i></span>
                        <span class="d-none d-sm-block">
                            <i onClick="location.href='{{route('notificacao.cliente_create', compact('notificacao'))}}';" class="fa fa-plus-square" style="color: goldenrod; margin-right:5px;" title="Novo Cliente Vinculado"></i>
                            Clientes ( <code class="highlighter-rouge">{{ $cliente_notificacaos->count() }}</code> )
                        </span>
                    </a>
                </li>
           </ul>
            <!-- Nav tabs - LISTA AULA/BANNER/AVALIAÇÃO - FIM -->      
            
            <!-- Tab panes - INI -->
            <div class="tab-content p-3 text-muted">

                <!-- Nav tabs - LISTA AULA - INI -->
                <div class="tab-pane active" id="clientes" role="tabpanel">
                    <table id="dt_clientes" class="table table-striped table-bordered dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th style="text-align:center;">Ações</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($cliente_notificacaos as $cliente_notificacao)
                                <tr>
                                    <td>{{ $cliente_notificacao->id }}</td>
                                    <td>{{ $cliente_notificacao->cliente->nome }}</td>
                                    <td style="text-align:center;">
                                        @can('delete_notificacao')
                                            <a href="javascript:;" data-toggle="modal"
                                            onclick="deleteData('{{$notificacao->id}}', '{{$cliente_notificacao->id}}');"
                                                data-target="#modal-delete"><i class="fa fa-minus-circle"
                                                    style="color: crimson" title="Excluir o Cliente Vinculado"></i></a>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">Nenhum registro encontrado</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <!-- Nav tabs - LISTA AULA - FIM -->

                    @section('modal_target')"formSubmit();"@endsection
                    @section('modal_type')@endsection
                    @section('modal_name')"modal-delete"@endsection
                    @section('modal_msg_title')Deseja excluir o registro ? @endsection
                    @section('modal_msg_description')O registro selecionado será excluído
                    definitivamente, BEM COMO TODOS seus relacionamentos. @endsection
                    @section('modal_close')Fechar @endsection
                    @section('modal_save')Excluir @endsection

                    <form action="" id="deleteForm" method="post">
                        @csrf
                        @method('DELETE')
                    </form>

                </div>            

            </div>
            <!-- FORMULÁRIO - FIM -->
            </div>
        </div>
    </div>
</div>

@endsection


@section('script-js')
    <script src="{{asset('nazox/assets/js/pages/form-validation.init.js')}}"></script>
    <script src="{{asset('nazox/assets/libs/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
    <script src="{{asset('nazox/assets/js/pages/form-element.init.js')}}"></script>

    <!-- Required datatable js -->
    <script src="{{ asset('nazox/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('nazox/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Responsive examples -->
    <script src="{{ asset('nazox/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('nazox/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
    <!-- Datatable init js -->
    <script src="{{ asset('nazox/assets/js/pages/datatables.init.js') }}"></script>    

    <script src="{{asset('nazox/assets/libs/magnific-popup/jquery.magnific-popup.min.js')}}"></script>
    <script src="{{asset('nazox/assets/js/pages/lightbox.init.js')}}"></script>

    @if ($cliente_notificacaos->count() > 0)
        <script>
            var table = $('#dt_clientes').DataTable({
                language: {
                    url: '{{ asset('nazox/assets/localisation/pt_br.json') }}'
                },
                "order": [
                    [1, "asc"]
                ]
            });
        </script>
    @endif    

    <script>

        function formSubmit() {
            $("#deleteForm").submit();
        }

        function deleteData(notificacao, cliente_notificacao) {
            var notificacao = notificacao;
            var cliente_notificacao = cliente_notificacao;

            var url = '{{ route('notificacao.cliente_destroy', [':notificacao', ':cliente_notificacao']) }}';
            url = url.replace(':notificacao', notificacao);
            url = url.replace(':cliente_notificacao', cliente_notificacao);
            $("#deleteForm").attr('action', url);
        }

    </script>    

@endsection

@section('head-css')
    <link href="{{asset('nazox/assets/libs/magnific-popup/magnific-popup.css')}}" rel="stylesheet" type="text/css" />

    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('plugins/summernote-0.8.18-dist/summernote.min.css') }}">
@endsection

