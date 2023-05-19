@extends('painel.layout.index')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Formas de Pagamento do Cliente</h4>
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

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <h4 class="card-title">Listagem das Formas de Pagamento Registradas para o Cliente</h4>
                <p class="card-title-desc"></p>

                <!-- Nav tabs - LISTA forma_pagamento - INI -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#ativa" role="tab">
                            <span class="d-block d-sm-none"><i class="ri-checkbox-circle-line"></i></span>
                            <span class="d-none d-sm-block">Formas de Pagamento Ativas ( <code class="highlighter-rouge">{{$forma_pagamentos_AT->count()}}</code> )
                                @can('create_forma_pagamento')
                                    <i class="fas fa-plus-circle" onclick="location.href='{{route('forma_pagamento.create')}}'" style="color: goldenrod" title="Nova Forma de Pagamento"></i>
                                @endcan
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#inativa" role="tab">
                            <span class="d-block d-sm-none"><i class=" ri-close-circle-line"></i></span>
                            <span class="d-none d-sm-block">Formas de Pagamento Inativas ( <code class="highlighter-rouge">{{$forma_pagamentos_IN->count()}}</code> )</span>
                        </a>
                    </li>
                </ul>
                <!-- Nav tabs - LISTA forma_pagamento - FIM -->

                <!-- Tab panes -->
                <div class="tab-content p-3 text-muted">

                <!-- Nav tabs - LISTA forma_pagamento - ATIVA - INI -->
                <div class="tab-pane active" id="ativa" role="tabpanel">
                    <table id="dt_forma_pagamentos_AT" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tipo Conta</th>
                            <th>Produtor/Documento</th>
                            <th style="text-align:center;">Ações</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($forma_pagamentos_AT as $forma_pagamento)
                        <tr>
                            <td>{{$forma_pagamento->id}}</td>
                            <td>{{$forma_pagamento->tipo_conta_texto}}</td>
                            <td data-toggle="tooltip" title="{{ $forma_pagamento->nome_produtor_full }}">{{$forma_pagamento->nome_produtor}}</td>
                            <td style="text-align:center;">

                            @can('edit_forma_pagamento')
                                <a href="{{route('forma_pagamento.show', compact('forma_pagamento'))}}"><i class="fa fa-edit" style="color: goldenrod" title="Editar a Forma de Pagamento"></i></a>
                            @endcan

                            @can('delete_forma_pagamento')
                                <a href="javascript:;" data-toggle="modal" onclick="deleteData({{$forma_pagamento->id}})"
                                    data-target="#modal-delete-forma_pagamento"><i class="fa fa-minus-circle" style="color: crimson" title="Excluir a Forma de Pagamento"></i></a>
                                    <form action="" id="deleteForm" method="post">
                                    @csrf
                                    @method('DELETE')
                                    </form>
                                    @section('modal_target')"formSubmit();"@endsection
                                    @section('modal_type')@endsection
                                    @section('modal_name')"modal-delete-forma_pagamento"@endsection
                                    @section('modal_msg_title')Deseja excluir o registro ? @endsection
                                    @section('modal_msg_description')O registro selecionado será excluído definitivamente, BEM COMO TODOS seus relacionamentos. @endsection
                                    @section('modal_close')Fechar @endsection
                                    @section('modal_save')Excluir @endsection
                            @endcan
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4">Nenhum registro encontrado</td>
                        </tr>
                        @endforelse
                        </tbody>
                    </table>
                    <!-- Nav tabs - LISTA forma_pagamento - ATIVA - FIM -->
                </div>


                <!-- Nav tabs - LISTA forma_pagamento - INATIVA - INI -->
                <div class="tab-pane" id="inativa" role="tabpanel">
                    <table id="dt_forma_pagamentos_IN" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tipo Conta</th>
                            <th>Produtor/Documento</th>
                            <th style="text-align:center;">Ações</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($forma_pagamentos_IN as $forma_pagamento)
                        <tr>
                            <td>{{$forma_pagamento->id}}</td>
                            <td>{{$forma_pagamento->tipo_conta_texto}}</td>
                            <td>{{$forma_pagamento->nome_produtor}}</td>
                            <td style="text-align:center;">

                            @can('edit_forma_pagamento')
                                <a href="{{route('forma_pagamento.show', compact('forma_pagamento'))}}"><i class="fa fa-edit" style="color: goldenrod" title="Editar a Forma de Pagamento"></i></a>
                            @endcan

                            @can('delete_forma_pagamento')
                                <a href="javascript:;" data-toggle="modal" onclick="deleteData({{$forma_pagamento->id}})"
                                    data-target="#modal-delete-forma_pagamento"><i class="fa fa-minus-circle" style="color: crimson" title="Excluir a Forma de Pagamento"></i></a>
                                    <form action="" id="deleteForm" method="post">
                                    @csrf
                                    @method('DELETE')
                                    </form>
                                    @section('modal_target')"formSubmit();"@endsection
                                    @section('modal_type')@endsection
                                    @section('modal_name')"modal-delete-forma_pagamento"@endsection
                                    @section('modal_msg_title')Deseja excluir o registro ? @endsection
                                    @section('modal_msg_description')O registro selecionado será excluído definitivamente, BEM COMO TODOS seus relacionamentos. @endsection
                                    @section('modal_close')Fechar @endsection
                                    @section('modal_save')Excluir @endsection
                            @endcan
                            </td>
                          </tr>
                        @empty
                        <tr>
                            <td colspan="5">Nenhum registro encontrado</td>
                        </tr>
                        @endforelse
                        </tbody>
                    </table>
                    <!-- Nav tabs - LISTA forma_pagamento - INATIVA - FIM -->
                </div>
            </div>


            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

@endsection


@section('script-js')
    <!-- Required datatable js -->
    <script src="{{asset('nazox/assets/libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('nazox/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <!-- Responsive examples -->
    <script src="{{asset('nazox/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('nazox/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')}}"></script>
   <!-- Datatable init js -->
    <script src="{{asset('nazox/assets/js/pages/datatables.init.js')}}"></script>

    @if($forma_pagamentos_AT->count() > 0)
        <script>
            var table_AT = $('#dt_forma_pagamentos_AT').DataTable({
                language: {
                    url: '{{asset('nazox/assets/localisation/pt_br.json')}}'
                },
                "order": [[ 1, "desc" ]]
            });
        </script>
    @endif

    @if($forma_pagamentos_IN->count() > 0)
        <script>
            var table_IN = $('#dt_forma_pagamentos_IN').DataTable({
                language: {
                    url: '{{asset('nazox/assets/localisation/pt_br.json')}}'
                },
                "order": [[ 1, "desc" ]]
            });
        </script>
    @endif

    <script>
       function deleteData(id)
       {
           var id = id;
           var url = '{{ route("forma_pagamento.destroy", ":id") }}';
           url = url.replace(':id', id);
           $("#deleteForm").attr('action', url);
       }

       function formSubmit()
       {
           $("#deleteForm").submit();
       }
    </script>

@endsection
