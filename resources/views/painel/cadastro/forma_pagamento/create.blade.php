@extends('painel.layout.index')


@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Nova Forma de Pagamento para o Cliente</h4>
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

            <h4 class="card-title">Formulário de Cadastro - Forma de Pagamento</h4>
            <p class="card-title-desc">A Forma de Pagamento cadastrada estará disponível para os lançamentos no sistema.</p>
            <form name="create_forma_pagamento" method="POST" action="{{route('forma_pagamento.store')}}"  class="needs-validation"  accept-charset="utf-8" enctype="multipart/form-data" novalidate>
                @csrf

                <div class="bg-soft-primary p-3 rounded" style="margin-bottom:10px;">
                    <h5 class="text-primary font-size-14" style="margin-bottom: 0px;">Dados da Forma de Pagamento</h5>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tipo_conta" class="{{($errors->first('tipo_conta') ? 'form-error-label' : '')}}">Tipo Conta</label>
                            <select id="tipo_conta" name="tipo_conta" class="form-control {{($errors->first('tipo_conta') ? 'form-error-field' : '')}}" required>
                                <option value="">---</option>
                                <option value="CC" {{(old('tipo_conta') == 'CC') ? 'selected' : '' }}>Conta Corrente</option>
                                <option value="CP" {{(old('tipo_conta') == 'CP') ? 'selected' : '' }}>Conta Poupança</option>
                                <option value="NT" {{(old('tipo_conta') == 'NT') ? 'selected' : '' }}>Numerário em Trânsito</option>
                                <option value="BL" {{(old('tipo_conta') == 'BL') ? 'selected' : '' }}>Boleto</option>
                                <option value="ES" {{(old('tipo_conta') == 'ES') ? 'selected' : '' }}>Espécie</option>
                            </select>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="form-group">
                            <label for="produtor" class="{{($errors->first('produtor') ? 'form-error-label' : '')}}">Produtor</label>
                            <select id="produtor" name="produtor" class="form-control {{($errors->first('produtor') ? 'form-error-field' : '')}} select2">
                                <option value="">---</option>
                                @foreach($produtors as $produtor)
                                    <option value="{{ $produtor->id }}" {{(old('produtor') == $produtor->id) ? 'selected' : '' }}>{{ $produtor->nome }} / {{ $produtor->cpf_cnpj }}</option>
                                @endforeach
                                <div class="valid-feedback">ok!</div>
                                <div class="invalid-feedback">Inválido!</div>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="situacao" class="{{($errors->first('situacao') ? 'form-error-label' : '')}}">Situação</label>
                            <select id="situacao" name="situacao" class="form-control {{($errors->first('situacao') ? 'form-error-field' : '')}}" required>
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
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="banco" class="{{($errors->first('banco') ? 'form-error-label' : '')}}">Banco</label>
                            <input type="text" class="form-control {{($errors->first('banco') ? 'form-error-field' : '')}}" id="banco" name="banco" value="{{old('banco')}}" placeholder="Banco">
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="agencia" class="{{($errors->first('agencia') ? 'form-error-label' : '')}}">Agência</label>
                            <input type="text" class="form-control {{($errors->first('agencia') ? 'form-error-field' : '')}}" id="agencia" name="agencia" value="{{old('agencia')}}" placeholder="Agência">
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="conta" class="{{($errors->first('conta') ? 'form-error-label' : '')}}">Conta</label>
                            <input type="text" class="form-control {{($errors->first('conta') ? 'form-error-field' : '')}}" id="conta" name="conta" value="{{old('conta')}}" placeholder="Conta">
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="pix" class="{{($errors->first('pix') ? 'form-error-label' : '')}}">Pix</label>
                            <input type="text" class="form-control {{($errors->first('pix') ? 'form-error-field' : '')}}" id="pix" name="pix" value="{{old('pix')}}" placeholder="Pix">
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
    <script src="{{asset('nazox/assets/libs/select2/js/select2.min.js')}}"></script>
    <!-- form mask -->
    <script src="{{asset('nazox/assets/libs/inputmask/jquery.inputmask.min.js')}}"></script>

    <script>
		$(document).ready(function(){
            $('.select2').select2();
		});
	</script>

@endsection

@section('head-css')
    <link href="{{asset('nazox/assets/libs/select2/css/select2.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />
@endsection
