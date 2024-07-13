@extends('painel.layout.index')


@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Informações da Empresa</h4>
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

            <h4 class="card-title">Formulário de Atualização - Empresa {{$empresa->nome}}</h4>
            <p class="card-title-desc">A Empresa cadastrada estará disponível para os lançamentos no sistema.</p>

            @can('edit_empresa')
            <form name="edit_empresa" method="POST" action="{{route('empresa.update', compact('empresa'))}}"  class="needs-validation" accept-charset="utf-8" enctype="multipart/form-data" novalidate>
                @csrf
                @method('PUT')
                <input type="hidden" name="liberado" id="liberado" @if($empresa->has_lancamento) value="NAO" style="background-color: #D3D3D3;" disabled @else value=OK @endif required>
            @endcan

                <!-- Dados Pessoais - INI -->
                <div class="bg-soft-primary p-3 rounded" style="margin-bottom:10px;">
                    <h5 class="text-primary font-size-14" style="margin-bottom: 0px;">Dados da Empresa</h5>
                </div>

                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="tipo_pessoa" class="{{($errors->first('tipo_pessoa') ? 'form-error-label' : '')}}">Tipo Pessoa</label>
                            <select id="tipo_pessoa" name="tipo_pessoa" class="form-control {{($errors->first('tipo_pessoa') ? 'form-error-field' : '')}} dynamic_tipo" @if($empresa->has_lancamento) style="background-color: #D3D3D3;" disabled @endif required>
                                <option value="">---</option>
                                <option value="PF" {{($empresa->tipo_pessoa == 'PF') ? 'selected' : '' }}>Pessoa Física</option>
                                <option value="PJ" {{($empresa->tipo_pessoa == 'PJ') ? 'selected' : '' }}>Pessoa Jurídica</option>
                            </select>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="cpf_cnpj" class="{{($errors->first('cpf_cnpj') ? 'form-error-label' : '')}}">CPF/CNPJ</label>
                        <img src="{{asset('images/loading.gif')}}" id="img-loading-cnpj" style="display:none;max-width: 10%; margin-left: 26px;">
                        <input type="text" name="cpf_cnpj" id="cpf_cnpj" class="form-control {{($errors->first('cpf_cnpj') ? 'form-error-field' : '')}} dynamic_cnpj mask_cpf_cnpj" value="{{$empresa->cpf_cnpj}}" placeholder="---" @if($empresa->has_lancamento) style="background-color: #D3D3D3;" disabled @endif required>
                        <div class="valid-feedback">ok!</div>
                        <div class="invalid-feedback">Inválido!</div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="nome" class="{{($errors->first('nome') ? 'form-error-label' : '')}}">Nome</label>
                            <input type="text" class="form-control {{($errors->first('nome') ? 'form-error-field' : '')}}" id="nome" name="nome" value="{{$empresa->nome}}" placeholder="Nome" @if($empresa->has_lancamento) style="background-color: #D3D3D3;" disabled @endif required>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="situacao" class="{{($errors->first('situacao') ? 'form-error-label' : '')}}">Situação</label>
                            <select id="situacao" name="situacao" class="form-control {{($errors->first('situacao') ? 'form-error-field' : '')}}" required>
                                <option value="">---</option>
                                <option value="A" {{($empresa->status == 'A') ? 'selected' : '' }}>Ativo</option>
                                <option value="I" {{($empresa->status == 'I') ? 'selected' : '' }}>Inativo</option>
                            </select>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                </div>

                <!-- Dados Pessoais -- FIM -->
            @can('edit_empresa')
                <button class="btn btn-primary" type="submit">Atualizar Cadastro</button>
            </form>
            @endcan


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
    <!-- form mask -->
    <script src="{{asset('nazox/assets/libs/inputmask/jquery.inputmask.min.js')}}"></script>

    <script src="{{asset('nazox/assets/libs/magnific-popup/jquery.magnific-popup.min.js')}}"></script>
    <script src="{{asset('nazox/assets/js/pages/lightbox.init.js')}}"></script>

    <script>
		$(document).ready(function(){
            $('.select2').select2();
		});
	</script>

    <script>
		$(document).ready(function(){
            tipo = '{{$empresa->tipo_pessoa}}';

            if(tipo == 'PF'){
                $('.mask_cpf_cnpj').inputmask('999.999.999-99');
            }
            if (tipo == 'PJ'){
                $('.mask_cpf_cnpj').inputmask('99.999.999/9999-99');
            }
        });
	</script>

    <script type='text/javascript'>
        $(document).ready(function(){
            $('.dynamic_tipo').change(function(){

                $('.mask_cpf_cnpj').attr('placeholder', '---');
                $('.mask_cpf_cnpj').val('');

                if ($(this).val() != ''){
                    var tipo = $(this).val();

                    if(tipo == 'PF'){
                        $('.mask_cpf_cnpj').inputmask('999.999.999-99');
                        $('.mask_cpf_cnpj').attr('placeholder', 'Informe o CPF');
                    }
                    if (tipo == 'PJ'){
                        $('.mask_cpf_cnpj').inputmask('99.999.999/9999-99');
                        $('.mask_cpf_cnpj').attr('placeholder', 'Informe o CNPJ');
                    }
                }
            });

            $('.dynamic_cnpj').change(function(){

                if ($(this).val() != ''){
                    var tipo_pessoa = $('#tipo_pessoa').val();
                    var cnpj = $('#cpf_cnpj').val();
                    var _token = $('input[name="_token"]').val();

                    $('#nome').val('');

                    if(tipo_pessoa && tipo_pessoa == 'PJ') {                    
                        document.getElementById("img-loading-cnpj").style.display = '';

                        $.ajax({
                            url: "{{route('painel.js_cnpj')}}",
                            method: "POST",
                            data: {_token:_token, cnpj:cnpj},
                            success:function(result){
                                dados = JSON.parse(result);
                                if(dados==null || dados['error'] == 'true'){
                                        console.log(dados);
                                } else{
                                        $('#nome').val(dados['nome']);
                                }
                                document.getElementById("img-loading-cnpj").style.display = 'none';
                            },
                            error:function(erro){
                                document.getElementById("img-loading-cnpj").style.display = 'none';
                            }
                        });
                    }
                }
            });            
        });
    </script>

@endsection

@section('head-css')
    <link href="{{asset('nazox/assets/libs/magnific-popup/magnific-popup.css')}}" rel="stylesheet" type="text/css" />
@endsection
