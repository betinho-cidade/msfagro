@extends('painel.layout.index')


@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Informações das Visualizações Google Maps</h4>
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

            <h4 class="card-title">Formulário - Visualizações Google Maps</h4>
            <p class="card-title-desc">As Visualizações do Google Maps cadastradas estarão disponíveis no sistema.</p>

            <form name="edit_googlemap" method="POST" action="{{route('googlemap.update', compact('googlemap'))}}"  class="needs-validation" accept-charset="utf-8" enctype="multipart/form-data" novalidate>
                @csrf
                @method('PUT')

                <!-- Dados Pessoais - INI -->
                <div class="bg-soft-primary p-3 rounded" style="margin-bottom:10px;">
                    <h5 class="text-primary font-size-14" style="margin-bottom: 0px;">Dados Google Maps</h5>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="valor_credito" class="{{($errors->first('valor_credito') ? 'form-error-label' : '')}}">Valor Crédito (U$) <i class="fas fa-info-circle" data-toggle="tooltip" title="Crédito mensal em dólar (U$ {{$googlemap->valor_credito}}) disponibilizado pela Google. A partir do consumo deste valor, será cobrado valores adicionais a cada 1.000 solicitações."></i></label>
                            <input type="text" class="form-control {{($errors->first('valor_credito') ? 'form-error-field' : '')}}" id="valor_credito" name="valor_credito" value="{{$googlemap->valor_credito}}" placeholder="Valor Crédito" required>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="valor_extra_apimaps" class="{{($errors->first('valor_extra_apimaps') ? 'form-error-label' : '')}}">Extra API (U$) <i class="fas fa-info-circle" data-toggle="tooltip" title="Valor adicional cobrado em dólar (U$ {{$googlemap->valor_extra_apimaps}}) a cada 1.000 solicitações. Essas solicitações estão relacionadas com visualização do Mapa (API Maps)."></i></label>
                            <input type="text" class="form-control {{($errors->first('valor_extra_apimaps') ? 'form-error-field' : '')}}" id="valor_extra_apimaps" name="valor_extra_apimaps" value="{{$googlemap->valor_extra_apimaps}}" placeholder="Valor Extra API Maps" required>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="qtd_apimaps" class="{{($errors->first('qtd_apimaps') ? 'form-error-label' : '')}}">Qtd. API <i class="fas fa-info-circle" data-toggle="tooltip" title="Quantidade de solicitações disponíveis por mês ({{$googlemap->qtd_apimaps}}), para serem distribuídas os clientes da MFSAgro. Estimando 50 clientes, o valor contemplaria cerca de ({{$googlemap->qtd_apimaps / 50}}) solicitações para cada um dos 50 clientes."></i></label>
                            <input type="text" class="form-control {{($errors->first('qtd_apimaps') ? 'form-error-field' : '')}}" id="qtd_apimaps" name="qtd_apimaps" value="{{$googlemap->qtd_apimaps}}" placeholder="Qtd. API Maps" required>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="valor_extra_geolocation" class="{{($errors->first('valor_extra_geolocation') ? 'form-error-label' : '')}}">Extra Geo (U$) <i class="fas fa-info-circle" data-toggle="tooltip" title="Valor adicional cobrado em dólar (U$ {{$googlemap->valor_extra_geolocation}}) a cada 1.000 solicitações. Essas solicitações estão relacionadas com a busca da Latitude / Longitude de forma automatizada (Geolocation)."></i></label>
                            <input type="text" class="form-control {{($errors->first('valor_extra_geolocation') ? 'form-error-field' : '')}}" id="valor_extra_geolocation" name="valor_extra_geolocation" value="{{$googlemap->valor_extra_geolocation}}" placeholder="Valor Extra Geolocation" required>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="qtd_geolocation" class="{{($errors->first('qtd_geolocation') ? 'form-error-label' : '')}}">Qtd. Geolocation <i class="fas fa-info-circle" data-toggle="tooltip" title="Quantidade de solicitações disponíveis por mês ({{$googlemap->qtd_geolocation}}), para serem distribuídas aos clientes da MFSAgro. Estimando 50 clientes, o valor contemplaria cerca de ({{$googlemap->qtd_geolocation / 50}}) solicitações para cada um dos 50 clientes."></i></label>
                            <input type="text" class="form-control {{($errors->first('qtd_geolocation') ? 'form-error-field' : '')}}" id="qtd_geolocation" name="qtd_geolocation" value="{{$googlemap->qtd_geolocation}}" placeholder="Qtd. Geolocation" required>
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

    <script src="{{asset('nazox/assets/libs/magnific-popup/jquery.magnific-popup.min.js')}}"></script>
    <script src="{{asset('nazox/assets/js/pages/lightbox.init.js')}}"></script>
@endsection

@section('head-css')
@endsection
