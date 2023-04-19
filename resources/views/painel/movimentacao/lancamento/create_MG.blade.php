@extends('painel.layout.index')


@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Novo Lançamento de Movimentação Bovina para o Cliente</h4>
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

            <h4 class="card-title">Formulário de Cadastro - Lançamento de Movimentação Bovina</h4>
            <p class="card-title-desc">O Lançamento cadastrado estará disponível para os movimentos no sistema.</p>
            <form name="create_lancamento" method="POST" action="{{route('lancamento.store_MG')}}"  class="needs-validation"  accept-charset="utf-8" enctype="multipart/form-data" novalidate>
                @csrf

                <div class="bg-soft-primary p-3 rounded" style="margin-bottom:10px;">
                    <h5 class="text-primary font-size-14" style="margin-bottom: 0px;">Dados do Lançamento de Movimentação Bovina</h5>
                </div>

                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="tipo_pessoa" class="{{($errors->first('tipo_pessoa') ? 'form-error-label' : '')}}">Tipo Pessoa</label>
                            <select id="tipo_pessoa" name="tipo_pessoa" class="form-control {{($errors->first('tipo_pessoa') ? 'form-error-field' : '')}} dynamic_tipo" required>
                                <option value="">---</option>
                                <option value="PF" {{(old('tipo_pessoa') == 'PF') ? 'selected' : '' }}>Pessoa Física</option>
                                <option value="PJ" {{(old('tipo_pessoa') == 'PJ') ? 'selected' : '' }}>Pessoa Jurídica</option>
                            </select>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="cpf">CPF/CNPJ</label>
                        <input type="text" name="cpf_cnpj" id="cpf_cnpj" class="form-control mask_cpf_cnpj" value="{{old('cpf_cnpj')}}" placeholder="---" required>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="nome">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" value="{{old('nome')}}" placeholder="Nome" required>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="situacao">Situação</label>
                            <select id="situacao" name="situacao" class="form-control" required>
                                <option value="">---</option>
                                <option value="A" {{(old('situacao') == 'A') ? 'selected' : '' }}>Ativo</option>
                                <option value="I" {{(old('situacao') == 'I') ? 'selected' : '' }}>Inativo</option>
                            </select>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{old('email')}}" placeholder="E-mail" required>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="telefone">Telefone</label>
                            <input type="text" class="form-control mask_telefone" id="telefone" name="telefone" value="{{old('telefone')}}" placeholder="Telefone">
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="inscricao_estadual">Inscrição Estadual</label>
                            <input type="text" class="form-control" id="inscricao_estadual" name="inscricao_estadual" value="{{old('inscricao_estadual')}}" placeholder="Inscrição Estadual">
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
                        <input type="text" name="end_cep" id="end_cep" class="form-control dynamic_cep mask_cep" value="{{old('end_cep')}}" placeholder="99.999-999">
                    </div>

                    <div class="col-md-4">
                        <label for="end_cidade">Cidade</label>
                        <input type="text" name="end_cidade" id="end_cidade" class="form-control" value="{{old('end_cidade')}}">
                    </div>

                    <div class="col-md-2">
                        <label for="end_uf">Estado</label>
                        <input type="text" name="end_uf" id="end_uf" class="form-control" value="{{old('end_uf')}}">
                    </div>

                    <div class="col-md-4">
                        <label for="end_bairro">Bairro</label>
                        <input type="text" name="end_bairro" id="end_bairro" class="form-control" value="{{old('end_bairro')}}">
                    </div>
                </div>
                <p></p>
                <div class="row">
                    <div class="col-md-6">
                        <label for="end_endereco">Endereço</label>
                        <input type="text" name="end_logradouro" id="end_logradouro" class="form-control" value="{{old('end_logradouro')}}">
                    </div>

                    <div class="col-md-2">
                        <label for="end_numero">Número</label>
                        <input type="text" name="end_numero" id="end_numero" value="{{old('end_numero')}}" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label for="end_complemento">Complemento </label>
                        <input type="text" name="end_complemento" id="end_complemento" class="form-control" value="{{old('end_complemento')}}">
                    </div>
                </div>
                <p></p>
            <!-- Dados Endereço - FIM -->

                <button class="btn btn-primary" type="submit">Salvar Cadastro</button>
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

    <script>
		$(document).ready(function(){
			$('.mask_cep').inputmask('99.999-999');
            $('.mask_telefone').inputmask('(99) 99999-9999');
            $('.select2').select2();
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
