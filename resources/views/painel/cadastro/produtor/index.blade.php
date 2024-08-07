@extends('painel.layout.index')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Produtores do Cliente</h4>

            @can('create_produtor')
            <div class="page-title-right">
                <a href="{{route("produtor.create")}}" class="btn btn-outline-secondary waves-effect" style="background: #4CAF50; border: #4CAF50; color: #fff !important; font-weight: 800;">Novo Produtor</a>
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

                <h4 class="card-title">Listagem dos Produtores Registrados para o Cliente</h4>
                <p class="card-title-desc"></p>

                <!-- Nav tabs - LISTA produtor - INI -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#ativa" role="tab">
                            <span class="d-block d-sm-none"><i class="ri-checkbox-circle-line"></i></span>
                            <span class="d-none d-sm-block">Produtores Ativos ( <code class="highlighter-rouge">{{$produtors_AT->count()}}</code> )
                                @can('create_produtor')
                                    <i class="fas fa-plus-circle" onclick="location.href='{{route('produtor.create')}}'" style="color: goldenrod" title="Novo Produtor"></i>
                                @endcan
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#inativa" role="tab">
                            <span class="d-block d-sm-none"><i class=" ri-close-circle-line"></i></span>
                            <span class="d-none d-sm-block">Produtores Inativos ( <code class="highlighter-rouge">{{$produtors_IN->count()}}</code> )</span>
                        </a>
                    </li>
                </ul>
                <!-- Nav tabs - LISTA produtor - FIM -->

                <!-- Tab panes -->
                <div class="tab-content p-3 text-muted">

                <!-- Nav tabs - LISTA produtor - ATIVA - INI -->
                <div class="tab-pane active" id="ativa" role="tabpanel">
                    <table id="dt_produtors_AT" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th style="width:30px;">Tipo Pessoa</th>
                            <th style="width:30px;">CPF/CNPJ</th>
                            <th style="width:150px;">Nome</th>
                            <th>E-mail</th>
                            <th style="width:30px;">Telefone</th>
                            <th style="text-align:center;">Ações</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($produtors_AT as $produtor)
                        <tr>
                            <td>{{$produtor->id}}</td>
                            <td style="width:30px;">{{$produtor->tipo_pessoa_texto}}</td>
                            <td style="width:30px;" class="{{($produtor->tipo_pessoa) == 'PF' ? 'mask_cpf' : 'mask_cnpj'}}">{{$produtor->cpf_cnpj}}</td>
                            <td  style="width:150px;" data-toggle="tooltip" title="{{ $produtor->nome }}">{{$produtor->nome_reduzido}}</td>
                            <td data-toggle="tooltip" title="{{ $produtor->email }}">{{$produtor->email_reduzido}}</td>
                            <td  style="width:30px;" class="mask_telefone">{{$produtor->telefone}}</td>
                            <td style="text-align:center;">

                            @can('view_produtor')
                                <a href="{{route('produtor.show', compact('produtor'))}}"><i class="fa fa-edit" style="color: goldenrod" title="Visualizar o Produtor"></i></a>
                            @endcan                            

                            @can('edit_produtor')
                                <a href="{{route('produtor.alterar_status', compact('produtor'))}}"><i class="fas fa-exchange-alt" style="color: goldenrod; margin-right:3px;" title="Ativar/Inativar Produtor"></i></a>
                            @endcan                            

                            @can('delete_produtor')
                                <a href="javascript:;" data-toggle="modal" onclick="deleteData({{$produtor->id}})"
                                    data-target="#modal-delete-produtor"><i class="fa fa-minus-circle" style="color: crimson" title="Excluir o Produtor"></i></a>
                                    <form action="" id="deleteForm" method="post">
                                    @csrf
                                    @method('DELETE')
                                    </form>
                                    @section('modal_target')"formSubmit();"@endsection
                                    @section('modal_type')@endsection
                                    @section('modal_name')"modal-delete-produtor"@endsection
                                    @section('modal_msg_title')Deseja excluir o registro ? @endsection
                                    @section('modal_msg_description')O registro selecionado será excluído definitivamente. @endsection
                                    @section('modal_close')Fechar @endsection
                                    @section('modal_save')Excluir @endsection
                            @endcan
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">Nenhum registro encontrado</td>
                        </tr>
                        @endforelse
                        </tbody>
                    </table>
                    <!-- Nav tabs - LISTA produtor - ATIVA - FIM -->
                </div>


                <!-- Nav tabs - LISTA produtor - INATIVA - INI -->
                <div class="tab-pane" id="inativa" role="tabpanel">
                    <table id="dt_produtors_IN" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tipo Pessoa</th>
                            <th>CPF/CNPJ</th>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Telefone</th>
                            <th style="text-align:center;">Ações</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($produtors_IN as $produtor)
                        <tr>
                            <td>{{$produtor->id}}</td>
                            <td>{{$produtor->tipo_pessoa_texto}}</td>
                            <td class="{{($produtor->tipo_pessoa) == 'PF' ? 'mask_cpf' : 'mask_cnpj'}}">{{$produtor->cpf_cnpj}}</td>
                            <td data-toggle="tooltip" title="{{ $produtor->nome }}">{{$produtor->nome_produtor_reduzido}}</td>
                            <td data-toggle="tooltip" title="{{ $produtor->email }}">{{$produtor->email_reduzido}}</td>
                            <td class="mask_telefone">{{$produtor->telefone}}</td>
                            <td style="text-align:center;">

                            @can('view_produtor')
                                <a href="{{route('produtor.show', compact('produtor'))}}"><i class="fa fa-edit" style="color: goldenrod" title="Visualizar o Produtor"></i></a>
                            @endcan                            

                            @can('edit_produtor')
                                <a href="{{route('produtor.alterar_status', compact('produtor'))}}"><i class="fas fa-exchange-alt" style="color: goldenrod; margin-right:3px;" title="Ativar/Inativar Produtor"></i></a>    
                            @endcan                            

                            @can('delete_produtor')
                                <a href="javascript:;" data-toggle="modal" onclick="deleteData({{$produtor->id}})"
                                    data-target="#modal-delete-produtor"><i class="fa fa-minus-circle" style="color: crimson" title="Excluir o Produtor"></i></a>
                                    <form action="" id="deleteForm" method="post">
                                    @csrf
                                    @method('DELETE')
                                    </form>
                                    @section('modal_target')"formSubmit();"@endsection
                                    @section('modal_type')@endsection
                                    @section('modal_name')"modal-delete-produtor"@endsection
                                    @section('modal_msg_title')Deseja excluir o registro ? @endsection
                                    @section('modal_msg_description')O registro selecionado será excluído definitivamente, BEM COMO TODOS seus relacionamentos. @endsection
                                    @section('modal_close')Fechar @endsection
                                    @section('modal_save')Excluir @endsection
                            @endcan
                            </td>
                          </tr>
                        @empty
                        <tr>
                            <td colspan="7">Nenhum registro encontrado</td>
                        </tr>
                        @endforelse
                        </tbody>
                    </table>
                    <!-- Nav tabs - LISTA produtor - INATIVA - FIM -->
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

    @if($produtors_AT->count() > 0)
        <script>
            var table_AT = $('#dt_produtors_AT').DataTable({
                language: {
                    url: '{{asset('nazox/assets/localisation/pt_br.json')}}'
                },
                "order": [[ 1, "desc" ]]
            });
        </script>
    @endif

    @if($produtors_IN->count() > 0)
        <script>
            var table_IN = $('#dt_produtors_IN').DataTable({
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
           var url = '{{ route("produtor.destroy", ":id") }}';
           url = url.replace(':id', id);
           $("#deleteForm").attr('action', url);
       }

       function formSubmit()
       {
           $("#deleteForm").submit();
       }
    </script>

@endsection
