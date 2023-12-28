@extends('painel.layout.index')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Clientes do Sistema</h4>
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

                <h4 class="card-title">Listagem dos Clientes Registrados no Sistema</h4>
                <p class="card-title-desc"></p>

                <!-- Nav tabs - LISTA cliente - INI -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#ativa" role="tab">
                            <span class="d-block d-sm-none"><i class="ri-checkbox-circle-line"></i></span>
                            <span class="d-none d-sm-block">Clientes Ativos ( <code class="highlighter-rouge">{{$clientes_AT->count()}}</code> )
                                @can('create_cliente')
                                    <i class="fas fa-plus-circle" onclick="location.href='{{route('cliente.create')}}'" style="color: goldenrod" title="Novo Cliente"></i>
                                @endcan
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#inativa" role="tab">
                            <span class="d-block d-sm-none"><i class=" ri-close-circle-line"></i></span>
                            <span class="d-none d-sm-block">Clientes Inativos ( <code class="highlighter-rouge">{{$clientes_IN->count()}}</code> )</span>
                        </a>
                    </li>
                </ul>
                <!-- Nav tabs - LISTA cliente - FIM -->

                <!-- Tab panes -->
                <div class="tab-content p-3 text-muted">

                <!-- Nav tabs - LISTA cliente - ATIVA - INI -->
                <div class="tab-pane active" id="ativa" role="tabpanel">
                    <table id="dt_clientes_AT" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tipo</th>
                            <th>CPF/CNPJ</th>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Telefone</th>
                            <th>Usuário</th>
                            <th style="text-align:center;">Ações</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($clientes_AT as $cliente)
                        <tr>
                            <td>{{$cliente->id}}</td>
                            <td>{{$cliente->tipo_cliente}}</td>
                            <td class="{{($cliente->tipo_pessoa) == 'PF' ? 'mask_cpf' : 'mask_cnpj'}}">{{$cliente->cpf_cnpj}}</td>
                            <td data-toggle="tooltip" title="{{ $cliente->nome }}">{{$cliente->nome_reduzido}}</td>
                            <td data-toggle="tooltip" title="{{ $cliente->email }}">{{$cliente->email_reduzido}}</td>
                            <td class="mask_telefone">{{$cliente->telefone}}</td>
                            <td data-toggle="tooltip" title="{{ $cliente->user->name }}">{{Str::limit($cliente->user->name, 150, '...')}}</td>
                            <td style="text-align:center;">

                            @can('edit_cliente')
                                <a href="{{route('cliente.show', compact('cliente'))}}"><i class="fa fa-edit" style="color: goldenrod" title="Editar o Cliente"></i></a>
                            @endcan

                            @can('delete_cliente')
                                <a href="javascript:;" data-toggle="modal" onclick="deleteData({{$cliente->id}})"
                                    data-target="#modal-delete-cliente"><i class="fa fa-minus-circle" style="color: crimson" title="Excluir o Cliente"></i></a>
                                    <form action="" id="deleteForm" method="post">
                                    @csrf
                                    @method('DELETE')
                                    </form>
                                    @section('modal_target')"formSubmit();"@endsection
                                    @section('modal_type')@endsection
                                    @section('modal_name')"modal-delete-cliente"@endsection
                                    @section('modal_msg_title')Deseja excluir o registro ? @endsection
                                    @section('modal_msg_description')O registro selecionado será excluído definitivamente. @endsection
                                    @section('modal_close')Fechar @endsection
                                    @section('modal_save')Excluir @endsection
                            @endcan
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8">Nenhum registro encontrado</td>
                        </tr>
                        @endforelse
                        </tbody>
                    </table>
                    <!-- Nav tabs - LISTA cliente - ATIVA - FIM -->
                </div>


                <!-- Nav tabs - LISTA cliente - INATIVA - INI -->
                <div class="tab-pane" id="inativa" role="tabpanel">
                    <table id="dt_clientes_IN" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tipo</th>
                            <th>CPF/CNPJ</th>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Telefone</th>
                            <th>Usuário</th>
                            <th style="text-align:center;">Ações</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($clientes_IN as $cliente)
                        <tr>
                            <td>{{$cliente->id}}</td>
                            <td>{{$cliente->tipo_cliente}}</td>
                            <td class="{{($cliente->tipo_pessoa) == 'PF' ? 'mask_cpf' : 'mask_cnpj'}}">{{$cliente->cpf_cnpj}}</td>
                            <td data-toggle="tooltip" title="{{ $cliente->nome }}">{{$cliente->nome_reduzido}}</td>
                            <td data-toggle="tooltip" title="{{ $cliente->email }}">{{$cliente->email_reduzido}}</td>
                            <td class="mask_telefone">{{$cliente->telefone}}</td>
                            <td data-toggle="tooltip" title="{{ $cliente->user->name }}">{{Str::limit($cliente->user->name, 150, '...')}}</td>
                            <td style="text-align:center;">

                            @can('edit_cliente')
                                <a href="{{route('cliente.show', compact('cliente'))}}"><i class="fa fa-edit" style="color: goldenrod" title="Editar o Cliente"></i></a>
                            @endcan

                            @can('delete_cliente')
                                <a href="javascript:;" data-toggle="modal" onclick="deleteData({{$cliente->id}})"
                                    data-target="#modal-delete-cliente"><i class="fa fa-minus-circle" style="color: crimson" title="Excluir o Cliente"></i></a>
                                    <form action="" id="deleteForm" method="post">
                                    @csrf
                                    @method('DELETE')
                                    </form>
                                    @section('modal_target')"formSubmit();"@endsection
                                    @section('modal_type')@endsection
                                    @section('modal_name')"modal-delete-cliente"@endsection
                                    @section('modal_msg_title')Deseja excluir o registro ? @endsection
                                    @section('modal_msg_description')O registro selecionado será excluído definitivamente, BEM COMO TODOS seus relacionamentos. @endsection
                                    @section('modal_close')Fechar @endsection
                                    @section('modal_save')Excluir @endsection
                            @endcan
                            </td>
                          </tr>
                        @empty
                        <tr>
                            <td colspan="8">Nenhum registro encontrado</td>
                        </tr>
                        @endforelse
                        </tbody>
                    </table>
                    <!-- Nav tabs - LISTA cliente - INATIVA - FIM -->
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

    <!-- form mask -->
    <script src="{{asset('nazox/assets/libs/inputmask/jquery.inputmask.min.js')}}"></script>

    <script>
		$(document).ready(function(){
            $('.mask_cpf').inputmask('999.999.999-99');
            $('.mask_cnpj').inputmask('99.999.999/9999-99');
            $('.mask_telefone').inputmask('(99) 99999-9999');
            $('.select2').select2();
		});
	</script>


    @if($clientes_AT->count() > 0)
        <script>
            var table_AT = $('#dt_clientes_AT').DataTable({
                language: {
                    url: '{{asset('nazox/assets/localisation/pt_br.json')}}'
                },
                "order": [[ 1, "desc" ]]
            });
        </script>
    @endif

    @if($clientes_IN->count() > 0)
        <script>
            var table_IN = $('#dt_clientes_IN').DataTable({
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
           var url = '{{ route("cliente.destroy", ":id") }}';
           url = url.replace(':id', id);
           $("#deleteForm").attr('action', url);
       }

       function formSubmit()
       {
           $("#deleteForm").submit();
       }
    </script>

@endsection
