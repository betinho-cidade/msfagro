@extends('painel.layout.index')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
        <h4 class="mb-0">Usuários do Cliente</h4>
        
            @can('create_usuario_cliente')
                <div class="page-title-right">
                    <a href="{{route("usuario_cliente.create")}}" class="btn btn-outline-secondary waves-effect" style="background: #4CAF50; border: #4CAF50; color: #fff !important; font-weight: 800;">Novo Usuário do Cliente</a>
                </div>          
            @endcan  
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

                <h4 class="card-title">Listagem dos Usuários Registrados para o Cliente no Sistema</h4>
                <p class="card-title-desc"></p>

                <!-- Nav tabs - LISTA usuario_cliente - INI -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#ativa" role="tab">
                            <span class="d-block d-sm-none"><i class="ri-checkbox-circle-line"></i></span>
                            <span class="d-none d-sm-block">Usuários ( <code class="highlighter-rouge">{{$cliente_users->count()}}</code> )
                                @can('create_usuario_cliente')
                                    <i class="fas fa-plus-circle" onclick="location.href='{{route('usuario_cliente.create')}}'" style="color: goldenrod" title="Novo Usuário"></i>
                                @endcan
                            </span>
                        </a>
                    </li>
                </ul>
                <!-- Nav tabs - LISTA usuario_cliente - FIM -->

                <!-- Tab panes -->
                <div class="tab-content p-3 text-muted">

                <!-- Nav tabs - LISTA USUÁRIO - ATIVA - INI -->
                <div class="tab-pane active" id="ativa" role="tabpanel">
                    <table id="dt_users_AT" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Login</th>
                            <th style="text-align:center;">Perfil</th>
                            <th style="text-align:center;">Ações</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($cliente_users as $usuario_cliente)
                        <tr>
                            <td>{{$usuario_cliente->id}}</td>
                            <td>{{$usuario_cliente->user->name}}</td>
                            <td>{{$usuario_cliente->user->email}}</td>
                            <td style="text-align:center;">{{$usuario_cliente->perfil->description}}</td>
                            <td style="text-align:center;">

                            @can('edit_usuario_cliente')
                                <a href="{{route('usuario_cliente.show', compact('usuario_cliente'))}}"><i class="fa fa-edit" style="color: goldenrod" title="Editar o Usuário"></i></a>
                            @endcan

                            @can('delete_usuario_cliente')
                                @if($user->id != $usuario_cliente->user->id)
                                    <a href="javascript:;" data-toggle="modal" onclick="deleteData({{$usuario_cliente->id}})"
                                    data-target="#modal-delete-usuario_cliente"><i class="fa fa-minus-circle" style="color: crimson" title="Excluir o Usuário"></i></a>
                                    <form action="" id="deleteForm" method="post">
                                    @csrf
                                    @method('DELETE')
                                    </form>
                                    @section('modal_target')"formSubmit();"@endsection
                                    @section('modal_type')@endsection
                                    @section('modal_name')"modal-delete-usuario_cliente"@endsection
                                    @section('modal_msg_title')Deseja excluir o registro ? @endsection
                                    @section('modal_msg_description')O registro selecionado será excluído definitivamente. @endsection
                                    @section('modal_close')Fechar @endsection
                                    @section('modal_save')Excluir @endsection
                                @endif
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
                    <!-- Nav tabs - LISTA usuario_cliente - ATIVA - FIM -->
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

    @if($cliente_users->count() > 0)
        <script>
            var table_AT = $('#dt_users_AT').DataTable({
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
           var url = '{{ route("usuario_cliente.destroy", ":id") }}';
           url = url.replace(':id', id);
           $("#deleteForm").attr('action', url);
       }

       function formSubmit()
       {
           $("#deleteForm").submit();
       }
    </script>

@endsection
