@extends('painel.layout.index')


@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Informações do Cliente</h4>
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

            <h4 class="card-title">Formulário de Atualização - Cliente {{$cliente->nome}}</h4>
            <p class="card-title-desc">O Cliente cadastrado estará disponível para os lançamentos no sistema.</p>

            <form name="edit_cliente" method="POST" action="{{route('cliente.update', compact('cliente'))}}"  class="needs-validation" accept-charset="utf-8" enctype="multipart/form-data" novalidate>
                @csrf
                @method('PUT')

                <!-- Dados Pessoais - INI -->
                <div class="bg-soft-primary p-3 rounded" style="margin-bottom:10px;">
                    <h5 class="text-primary font-size-14" style="margin-bottom: 0px;">Dados Cliente</h5>
                </div>

                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="tipo_pessoa">Tipo Pessoa</label>
                                <select id="tipo_pessoa" name="tipo_pessoa" class="form-control dynamic_tipo" required>
                                    <option value="">---</option>
                                    <option value="PF" {{($cliente->tipo_pessoa == 'PF') ? 'selected' : '' }}>Pessoa Física</option>
                                    <option value="PJ" {{($cliente->tipo_pessoa == 'PJ') ? 'selected' : '' }}>Pessoa Jurídica</option>
                                </select>
                                <div class="valid-feedback">ok!</div>
                                <div class="invalid-feedback">Inválido!</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="cpf">CPF/CNPJ</label>
                            <input type="text" name="cpf_cnpj" id="cpf_cnpj" class="form-control mask_cpf_cnpj" value="{{$cliente->cpf_cnpj}}" placeholder="---" required>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="nome">Nome</label>
                                <input type="text" class="form-control" id="nome" name="nome" value="{{$cliente->nome}}" placeholder="Nome" required>
                                <div class="valid-feedback">ok!</div>
                                <div class="invalid-feedback">Inválido!</div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="inscricao_representante">É o representante ?</label>
                                <select id="inscricao_representante" name="inscricao_representante" class="form-control" required>
                                    <option value="">---</option>
                                    <option value="S" {{($cliente->inscricao_representante == 'S') ? 'selected' : '' }}>Sim</option>
                                    <option value="N" {{($cliente->inscricao_representante == 'N') ? 'selected' : '' }}>Não</option>
                                </select>
                                <div class="valid-feedback">ok!</div>
                                <div class="invalid-feedback">Inválido!</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="tipo">Tipo Cliente</label>
                                <select id="tipo" name="tipo" class="form-control" required>
                                    <option value="">---</option>
                                    <option value="AG" {{($cliente->tipo == 'AG') ? 'selected' : '' }}>Agricultor</option>
                                    <option value="PE" {{($cliente->tipo == 'PE') ? 'selected' : '' }}>Pecuarísta</option>
                                    <option value="AB" {{($cliente->tipo == 'AB') ? 'selected' : '' }}>Ambos</option>
                                </select>
                                <div class="valid-feedback">ok!</div>
                                <div class="invalid-feedback">Inválido!</div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="email">E-mail</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{$cliente->email}}" placeholder="E-mail" required>
                                <div class="valid-feedback">ok!</div>
                                <div class="invalid-feedback">Inválido!</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="telefone">Telefone</label>
                                <input type="text" class="form-control mask_telefone" id="telefone" name="telefone" value="{{$cliente->telefone}}" placeholder="Telefone">
                                <div class="valid-feedback">ok!</div>
                                <div class="invalid-feedback">Inválido!</div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="inscricao_estadual">Inscrição Estadual</label>
                                <input type="text" class="form-control" id="inscricao_estadual" name="inscricao_estadual" value="{{$cliente->inscricao_estadual}}" placeholder="Inscrição Estadual">
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
                            <label for="end_cep">CEP</label>
                            <img src="{{asset('images/loading.gif')}}" id="img-loading-cep" style="display:none;max-width: 17%; margin-left: 26px;">
                            <input type="text" name="end_cep" id="end_cep" class="form-control dynamic_cep mask_cep" value="{{$cliente->end_cep}}" placeholder="99.999-999">
                        </div>

                        <div class="col-md-4">
                            <label for="end_cidade">Cidade</label>
                            <input type="text" name="end_cidade" id="end_cidade" class="form-control" value="{{$cliente->end_cidade}}">
                        </div>

                        <div class="col-md-2">
                            <label for="end_uf">Estado</label>
                            <input type="text" name="end_uf" id="end_uf" class="form-control" value="{{$cliente->end_uf}}">
                        </div>

                        <div class="col-md-4">
                            <label for="end_bairro">Bairro</label>
                            <input type="text" name="end_bairro" id="end_bairro" class="form-control" value="{{$cliente->end_bairro}}">
                        </div>
                    </div>
                    <p></p>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="end_endereco">Endereço</label>
                            <input type="text" name="end_logradouro" id="end_logradouro" class="form-control" value="{{$cliente->end_logradouro}}">
                        </div>

                        <div class="col-md-2">
                            <label for="end_numero">Número</label>
                            <input type="text" name="end_numero" id="end_numero" value="{{$cliente->end_numero}}" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label for="end_complemento">Complemento </label>
                            <input type="text" name="end_complemento" id="end_complemento" class="form-control" value="{{$cliente->end_complemento}}">
                        </div>
                    </div>
                    <p></p>
                <!-- Dados Endereço - FIM -->


                <!-- Dados Acesso - INI -->
                <div class="bg-soft-primary p-3 rounded" style="margin-bottom:10px;">
                    <h5 class="text-primary font-size-14" style="margin-bottom: 0px;">Dados Acesso</h5>
                </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="usuario">Usuário para acesso ao sistema</label>
                                <select id="usuario" name="usuario" class="form-control select2" required>
                                    <option value="">---</option>
                                    @foreach($usuarios as $usuario)
                                        <option value="{{ $usuario->id }}" {{($cliente->user_id == $usuario->id) ? 'selected' : '' }}>{{ $usuario->name }}</option>
                                    @endforeach
                                    <div class="valid-feedback">ok!</div>
                                    <div class="invalid-feedback">Inválido!</div>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="situacao">Situação</label>
                                <select id="situacao" name="situacao" class="form-control" required>
                                    <option value="">---</option>
                                    <option value="A" {{($cliente->status == 'A') ? 'selected' : '' }}>Ativo</option>
                                    <option value="I" {{($cliente->status == 'I') ? 'selected' : '' }}>Inativo</option>
                                </select>
                                <div class="valid-feedback">ok!</div>
                                <div class="invalid-feedback">Inválido!</div>
                            </div>
                        </div>
                    </div>
                <!-- Dados Acesso -- FIM -->

                <button class="btn btn-primary" type="submit">Atualizar Cadastro</button>
            </form>


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
            tipo = '{{$cliente->tipo_pessoa}}';

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
        });
    </script>


@endsection

@section('head-css')
    <link href="{{asset('nazox/assets/libs/magnific-popup/magnific-popup.css')}}" rel="stylesheet" type="text/css" />
@endsection
