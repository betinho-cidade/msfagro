@extends('painel.layout.index')


@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Novo Cliente do Sistema</h4>
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

            <h4 class="card-title">Formulário de Cadastro - Cliente</h4>
            <p class="card-title-desc">O Cliente cadastrado estará disponível para os lançamentos no sistema.</p>
            <form name="create_cliente" method="POST" action="{{route('cliente.store')}}"  class="needs-validation"  accept-charset="utf-8" enctype="multipart/form-data" novalidate>
                @csrf

                <!-- Dados Pessoais - INI -->
                <div class="bg-soft-primary p-3 rounded" style="margin-bottom:10px;">
                    <h5 class="text-primary font-size-14" style="margin-bottom: 0px;">Dados Cliente</h5>
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
                            <label for="cpf_cnpj" class="{{($errors->first('cpf_cnpj') ? 'form-error-label' : '')}}">CPF/CNPJ</label>
                            <input type="text" name="cpf_cnpj" id="cpf_cnpj" class="form-control {{($errors->first('cpf_cnpj') ? 'form-error-field' : '')}} mask_cpf_cnpj" value="{{old('cpf_cnpj')}}" placeholder="---" required>
                        </div>
                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="nome" class="{{($errors->first('nome') ? 'form-error-label' : '')}}">Nome</label>
                                <input type="text" class="form-control {{($errors->first('nome') ? 'form-error-field' : '')}}" id="nome" name="nome" value="{{old('nome')}}" placeholder="Nome" required>
                                <div class="valid-feedback">ok!</div>
                                <div class="invalid-feedback">Inválido!</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="tipo" class="{{($errors->first('tipo') ? 'form-error-label' : '')}}">Tipo Cliente</label>
                                <select id="tipo" name="tipo" class="form-control {{($errors->first('tipo') ? 'form-error-field' : '')}}" required>
                                    <option value="">---</option>
                                    <option value="AG" {{(old('tipo') == 'AG') ? 'selected' : '' }}>Agricultor</option>
                                    <option value="PE" {{(old('tipo') == 'PE') ? 'selected' : '' }}>Pecuarísta</option>
                                    <option value="AB" {{(old('tipo') == 'AB') ? 'selected' : '' }}>Ambos</option>
                                </select>
                                <div class="valid-feedback">ok!</div>
                                <div class="invalid-feedback">Inválido!</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email" class="{{($errors->first('email') ? 'form-error-label' : '')}}">E-mail</label>
                                <input type="email" class="form-control {{($errors->first('email') ? 'form-error-field' : '')}}" id="email" name="email" value="{{old('email')}}" placeholder="E-mail" required>
                                <div class="valid-feedback">ok!</div>
                                <div class="invalid-feedback">Inválido!</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="telefone" class="{{($errors->first('telefone') ? 'form-error-label' : '')}}">Telefone</label>
                                <input type="text" class="form-control {{($errors->first('telefone') ? 'form-error-field' : '')}} mask_telefone" id="telefone" name="telefone" value="{{old('telefone')}}" placeholder="Telefone">
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
                            <input type="text" name="end_cep" id="end_cep" class="form-control {{($errors->first('end_cep') ? 'form-error-field' : '')}} dynamic_cep mask_cep" value="{{old('end_cep')}}" placeholder="99.999-999" required>
                        </div>

                        <div class="col-md-4">
                            <label for="end_cidade" class="{{($errors->first('end_cidade') ? 'form-error-label' : '')}}">Cidade</label>
                            <input type="text" name="end_cidade" id="end_cidade" class="form-control {{($errors->first('end_cidade') ? 'form-error-field' : '')}}" value="{{old('end_cidade')}}" required>
                        </div>

                        <div class="col-md-2">
                            <label for="end_uf" class="{{($errors->first('end_uf') ? 'form-error-label' : '')}}">Estado</label>
                            <input type="text" name="end_uf" id="end_uf" class="form-control {{($errors->first('end_uf') ? 'form-error-field' : '')}}" value="{{old('end_uf')}}" required>
                        </div>

                        <div class="col-md-4">
                            <label for="end_bairro" class="{{($errors->first('end_bairro') ? 'form-error-label' : '')}}">Bairro</label>
                            <input type="text" name="end_bairro" id="end_bairro" class="form-control {{($errors->first('end_bairro') ? 'form-error-field' : '')}}" value="{{old('end_bairro')}}" required>
                        </div>
                    </div>
                    <p></p>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="end_endereco" class="{{($errors->first('end_bairro') ? 'form-error-label' : '')}}">Endereço</label>
                            <input type="text" name="end_logradouro" id="end_logradouro" class="form-control {{($errors->first('end_bairro') ? 'form-error-field' : '')}}" value="{{old('end_logradouro')}}" required>
                        </div>

                        <div class="col-md-2">
                            <label for="end_numero" class="{{($errors->first('end_numero') ? 'form-error-label' : '')}}">Número</label>
                            <input type="text" name="end_numero" id="end_numero" value="{{old('end_numero')}}" class="form-control {{($errors->first('end_numero') ? 'form-error-field' : '')}}" required>
                        </div>

                        <div class="col-md-4">
                            <label for="end_complemento" class="{{($errors->first('end_complemento') ? 'form-error-label' : '')}}">Complemento </label>
                            <input type="text" name="end_complemento" id="end_complemento" class="form-control {{($errors->first('end_complemento') ? 'form-error-field' : '')}}" value="{{old('end_complemento')}}">
                        </div>
                    </div>
                    <p></p>
                <!-- Dados Endereço - FIM -->

                <!-- Dados GoogleMaps - INI -->
                <div class="bg-soft-primary p-3 rounded" style="margin-bottom:10px;">
                    <h5 class="text-primary font-size-14" style="margin-bottom: 0px;">Dados GoogleMaps</h5>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="qtd_apimaps" class="{{($errors->first('qtd_apimaps') ? 'form-error-label' : '')}}">Qtd. API Maps <i class="fas fa-info-circle" data-toggle="tooltip" title="Quantidade de visualizações por mês relacionados ao Mapa que o cliente pode obter."></i></label>
                            <input type="text" class="form-control {{($errors->first('qtd_apimaps') ? 'form-error-field' : '')}}" id="qtd_apimaps" name="qtd_apimaps" value="{{old('qtd_apimaps')}}" placeholder="Qtd. API Maps" required>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="qtd_geolocation" class="{{($errors->first('qtd_geolocation') ? 'form-error-label' : '')}}">Qtd. Geolocation <i class="fas fa-info-circle" data-toggle="tooltip" title="Quantidade de solicitações para busca da Latitude/Longitude que o cliente pode solicitar."></i></label>
                            <input type="text" class="form-control {{($errors->first('qtd_geolocation') ? 'form-error-field' : '')}}" id="qtd_geolocation" name="qtd_geolocation" value="{{old('qtd_geolocation')}}" placeholder="Qtd. Geolocation" required>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                </div>
                <!-- Dados GoogleMaps - FIM -->

                <!-- Dados Acesso - INI -->
                <div class="bg-soft-primary p-3 rounded" style="margin-bottom:10px;">
                    <h5 class="text-primary font-size-14" style="margin-bottom: 0px;">Dados Acesso</h5>
                </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="situacao" class="{{($errors->first('situacao') ? 'form-error-label' : '')}}">Situação</label>
                                <select id="situacao" name="situacao" class="form-control {{($errors->first('situacao') ? 'form-error-field' : '')}}" required>
                                    <option value="A" {{(old('situacao') == 'A') ? 'selected' : '' }}>Ativo</option>
                                    <option value="I" {{(old('situacao') == 'I') ? 'selected' : '' }}>Inativo</option>
                                </select>
                                <div class="valid-feedback">ok!</div>
                                <div class="invalid-feedback">Inválido!</div>
                            </div>
                        </div>
                    </div>
                <!-- Dados Acesso -- FIM -->

                <button class="btn btn-primary" type="submit">Salvar Cadastro</button>
            </form>


            <!-- FORMULÁRIO - FIM -->
            </div>
        </div>
    </div>
</div>

@endsection

@section('head-css')
    <link href="{{asset('nazox/assets/libs/select2/css/select2.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />
@endsection

@section('script-js')
    <script src="{{asset('nazox/assets/js/pages/form-validation.init.js')}}"></script>
    <script src="{{asset('nazox/assets/libs/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
    <script src="{{asset('nazox/assets/js/pages/form-element.init.js')}}"></script>
    <script src="{{asset('nazox/assets/libs/select2/js/select2.min.js')}}"></script>

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
