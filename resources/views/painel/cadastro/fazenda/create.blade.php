@extends('painel.layout.index')


@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Nova Fazenda para o Cliente</h4>
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

            <h4 class="card-title">Formulário de Cadastro - Fazenda</h4>
            <p class="card-title-desc">A Fazenda cadastrada estará disponível para os lançamentos no sistema.</p>
            <form name="create_fazenda" method="POST" action="{{route('fazenda.store')}}"  class="needs-validation"  accept-charset="utf-8" enctype="multipart/form-data" novalidate>
                @csrf

                <input type="hidden" id="tipo_cliente" name="tipo_cliente" value="{{$user->cliente->tipo}}">

                <div class="bg-soft-primary p-3 rounded" style="margin-bottom:10px;">
                    <h5 class="text-primary font-size-14" style="margin-bottom: 0px;">Dados da Fazenda</h5>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="nome" class="{{($errors->first('nome') ? 'form-error-label' : '')}}">Nome</label>
                            <input type="text" class="form-control {{($errors->first('nome') ? 'form-error-field' : '')}}" id="nome" name="nome" value="{{old('nome')}}" placeholder="Nome" required>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label for="end_cep" class="{{($errors->first('end_cep') ? 'form-error-label' : '')}}">CEP</label>
                        <img src="{{asset('images/loading.gif')}}" id="img-loading-cep" style="display:none;max-width: 17%; margin-left: 26px;">
                        <input type="text" name="end_cep" id="end_cep" class="form-control {{($errors->first('end_cep') ? 'form-error-field' : '')}} dynamic_cep mask_cep" value="{{old('end_cep')}}" placeholder="99.999-999">
                    </div>

                    <div class="col-md-3">
                        <label for="end_cidade" class="{{($errors->first('end_cidade') ? 'form-error-label' : '')}}">Cidade</label>
                        <input type="text" name="end_cidade" id="end_cidade" class="form-control {{($errors->first('end_cidade') ? 'form-error-field' : '')}}" value="{{old('end_cidade')}}">
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="end_uf" class="{{($errors->first('end_uf') ? 'form-error-label' : '')}}">Estado/UF</label>
                            <select id="end_uf" name="end_uf" class="form-control {{($errors->first('end_uf') ? 'form-error-field' : '')}}">
                                <option value="">---</option>
                                <option value="AC" {{(old('end_uf') == 'AC') ? 'selected' : '' }}>Acre</option>
                                <option value="AL" {{(old('end_uf') == 'AL') ? 'selected' : '' }}>Alagoas</option>
                                <option value="AP" {{(old('end_uf') == 'AP') ? 'selected' : '' }}>Amapá</option>
                                <option value="AM" {{(old('end_uf') == 'AM') ? 'selected' : '' }}>Amazonas</option>
                                <option value="BA" {{(old('end_uf') == 'BA') ? 'selected' : '' }}>Bahia</option>
                                <option value="CE" {{(old('end_uf') == 'CE') ? 'selected' : '' }}>Ceará</option>
                                <option value="DF" {{(old('end_uf') == 'DF') ? 'selected' : '' }}>Distrito Federal</option>
                                <option value="ES" {{(old('end_uf') == 'ES') ? 'selected' : '' }}>Espirito Santo</option>
                                <option value="GO" {{(old('end_uf') == 'GO') ? 'selected' : '' }}>Goiás</option>
                                <option value="MA" {{(old('end_uf') == 'MA') ? 'selected' : '' }}>Maranhão</option>
                                <option value="MS" {{(old('end_uf') == 'MS') ? 'selected' : '' }}>Mato Grosso do Sul</option>
                                <option value="MT" {{(old('end_uf') == 'MT') ? 'selected' : '' }}>Mato Grosso</option>
                                <option value="MG" {{(old('end_uf') == 'MG') ? 'selected' : '' }}>Minas Gerais</option>
                                <option value="PA" {{(old('end_uf') == 'PA') ? 'selected' : '' }}>Pará</option>
                                <option value="PB" {{(old('end_uf') == 'PB') ? 'selected' : '' }}>Paraíba</option>
                                <option value="PR" {{(old('end_uf') == 'PR') ? 'selected' : '' }}>Paraná</option>
                                <option value="PE" {{(old('end_uf') == 'PE') ? 'selected' : '' }}>Pernambuco</option>
                                <option value="PI" {{(old('end_uf') == 'PI') ? 'selected' : '' }}>Piauí</option>
                                <option value="RJ" {{(old('end_uf') == 'RJ') ? 'selected' : '' }}>Rio de Janeiro</option>
                                <option value="RN" {{(old('end_uf') == 'RN') ? 'selected' : '' }}>Rio Grande do Norte</option>
                                <option value="RS" {{(old('end_uf') == 'RS') ? 'selected' : '' }}>Rio Grande do Sul</option>
                                <option value="RO" {{(old('end_uf') == 'RO') ? 'selected' : '' }}>Rondônia</option>
                                <option value="RR" {{(old('end_uf') == 'RR') ? 'selected' : '' }}>Roraima</option>
                                <option value="SC" {{(old('end_uf') == 'SC') ? 'selected' : '' }}>Santa Catarina</option>
                                <option value="SP" {{(old('end_uf') == 'SP') ? 'selected' : '' }}>São Paulo</option>
                                <option value="SE" {{(old('end_uf') == 'SE') ? 'selected' : '' }}>Sergipe</option>
                                <option value="TO" {{(old('end_uf') == 'TO') ? 'selected' : '' }}>Tocantins</option>
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
                            <input type="text" class="form-control {{($errors->first('latitude') ? 'form-error-field' : '')}}" id="latitude" name="latitude" value="{{old('latitude')}}" placeholder="Latitude">
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="longitude" class="{{($errors->first('longitude') ? 'form-error-label' : '')}}">Longitude</label>
                            <input type="text" class="form-control {{($errors->first('longitude') ? 'form-error-field' : '')}}" id="longitude" name="longitude" value="{{old('longitude')}}" placeholder="Longitude">
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>

                    @if($user->cliente && $user->cliente->tipo != 'AG')
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="qtd_macho" class="{{($errors->first('qtd_macho') ? 'form-error-label' : '')}}">Qtd. Machos</label>
                            <input type="text" class="form-control {{($errors->first('qtd_macho') ? 'form-error-field' : '')}}" id="qtd_macho" name="qtd_macho" value="{{old('qtd_macho')}}" placeholder="Qtd. Macho" required>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="qtd_femea" class="{{($errors->first('qtd_femea') ? 'form-error-label' : '')}}">Qtd. Fêmeas</label>
                            <input type="text" class="form-control {{($errors->first('qtd_femea') ? 'form-error-field' : '')}}" id="qtd_femea" name="qtd_femea" value="{{old('qtd_femea')}}" placeholder="Qtd. Fêmea" required>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                    @endif

                    <div class="col-md-2">
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
