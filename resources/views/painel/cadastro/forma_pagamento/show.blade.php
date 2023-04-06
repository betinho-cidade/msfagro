@extends('painel.layout.index')


@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Informações da Forma de Pagamento</h4>
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

            <h4 class="card-title">Formulário de Atualização - Forma de Pagamento {{$forma_pagamento->tipo_conta_texto}}</h4>
            <p class="card-title-desc">A Forma de Pagamento cadastrada estará disponível para os lançamentos no sistema.</p>

            <form name="edit_forma_pagamento" method="POST" action="{{route('forma_pagamento.update', compact('forma_pagamento'))}}"  class="needs-validation" accept-charset="utf-8" enctype="multipart/form-data" novalidate>
                @csrf
                @method('PUT')

                <!-- Dados Pessoais - INI -->
                <div class="bg-soft-primary p-3 rounded" style="margin-bottom:10px;">
                    <h5 class="text-primary font-size-14" style="margin-bottom: 0px;">Dados Forma de Pagamento</h5>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tipo_conta">Tipo Conta</label>
                            <select id="tipo_conta" name="tipo_conta" class="form-control" required>
                                <option value="">---</option>
                                <option value="CC" {{($forma_pagamento->tipo_conta == 'CC') ? 'selected' : '' }}>Conta Corrente</option>
                                <option value="CP" {{($forma_pagamento->tipo_conta == 'CP') ? 'selected' : '' }}>Conta Poupança</option>
                                <option value="PX" {{($forma_pagamento->tipo_conta == 'PX') ? 'selected' : '' }}>Pix</option>
                                <option value="BL" {{($forma_pagamento->tipo_conta == 'BL') ? 'selected' : '' }}>Boleto</option>
                                <option value="ES" {{($forma_pagamento->tipo_conta == 'ES') ? 'selected' : '' }}>Espécie</option>
                            </select>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="titular">Titular</label>
                            <input type="text" class="form-control" id="titular" name="titular" value="{{$forma_pagamento->titular}}" placeholder="Titular" required>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="doc_titular">Documento do Titular</label>
                            <input type="text" class="form-control" id="doc_titular" name="doc_titular" value="{{$forma_pagamento->doc_titular}}" placeholder="Documento do Titular" required>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="situacao">Situação</label>
                            <select id="situacao" name="situacao" class="form-control" required>
                                <option value="">---</option>
                                <option value="A" {{($forma_pagamento->status == 'A') ? 'selected' : '' }}>Ativo</option>
                                <option value="I" {{($forma_pagamento->status == 'I') ? 'selected' : '' }}>Inativo</option>
                            </select>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="banco">Banco</label>
                            <input type="text" class="form-control" id="banco" name="banco" value="{{$forma_pagamento->banco}}" placeholder="Banco">
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="agencia">Agência</label>
                            <input type="text" class="form-control" id="agencia" name="agencia" value="{{$forma_pagamento->agencia}}" placeholder="Agência">
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="conta">Conta</label>
                            <input type="text" class="form-control" id="conta" name="conta" value="{{$forma_pagamento->conta}}" placeholder="Conta">
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="pix">Pix</label>
                            <input type="text" class="form-control" id="pix" name="pix" value="{{$forma_pagamento->pix}}" placeholder="Pix">
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

    <script>
		$(document).ready(function(){
            $('.select2').select2();
		});
	</script>

@endsection

@section('head-css')
    <link href="{{asset('nazox/assets/libs/magnific-popup/magnific-popup.css')}}" rel="stylesheet" type="text/css" />
@endsection
