@extends('painel.layout.index')


@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Lançamento da Movimentação Fiscal do Cliente</h4>
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

            <h4 class="card-title">Formulário de Atualização - Lançamento da Movimentação Fiscal</h4>
            @if($user->roles->contains('name', 'Cliente'))
                <span style="float: right">
                    <a href="{{route('movimentacao.index', ['mes_referencia' => $movimentacao->mes_referencia_listagem])}}"><i class="nav-icon fas fa-arrow-left" style="color: goldenrod; font-size: 14px;margin-right: 4px;" title="Movimentações Financeiras do mês: {{$movimentacao->mes_referencia_listagem}}"></i></a>
                    <a href="{{route('painel')}}"><i class="nav-icon fas fa-home" style="color: goldenrod; font-size: 14px;margin-right: 4px;" title="Home"></i></a>
                </span>
            @endif

            <p class="card-title-desc">O Lançamento registrado estará disponível para os movimentos no sistema.</p>
            @if($user->roles->contains('name', 'Cliente'))
                <form name="edit_movimentacao" method="POST" action="{{route('movimentacao.update', compact('movimentacao'))}}"  class="needs-validation"  accept-charset="utf-8" enctype="multipart/form-data" novalidate>
                    @csrf
                    @method('PUT')
            @endif

                <input type="hidden" id="tipo" name="tipo" value="{{ $movimentacao->tipo }}">

                <div class="bg-soft-primary p-3 rounded" style="margin-bottom:10px;">
                    <h5 class="text-primary font-size-14" style="margin-bottom: 0px;">Dados da Movimentação Fiscal</h5>
                </div>

                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="tipo">Tipo Movimentação</label>
                                <input style="background-color: #D3D3D3;" type="text" class="form-control" value="{{$movimentacao->tipo_movimentacao_texto}}" disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="categoria">Categoria</label>
                                <input style="background-color: #D3D3D3;" type="text" class="form-control" value="{{$movimentacao->categoria->nome}}" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="empresa" class="{{($errors->first('empresa') ? 'form-error-label' : '')}}">Empresa 
                                @if($user->roles->contains('name', 'Cliente'))
                                    <a href="{{ route('empresa.create') }}" target="_blank"><i class="fas fa-plus-circle" style="color: goldenrod; margin-left: 5px; vertical-align: middle;" title="Nova Empresa"></i></a> <i onclick="refreshList('EP');" class="fas fa-sync-alt" style="color: goldenrod; margin-left: 5px; vertical-align: middle;" title="Atualizar Empresas"></i>
                                    <img src="{{asset('images/loading.gif')}}" id="img-loading-empresa" style="display:none;max-width: 20px; margin-left: 12px;">
                                @endif
                                </label>
                                <select id="empresa" name="empresa" class="form-control {{($errors->first('empresa') ? 'form-error-field' : '')}} select2" required>
                                    <option value="">---</option>
                                    @foreach($empresas as $empresa)
                                        <option value="{{ $empresa->id }}" {{($movimentacao->empresa_id == $empresa->id) ? 'selected' : '' }}>{{ $empresa->nome_empresa }}</option>
                                    @endforeach
                                </select>
                                <div class="valid-feedback">ok!</div>
                                <div class="invalid-feedback">Inválido!</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2">
                            <label for="data_programada">Data Programada</label>
                            <input type="date" class="form-control" id="data_programada" name="data_programada" value="{{$movimentacao->data_programada_ajustada}}" placeholder="Data Programada" required>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="data_pagamento" class="{{($errors->first('data_pagamento') ? 'form-error-label' : '')}}">Data Pagamento</label>
                                <input type="date" id="data_pagamento" name="data_pagamento" value="{{$movimentacao->data_pagamento_ajustada}}" placeholder="Data Pagamento" class="form-control {{($errors->first('data_pagamento') ? 'form-error-field' : '')}}">
                            </div>
                        </div>                        

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="item_texto" class="{{($errors->first('item_texto') ? 'form-error-label' : '')}}">Item Fiscal</label>
                                <textarea class="form-control {{($errors->first('item_texto') ? 'form-error-field' : '')}}" id="item_texto" name="item_texto" placeholder="Item Fiscal" required>{{$movimentacao->item_texto}}</textarea>
                                <div class="valid-feedback">ok!</div>
                                <div class="invalid-feedback">Inválido!</div>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="observacao">Observações</label>
                                <textarea class="form-control" id="observacao" name="observacao" placeholder="Observação">{{$movimentacao->observacao}}</textarea>
                                <div class="valid-feedback">ok!</div>
                                <div class="invalid-feedback">Inválido!</div>
                            </div>
                        </div>
                    </div>

                    <br>

                    <div class="bg-soft-primary p-3 rounded" style="margin-bottom:10px;">
                        <h5 class="text-primary font-size-14" style="margin-bottom: 0px;">Dados do Pagamento para Movimentação Fiscal</h5>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="produtor">Produtor</label>
                                <textarea style="background-color: #D3D3D3;" type="text" class="form-control" disabled>{{$movimentacao->produtor->nome_produtor ?? '...'}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="forma_pagamento">Forma Pagamento</label>
                                <textarea style="background-color: #D3D3D3;" type="text" class="form-control" disabled>{{$movimentacao->forma_pagamento->forma ?? '...'}}</textarea>
                            </div>
                        </div>
                    </div>

                    <br>

                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="valor" class="{{($errors->first('valor') ? 'form-error-label' : '')}}">Valor</label>
                                <input type="hidden" class="form-control" id="valor" name="valor" value="{{$movimentacao->valor}}">
                                <input type="text" class="form-control updValor mask_valor {{($errors->first('valor') ? 'form-error-field' : '')}}" id="valor_view" name="valor_view" value="{{$movimentacao->valor}}" placeholder="Valor" required>
                                <div class="valid-feedback">ok!</div>
                                <div class="invalid-feedback">Inválido!</div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="path_comprovante" class="{{($errors->first('path_comprovante') ? 'form-error-label' : '')}}">Comprovante Pagamento (imagem/pdf)
                                @if($movimentacao->path_comprovante)
                                <a href="{{ route('movimentacao.download', ['movimentacao' => $movimentacao->id, 'tipo_documento' => 'CP']) }}">
                                    <i class="mdi mdi-file-download mdi-18px" style="color: goldenrod;cursor: pointer" title="Download do Comprovante de Pagamento"></i>
                                </a>
                                @endif
                            </label>
                            <div class="form-group custom-file">
                                <input type="file" class="custom-file-input {{($errors->first('path_comprovante') ? 'form-error-field' : '')}}" id="path_comprovante" name="path_comprovante" accept="image/*, application/pdf">
                                <label id="path_comprovante_lbl" class="custom-file-label" for="path_comprovante">Selecionar Comprovante</label>
                                <div class="valid-feedback">ok!</div>
                                <div class="invalid-feedback">Inválido!</div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="nota" class="{{($errors->first('nota') ? 'form-error-label' : '')}}">Doc. Comprobatório</label>
                                <input type="text" class="form-control {{($errors->first('nota') ? 'form-error-field' : '')}}" id="nota" name="nota" value="{{$movimentacao->nota}}" placeholder="Número Nota" required>
                                <div class="valid-feedback">ok!</div>
                                <div class="invalid-feedback">Inválido!</div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="path_nota" class="{{($errors->first('path_nota') ? 'form-error-label' : '')}}">Doc. Comprobatório (imagem/pdf)
                                @if($movimentacao->path_nota)
                                <a href="{{ route('movimentacao.download', ['movimentacao' => $movimentacao->id, 'tipo_documento' => 'NT']) }}">
                                    <i class="mdi mdi-file-download mdi-18px" style="color: goldenrod;cursor: pointer" title="Download da Nota"></i>
                                </a>
                                @endif
                            </label>
                            <div class="form-group custom-file">
                                <input type="file" class="custom-file-input {{($errors->first('path_nota') ? 'form-error-field' : '')}}" id="path_nota" name="path_nota" accept="image/*, application/pdf">
                                <label id="path_nota_lbl" class="custom-file-label" for="path_nota">Selecionar</label>
                                <div class="valid-feedback">ok!</div>
                                <div class="invalid-feedback">Inválido!</div>
                            </div>
                        </div>
                    </div>

                <!-- Dados Pessoais -- FIM -->
            @if($user->roles->contains('name', 'Cliente'))
                    <button class="btn btn-primary" type="submit">Salvar Cadastro</button>
                </form>
            @endif

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

        formatValorMoeda('valor');
        formatValorMoeda('valor_view');

        $('.updValor').change(function(){
            let valor_view = document.getElementById('valor_view');
            let valor = document.getElementById('valor');

            if(valor_view && valor_view.value){
                valor_new = valor_view.value;
                valor_new = valor_new.replace('R$ ', '').replace('.', '');
                valor.value = valor_new;
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

    @if($user->roles->contains('name', 'Cliente'))
        function refreshList(tipo) {

            var _token = $('input[name="_token"]').val();
            var _tipo = tipo;
            var objectList;
            var objectName;

            if(tipo == 'EP'){
                objectList = $('#empresa');
                objectName = 'empresa';
            }

            if(tipo == 'FP'){
                objectList = $('#forma_pagamento');
                objectName = 'forma_pagamento';
            }

            if(tipo == 'PT'){
                objectList = $('#produtor');
                objectName = 'produtor';
            }

            document.getElementById("img-loading-"+objectName).style.display = '';

            $.ajax({
                url: "{{route('lancamento.refreshList')}}",
                method: "POST",
                dataType: "json",
                data: {_token:_token, tipo:_tipo},
                success:function(response){

                    var len = 0;
                    if (response.mensagem != null) {
                        len = response.mensagem.length;
                    }

                    if (len>0) {
                        objectList.find('option').not(':first').remove();
                        for (var i = 0; i<len; i++) {
                            var id = response.mensagem[i].id;
                            var nome = response.mensagem[i].nome;
                            var option = "<option value='"+id+"'>"+nome+"</option>";
                            objectList.append(option);
                        }
                        document.getElementById("img-loading-"+objectName).style.display = 'none';
                    } else {
                        document.getElementById("img-loading-"+objectName).style.display = 'none';
                    }
                },
                error:function(erro){
                    document.getElementById("img-loading-"+objectName).style.display = 'none';
                }
            })
        }
    @endif

    </script>

@endsection

