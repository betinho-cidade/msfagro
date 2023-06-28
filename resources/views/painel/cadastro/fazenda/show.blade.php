@extends('painel.layout.index')


@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Informações da Fazenda</h4>
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

            <h4 class="card-title">Formulário de Atualização - Fazenda {{$fazenda->nome}}</h4>
            <p class="card-title-desc">A Fazenda cadastrada estará disponível para os lançamentos no sistema.</p>

            <form name="edit_fazenda" method="POST" action="{{route('fazenda.update', compact('fazenda'))}}"  class="needs-validation" accept-charset="utf-8" enctype="multipart/form-data" novalidate>
                @csrf
                @method('PUT')

                <input type="hidden" id="tipo_cliente" name="tipo_cliente" value="{{$user->cliente->tipo}}">

                <!-- Dados Pessoais - INI -->
                <div class="bg-soft-primary p-3 rounded" style="margin-bottom:10px;">
                    <h5 class="text-primary font-size-14" style="margin-bottom: 0px;">Dados da Fazenda</h5>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="nome" class="{{($errors->first('nome') ? 'form-error-label' : '')}}">Nome</label>
                            <input type="text" class="form-control {{($errors->first('nome') ? 'form-error-field' : '')}}" id="nome" name="nome" value="{{$fazenda->nome}}" placeholder="Nome" required>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label for="end_cep" class="{{($errors->first('end_cep') ? 'form-error-label' : '')}}">CEP</label>
                        <img src="{{asset('images/loading.gif')}}" id="img-loading-cep" style="display:none;max-width: 17%; margin-left: 26px;">
                        <input type="text" name="end_cep" id="end_cep" class="form-control {{($errors->first('end_cep') ? 'form-error-field' : '')}} dynamic_cep mask_cep" value="{{$fazenda->end_cep}}" placeholder="99.999-999">
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="end_cidade" class="{{($errors->first('end_cidade') ? 'form-error-label' : '')}}">Cidade</label>
                            <input type="text" class="form-control {{($errors->first('end_cidade') ? 'form-error-field' : '')}}" id="end_cidade" name="end_cidade" value="{{$fazenda->end_cidade}}" placeholder="Cidade">
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="end_uf" class="{{($errors->first('end_uf') ? 'form-error-label' : '')}}">Estado/UF</label>
                            <select id="end_uf" name="end_uf" class="form-control {{($errors->first('end_uf') ? 'form-error-field' : '')}}">
                                <option value="">---</option>
                                <option value="AC" {{($fazenda->end_uf == 'AC') ? 'selected' : '' }}>Acre</option>
                                <option value="AL" {{($fazenda->end_uf == 'AL') ? 'selected' : '' }}>Alagoas</option>
                                <option value="AP" {{($fazenda->end_uf == 'AP') ? 'selected' : '' }}>Amapá</option>
                                <option value="AM" {{($fazenda->end_uf == 'AM') ? 'selected' : '' }}>Amazonas</option>
                                <option value="BA" {{($fazenda->end_uf == 'BA') ? 'selected' : '' }}>Bahia</option>
                                <option value="CE" {{($fazenda->end_uf == 'CE') ? 'selected' : '' }}>Ceará</option>
                                <option value="DF" {{($fazenda->end_uf == 'DF') ? 'selected' : '' }}>Distrito Federal</option>
                                <option value="ES" {{($fazenda->end_uf == 'ES') ? 'selected' : '' }}>Espirito Santo</option>
                                <option value="GO" {{($fazenda->end_uf == 'GO') ? 'selected' : '' }}>Goiás</option>
                                <option value="MA" {{($fazenda->end_uf == 'MA') ? 'selected' : '' }}>Maranhão</option>
                                <option value="MS" {{($fazenda->end_uf == 'MS') ? 'selected' : '' }}>Mato Grosso do Sul</option>
                                <option value="MT" {{($fazenda->end_uf == 'MT') ? 'selected' : '' }}>Mato Grosso</option>
                                <option value="MG" {{($fazenda->end_uf == 'MG') ? 'selected' : '' }}>Minas Gerais</option>
                                <option value="PA" {{($fazenda->end_uf == 'PA') ? 'selected' : '' }}>Pará</option>
                                <option value="PB" {{($fazenda->end_uf == 'PB') ? 'selected' : '' }}>Paraíba</option>
                                <option value="PR" {{($fazenda->end_uf == 'PR') ? 'selected' : '' }}>Paraná</option>
                                <option value="PE" {{($fazenda->end_uf == 'PE') ? 'selected' : '' }}>Pernambuco</option>
                                <option value="PI" {{($fazenda->end_uf == 'PI') ? 'selected' : '' }}>Piauí</option>
                                <option value="RJ" {{($fazenda->end_uf == 'RJ') ? 'selected' : '' }}>Rio de Janeiro</option>
                                <option value="RN" {{($fazenda->end_uf == 'RN') ? 'selected' : '' }}>Rio Grande do Norte</option>
                                <option value="RS" {{($fazenda->end_uf == 'RS') ? 'selected' : '' }}>Rio Grande do Sul</option>
                                <option value="RO" {{($fazenda->end_uf == 'RO') ? 'selected' : '' }}>Rondônia</option>
                                <option value="RR" {{($fazenda->end_uf == 'RR') ? 'selected' : '' }}>Roraima</option>
                                <option value="SC" {{($fazenda->end_uf == 'SC') ? 'selected' : '' }}>Santa Catarina</option>
                                <option value="SP" {{($fazenda->end_uf == 'SP') ? 'selected' : '' }}>São Paulo</option>
                                <option value="SE" {{($fazenda->end_uf == 'SE') ? 'selected' : '' }}>Sergipe</option>
                                <option value="TO" {{($fazenda->end_uf == 'TO') ? 'selected' : '' }}>Tocantins</option>
                            </select>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="latitude" class="{{($errors->first('latitude') ? 'form-error-label' : '')}}">Latitude</label>
                            <input type="text" class="form-control {{($errors->first('latitude') ? 'form-error-field' : '')}}" id="latitude" name="latitude" value="{{$fazenda->latitude}}" placeholder="Latitude" required>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="longitude" class="{{($errors->first('longitude') ? 'form-error-label' : '')}}">Longitude</label>
                            <input type="text" class="form-control {{($errors->first('longitude') ? 'form-error-field' : '')}}" id="longitude" name="longitude" value="{{$fazenda->longitude}}" placeholder="Longitude" required>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>                    

                    @if($user->cliente && $user->cliente->tipo != 'AG')
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="qtd_macho" class="{{($errors->first('qtd_macho') ? 'form-error-label' : '')}}">Qtd. Machos</label>
                            <input type="text" class="form-control {{($errors->first('qtd_macho') ? 'form-error-field' : '')}}" id="qtd_macho" name="qtd_macho" value="{{$fazenda->qtd_macho}}" placeholder="Qtd. Macho" required>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="qtd_femea" class="{{($errors->first('qtd_femea') ? 'form-error-label' : '')}}">Qtd. Fêmeas</label>
                            <input type="text" class="form-control {{($errors->first('qtd_femea') ? 'form-error-field' : '')}}" id="qtd_femea" name="qtd_femea" value="{{$fazenda->qtd_femea}}" placeholder="Qtd. Fêmea" required>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                    @endif

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="situacao" class="{{($errors->first('situacao') ? 'form-error-label' : '')}}">Situação</label>
                            <select id="situacao" name="situacao" class="form-control {{($errors->first('situacao') ? 'form-error-field' : '')}}" required>
                                <option value="">---</option>
                                <option value="A" {{($fazenda->status == 'A') ? 'selected' : '' }}>Ativo</option>
                                <option value="I" {{($fazenda->status == 'I') ? 'selected' : '' }}>Inativo</option>
                            </select>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                </div>

                <!-- Dados Pessoais -- FIM -->

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

    <script>
		$(document).ready(function(){
            $('.select2').select2();
			$('.mask_cep').inputmask('99.999-999');
		});
	</script>

    <script type='text/javascript'>
        $(document).ready(function(){
            $('.dynamic_cep').change(function(){

                if ($(this).val() != ''){
                    document.getElementById("img-loading-cep").style.display = '';

                    var cep = $('#end_cep').val();
                    var _token = $('input[name="_token"]').val();

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

@endsection
