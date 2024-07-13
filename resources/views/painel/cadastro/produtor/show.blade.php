@extends('painel.layout.index')


@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Informações do Produtor</h4>
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

            <h4 class="card-title">Formulário de Atualização - Produtor {{$produtor->nome}}</h4>
            <p class="card-title-desc">O Produtor cadastrado estará disponível para os lançamentos no sistema.</p>

            @can('edit_produtor')
            <form name="edit_produtor" method="POST" action="{{route('produtor.update', compact('produtor'))}}"  class="needs-validation" accept-charset="utf-8" enctype="multipart/form-data" novalidate>
                @csrf
                @method('PUT')
                <input type="hidden" name="liberado" id="liberado" @if($produtor->has_lancamento) value="NAO" style="background-color: #D3D3D3;" disabled @else value=OK @endif required>
            @endcan

                <div class="bg-soft-primary p-3 rounded" style="margin-bottom:10px;">
                    <h5 class="text-primary font-size-14" style="margin-bottom: 0px;">Dados do Produtor</h5>
                </div>

                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="tipo_pessoa" class="{{($errors->first('tipo_pessoa') ? 'form-error-label' : '')}}">Tipo Pessoa</label>
                            <select id="tipo_pessoa" name="tipo_pessoa" class="form-control {{($errors->first('tipo_pessoa') ? 'form-error-field' : '')}} dynamic_tipo" @if($produtor->has_lancamento) style="background-color: #D3D3D3;" disabled @endif required>
                                <option value="">---</option>
                                <option value="PF" {{($produtor->tipo_pessoa == 'PF') ? 'selected' : '' }}>Pessoa Física</option>
                                <option value="PJ" {{($produtor->tipo_pessoa == 'PJ') ? 'selected' : '' }}>Pessoa Jurídica</option>
                            </select>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="cpf_cnpj" class="{{($errors->first('cpf_cnpj') ? 'form-error-label' : '')}}">CPF/CNPJ</label>
                        <img src="{{asset('images/loading.gif')}}" id="img-loading-cnpj" style="display:none;max-width: 10%; margin-left: 26px;">
                        <input type="text" name="cpf_cnpj" id="cpf_cnpj" class="form-control {{($errors->first('cpf_cnpj') ? 'form-error-field' : '')}} dynamic_cnpj mask_cpf_cnpj" value="{{$produtor->cpf_cnpj}}" placeholder="---" @if($produtor->has_lancamento) style="background-color: #D3D3D3;" disabled @endif required>
                        <div class="valid-feedback">ok!</div>
                        <div class="invalid-feedback">Inválido!</div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="nome" class="{{($errors->first('nome') ? 'form-error-label' : '')}}">Nome</label>
                            <input type="text" class="form-control {{($errors->first('nome') ? 'form-error-field' : '')}}" id="nome" name="nome" value="{{$produtor->nome}}" placeholder="Nome" @if($produtor->has_lancamento) style="background-color: #D3D3D3;" disabled @endif required>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="situacao" class="{{($errors->first('situacao') ? 'form-error-label' : '')}}">Situação</label>
                            <select id="situacao" name="situacao" class="form-control {{($errors->first('situacao') ? 'form-error-field' : '')}}" required>
                                <option value="">---</option>
                                <option value="A" {{($produtor->status == 'A') ? 'selected' : '' }}>Ativo</option>
                                <option value="I" {{($produtor->status == 'I') ? 'selected' : '' }}>Inativo</option>
                            </select>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="email" class="{{($errors->first('email') ? 'form-error-label' : '')}}">E-mail</label>
                            <input type="email" class="form-control {{($errors->first('email') ? 'form-error-field' : '')}}" id="email" name="email" value="{{$produtor->email}}" placeholder="E-mail" required>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="telefone" class="{{($errors->first('telefone') ? 'form-error-label' : '')}}">Telefone</label>
                            <input type="text" class="form-control {{($errors->first('telefone') ? 'form-error-field' : '')}} mask_telefone" id="telefone" name="telefone" value="{{$produtor->telefone}}" placeholder="Telefone">
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="inscricao_representante" class="{{($errors->first('inscricao_representante') ? 'form-error-label' : '')}}">É o representante ?</label>
                            <select id="inscricao_representante" name="inscricao_representante" class="form-control {{($errors->first('inscricao_representante') ? 'form-error-field' : '')}}" required>
                                <option value="">---</option>
                                <option value="S" {{($produtor->inscricao_representante == 'S') ? 'selected' : '' }}>Sim</option>
                                <option value="N" {{($produtor->inscricao_representante == 'N') ? 'selected' : '' }}>Não</option>
                            </select>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="inscricao_estadual" class="{{($errors->first('inscricao_estadual') ? 'form-error-label' : '')}}">Inscrição Estadual</label>
                            <input type="text" class="form-control {{($errors->first('inscricao_estadual') ? 'form-error-field' : '')}}" id="inscricao_estadual" name="inscricao_estadual" value="{{$produtor->inscricao_estadual}}" placeholder="Inscrição Estadual">
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                </div>
            <!-- Dados Pessoais -- FIM -->

            <!-- Dados Endereço - INI -->
            <div class="bg-soft-primary p-3 rounded" style="margin-bottom:10px;">
                <h5 class="text-primary font-size-14" style="margin-bottom: 0px;">Dados Endereço</h5>
            </div>
                <div class="row">
                    <div class="col-md-2">
                        <label for="end_cep" class="{{($errors->first('end_cep') ? 'form-error-label' : '')}}">CEP</label>
                        <img src="{{asset('images/loading.gif')}}" id="img-loading-cep" style="display:none;max-width: 17%; margin-left: 26px;">
                        <input type="text" name="end_cep" id="end_cep" class="form-control {{($errors->first('end_cep') ? 'form-error-field' : '')}} dynamic_cep mask_cep" value="{{$produtor->end_cep}}" placeholder="99.999-999" required>
                    </div>

                    <div class="col-md-4">
                        <label for="end_cidade" class="{{($errors->first('end_cidade') ? 'form-error-label' : '')}}">Cidade</label>
                        <input type="text" name="end_cidade" id="end_cidade" class="form-control {{($errors->first('end_cidade') ? 'form-error-field' : '')}}" value="{{$produtor->end_cidade}}" required>
                    </div>

                    <div class="col-md-2">
                        <label for="end_uf" class="{{($errors->first('end_uf') ? 'form-error-label' : '')}}">Estado</label>
                        <input type="text" name="end_uf" id="end_uf" class="form-control {{($errors->first('end_uf') ? 'form-error-field' : '')}}" value="{{$produtor->end_uf}}" required>
                    </div>

                    <div class="col-md-4">
                        <label for="end_bairro" class="{{($errors->first('end_bairro') ? 'form-error-label' : '')}}">Bairro</label>
                        <input type="text" name="end_bairro" id="end_bairro" class="form-control {{($errors->first('end_bairro') ? 'form-error-field' : '')}}" value="{{$produtor->end_bairro}}" required>
                    </div>
                </div>
                <p></p>
                <div class="row">
                    <div class="col-md-6">
                        <label for="end_endereco" class="{{($errors->first('end_endereco') ? 'form-error-label' : '')}}">Endereço</label>
                        <input type="text" name="end_logradouro" id="end_logradouro" class="form-control {{($errors->first('end_endereco') ? 'form-error-field' : '')}}" value="{{$produtor->end_logradouro}}" required>
                    </div>

                    <div class="col-md-2">
                        <label for="end_numero" class="{{($errors->first('end_numero') ? 'form-error-label' : '')}}">Número</label>
                        <input type="text" name="end_numero" id="end_numero" value="{{$produtor->end_numero}}" class="form-control {{($errors->first('end_numero') ? 'form-error-field' : '')}}" required>
                    </div>

                    <div class="col-md-4">
                        <label for="end_complemento" class="{{($errors->first('end_complemento') ? 'form-error-label' : '')}}">Complemento </label>
                        <input type="text" name="end_complemento" id="end_complemento" class="form-control {{($errors->first('end_complemento') ? 'form-error-field' : '')}}" value="{{$produtor->end_complemento}}">
                    </div>
                </div>
                <p></p>
            <!-- Dados Endereço - FIM -->

            @can('edit_produtor')
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

    <script>
		$(document).ready(function(){
			$('.mask_cep').inputmask('99.999-999');
            $('.mask_telefone').inputmask('(99) 99999-9999');
            $('.select2').select2();
		});
	</script>

    <script>
		$(document).ready(function(){
            tipo = '{{$produtor->tipo_pessoa}}';

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
            $('.dynamic_cep').change(function(){

                if ($(this).val() != ''){
                    document.getElementById("img-loading-cep").style.display = '';

                    var cep = $('#end_cep').val();
                    var _token = $('input[name="_token"]').val();

                    $('#end_logradouro').val('');
                    $('#end_complemento').val('');
                    $('#end_numero').val('');
                    $('#end_bairro').val('');
                    $('#end_cidade').val('');
                    $('#end_uf').val('');

                    $.ajax({
                        url: "{{route('painel.js_viacep')}}",
                        method: "POST",
                        data: {_token:_token, cep:cep},
                        success:function(result){
                            dados = JSON.parse(result);
                            if(dados==null || dados['error'] == 'true'){
                                    console.log(dados);
                            } else{
                                    $('#end_logradouro').val(dados['logradouro']);
                                    $('#end_complemento').val(dados['complemento']);
                                    $('#end_bairro').val(dados['bairro']);
                                    $('#end_cidade').val(dados['localidade']);
                                    $('#end_uf').val(dados['uf']);
                            }
                            document.getElementById("img-loading-cep").style.display = 'none';
                        },
                        error:function(erro){
                            document.getElementById("img-loading-cep").style.display = 'none';
                        }
                    })
                }
            });
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
                    $('#email').val('');
                    $('#telefone').val('');
                    $('#end_cep').val('');                    
                    $('#end_cidade').val('');      
                    $('#end_uf').val('');    
                    $('#end_bairro').val('');    
                    $('#end_logradouro').val('');    
                    $('#end_numero').val('');  
                    $('#end_complemento').val('');    

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
                                        $('#email').val(dados['email']);

                                        telefone = dados['telefone'];

                                        if(telefone.includes("/")){
                                            telefone = telefone.substring(0, telefone.indexOf("/"));
                                            $('#telefone').val(telefone.replace(/[^0-9]+/g, ""));    
                                        }else{
                                            $('#telefone').val(telefone.replace(/[^0-9]+/g, ""));    
                                        }

                                        $('#end_cep').val(dados['cep'].replace(/[^0-9]+/g, ""));
                                        $('#end_cidade').val(dados['municipio']);
                                        $('#end_uf').val(dados['uf']);
                                        $('#end_bairro').val(dados['bairro']);
                                        $('#end_logradouro').val(dados['logradouro']);
                                        $('#end_numero').val(dados['numero']);
                                        $('#end_complemento').val(dados['complemento']);
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
