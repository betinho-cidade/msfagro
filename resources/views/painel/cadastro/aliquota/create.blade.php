@extends('painel.layout.index')


@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Nova Alíquota do Sistema</h4>
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

            <h4 class="card-title">Formulário de Cadastro - Alíquota</h4>
            <p class="card-title-desc">As Alíquotas cadastradas estarão disponíveies no sistema.</p>
            <form name="create_aliquota" method="POST" action="{{route('aliquota.store')}}"  class="needs-validation"  accept-charset="utf-8" enctype="multipart/form-data" novalidate>
                @csrf

                <!-- Dados Pessoais - INI -->
                <div class="bg-soft-primary p-3 rounded" style="margin-bottom:10px;">
                    <h5 class="text-primary font-size-14" style="margin-bottom: 0px;">Dados Alíquota</h5>
                </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="base_inicio" class="{{($errors->first('base_inicio') ? 'form-error-label' : '')}}">Base Inicial</label>
                                <input type="hidden" class="form-control" id="base_inicio" name="base_inicio" value="">
                                <input type="text" class="form-control updParcela mask_valor {{($errors->first('base_inicio') ? 'form-error-field' : '')}}" id="base_inicio_view" name="base_inicio_view" value="{{old('base_inicio_view')}}" placeholder="Base Início" required>
                                <div class="valid-feedback">ok!</div>
                                <div class="invalid-feedback">Inválido!</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="base_fim" class="{{($errors->first('base_fim') ? 'form-error-label' : '')}}">Base Fim</label>
                                <input type="hidden" class="form-control" id="base_fim" name="base_fim" value="">
                                <input type="text" class="form-control updParcela mask_valor {{($errors->first('base_fim') ? 'form-error-field' : '')}}" id="base_fim_view" name="base_fim_view" value="{{old('base_fim_view')}}" placeholder="Base Fim" required>
                                <div class="valid-feedback">ok!</div>
                                <div class="invalid-feedback">Inválido!</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="aliquota" class="{{($errors->first('aliquota') ? 'form-error-label' : '')}}">Alíquota</label>
                                <input type="hidden" class="form-control" id="aliquota" name="aliquota" value="">
                                <input type="text" class="form-control updParcela mask_aliquota {{($errors->first('aliquota') ? 'form-error-field' : '')}}" id="aliquota_view" name="aliquota_view" value="{{old('aliquota_view')}}" placeholder="Alíquota" required>
                                <div class="valid-feedback">ok!</div>
                                <div class="invalid-feedback">Inválido!</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="parcela_deducao" class="{{($errors->first('parcela_deducao') ? 'form-error-label' : '')}}">Parcela Dedução</label>
                                <input type="hidden" class="form-control" id="parcela_deducao" name="parcela_deducao" value="">
                                <input type="text" class="form-control updParcela mask_valor {{($errors->first('parcela_deducao') ? 'form-error-field' : '')}}" id="parcela_deducao_view" name="parcela_deducao_view" value="{{old('parcela_deducao_view')}}" placeholder="Parcela Dedução" required>
                                <div class="valid-feedback">ok!</div>
                                <div class="invalid-feedback">Inválido!</div>
                            </div>
                        </div>
                    </div>

                <!-- Dados Pessoais -- FIM -->

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

    <!-- form mask -->
    <script src="{{asset('js/jquery.maskMoney.min.js')}}"></script>

    <script>
    $(document).ready(function(){

        $('.mask_valor').maskMoney({
            prefix:'R$ ',
            allowNegative: false,
            thousands:'.',
            decimal:',',
            precision: 2,
            affixesStay: true
        });

        $('.mask_aliquota').maskMoney({
            prefix:'% ',
            allowNegative: false,
            thousands:'.',
            decimal:',',
            precision: 2,
            affixesStay: true
        });

        formatValorMoeda('base_inicio');
        formatValorMoeda('base_inicio_view');
        formatValorMoeda('base_fim');
        formatValorMoeda('base_fim_view');
        formatValorMoeda('parcela_deducao');
        formatValorMoeda('parcela_deducao_view');

        formatValorAliquota('aliquota');
        formatValorAliquota('aliquota_view');

        $('.updParcela').change(function(){
            let base_inicio_view = document.getElementById('base_inicio_view');
            let base_inicio = document.getElementById('base_inicio');
            let base_fim_view = document.getElementById('base_fim_view');
            let base_fim = document.getElementById('base_fim');
            let aliquota_view = document.getElementById('aliquota_view');
            let aliquota = document.getElementById('aliquota');
            let parcela_deducao_view = document.getElementById('parcela_deducao_view');
            let parcela_deducao = document.getElementById('parcela_deducao');

            if(base_inicio_view && base_inicio_view.value){
                base_inicio_new = base_inicio_view.value;
                base_inicio_new = base_inicio_new.replace('R$ ', '').replace('.', '');
                base_inicio_new = base_inicio_new.replace('R$ ', '').replace('.', '');
                base_inicio.value = base_inicio_new;
            }

            if(base_fim_view && base_fim_view.value){
                base_fim_new = base_fim_view.value;
                base_fim_new = base_fim_new.replace('R$ ', '').replace('.', '');
                base_fim_new = base_fim_new.replace('R$ ', '').replace('.', '');
                base_fim.value = base_fim_new;
            }

            if(aliquota_view && aliquota_view.value){
                aliquota_new = aliquota_view.value;
                aliquota_new = aliquota_new.replace('% ', '').replace('.', '');
                aliquota_new = aliquota_new.replace('% ', '').replace('.', '');
                aliquota.value = aliquota_new;
            }

            if(parcela_deducao_view && parcela_deducao_view.value){
                parcela_deducao_new = parcela_deducao_view.value;
                parcela_deducao_new = parcela_deducao_new.replace('R$ ', '').replace('.', '');
                parcela_deducao_new = parcela_deducao_new.replace('R$ ', '').replace('.', '');
                parcela_deducao.value = parcela_deducao_new;
            }
        });
    });

    function formatValorMoeda(field){
        let element =  document.getElementById(field);

        if(element && element.value){
            valueFormatted = parseFloat(element.value.replace('R$ ', '').replace(',', '.')).toFixed(2).replace('.', ',');
            document.getElementById(field).value = valueFormatted;

            $('#'+field).trigger('select');
        }
    }

    function formatValorAliquota(field){
        let element =  document.getElementById(field);

        if(element && element.value){
            valueFormatted = parseFloat(element.value.replace('% ', '').replace(',', '.')).toFixed(2).replace('.', ',');
            document.getElementById(field).value = valueFormatted;

            $('#'+field).trigger('select');
        }
    }
    </script>

@endsection
