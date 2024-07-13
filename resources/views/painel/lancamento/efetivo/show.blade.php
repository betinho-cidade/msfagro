@extends('painel.layout.index')


@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Lançamento do Efetivo Pecuário do Cliente</h4>
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

            <h4 class="card-title">Formulário de Atualização - Lançamento de Efetivo Pecuário</h4>
            @if($user->roles->contains('name', 'Cliente'))
            <span style="float: right">
                <a href="{{route('efetivo.index', ['mes_referencia' => $efetivo->mes_referencia_listagem])}}"><i class="nav-icon fas fa-arrow-left" style="color: goldenrod; font-size: 14px;margin-right: 4px;" title="Efetivos Pecuários do mês: {{$efetivo->mes_referencia_listagem}}"></i></a>
                <a href="{{route('painel')}}"><i class="nav-icon fas fa-home" style="color: goldenrod; font-size: 14px;margin-right: 4px;" title="Home"></i></a>
            </span>
            @endif
            <p class="card-title-desc">O Lançamento registrado estará disponível para os movimentos no sistema.</p>
            
            @if($user->roles->contains('name', 'Cliente'))
                @can('edit_efetivo')
                <form name="edit_efetivo" method="POST" action="{{route('efetivo.update', compact('efetivo'))}}"  class="needs-validation"  accept-charset="utf-8" enctype="multipart/form-data" novalidate>
                    @csrf
                    @method('PUT')
                @endcan
            @endif

                <input type="hidden" id="tipo" name="tipo" value="{{ $efetivo->tipo }}">

                <div class="bg-soft-primary p-3 rounded" style="margin-bottom:10px;">
                    <h5 class="text-primary font-size-14" style="margin-bottom: 0px;">Dados do Lançamento do Efetivo Pecuário</h5>
                </div>

                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="tipo">Tipo Movimentação</label>
                            <input style="background-color: #D3D3D3;" type="text" class="form-control" value="{{$efetivo->tipo_efetivo_texto}}" disabled>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="categoria">Categoria</label>
                            <input style="background-color: #D3D3D3;" type="text" class="form-control" value="{{$efetivo->categoria->nome}}" disabled>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="empresa">Empresa</label>
                            <textarea style="background-color: #D3D3D3;" type="text" class="form-control" disabled>{{$efetivo->empresa->nome_empresa ?? '...'}}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="item_macho">Classificação Machos</label>
                            <input style="background-color: #D3D3D3;" type="text" class="form-control" value="{{$efetivo->classificacao_macho}}" disabled>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="qtd_macho">Qtd. Machos</label>
                            <input style="background-color: #D3D3D3;" type="text" class="form-control" value="{{$efetivo->qtd_macho}}" disabled>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="item_femea">Classificação Fêmeas</label>
                            <input style="background-color: #D3D3D3;" type="text" class="form-control" value="{{$efetivo->classificacao_femea}}" disabled>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="qtd_femea">Qtd. Fêmeas</label>
                            <input style="background-color: #D3D3D3;" type="text" class="form-control" value="{{$efetivo->qtd_femea}}" disabled>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="gta">Número GTA</label>
                            <input type="text" class="form-control" id="gta" name="gta" value="{{$efetivo->gta}}" placeholder="Número GTA" required>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="path_gta" class="{{($errors->first('path_gta') ? 'form-error-label' : '')}}">GTA (imagem/pdf)
                            @if($efetivo->path_gta)
                            <a href="{{ route('efetivo.download', ['efetivo' => $efetivo->id, 'tipo_documento' => 'GT']) }}">
                                <i class="mdi mdi-file-download mdi-18px" style="color: goldenrod;cursor: pointer" title="Download da GTA"></i>
                            </a>
                            @endif
                        </label>
                        <div class="form-group custom-file">
                            <input type="file" class="custom-file-input {{($errors->first('path_gta') ? 'form-error-field' : '')}}" id="path_gta" name="path_gta" accept="image/*, application/pdf" {{($efetivo->path_gta) ? '' : 'required'}}>
                            <label class="custom-file-label" for="path_gta">Selecionar GTA</label>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="origem">Origem</label>
                            <textarea style="background-color: #D3D3D3;" type="text" class="form-control" disabled>{{$efetivo->origem->nome_fazenda ?? '...'}}</textarea>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="destino">Destino</label>
                            <textarea style="background-color: #D3D3D3;" type="text" class="form-control" disabled>{{$efetivo->destino->nome_fazenda ?? '...'}}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="data_programada">Data Programada</label>
                            <input type="date" class="form-control" id="data_programada" name="data_programada" value="{{$efetivo->data_programada_ajustada}}" placeholder="Data Programada" required>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>

                    @if($efetivo->tipo != 'EG')
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="data_pagamento" class="{{($errors->first('data_pagamento') ? 'form-error-label' : '')}}">Data Pagamento</label>
                            <input type="date" id="data_pagamento" name="data_pagamento" value="{{$efetivo->movimentacao->data_pagamento_ajustada}}" placeholder="Data Pagamento" class="form-control {{($errors->first('data_pagamento') ? 'form-error-field' : '')}}">
                        </div>
                    </div>
                    @endif

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="item_texto">Item Fiscal</label>
                            <textarea style="background-color: #D3D3D3;" type="text" class="form-control" disabled>{{$efetivo->movimentacao->item_texto ?? '...'}}</textarea>
                        </div>
                    </div>
                    <div class="col-md-{{($efetivo->tipo != 'EG') ? '4' : '6' }}">
                        <div class="form-group">
                            <label for="observacao">Observações</label>
                            <textarea class="form-control" id="observacao" name="observacao" placeholder="Observação">{{$efetivo->observacao}}</textarea>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                </div>

                @if($efetivo->tipo != 'EG')
                <br>
                <div class="bg-soft-primary p-3 rounded" style="margin-bottom:10px;">
                    <h5 class="text-primary font-size-14" style="margin-bottom: 0px;">Dados do Pagamento para Movimentação Fiscal</h5>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="produtor">Produtor</label>
                            <textarea style="background-color: #D3D3D3;" type="text" class="form-control" disabled>{{$efetivo->movimentacao->produtor->nome_produtor ?? '...'}}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                        <label for="forma_pagamento" class="{{($errors->first('forma_pagamento') ? 'form-error-label' : '')}}">Forma Pagamento 
                        @can('edit_forma_pagamento')    
                            <a href="{{ route('forma_pagamento.create') }}" target="_blank"><i class="fas fa-plus-circle" style="color: goldenrod; margin-left: 5px; vertical-align: middle;" title="Nova Forma de Pagamento"></i></a> <i onclick="refreshList('FP');" class="fas fa-sync-alt" style="color: goldenrod; margin-left: 5px; vertical-align: middle;" title="Atualizar Forma de Pagamentos"></i></label>
                        @endcan
                            <img src="{{asset('images/loading.gif')}}" id="img-loading-forma_pagamento" style="display:none;max-width: 20px; margin-left: 12px;">
                            <select id="forma_pagamento" name="forma_pagamento" class="form-control {{($errors->first('forma_pagamento') ? 'form-error-field' : '')}} select2" required>                            
                                <option value="">---</option>
                                @foreach($forma_pagamentos as $forma_pagamento)
                                    <option value="{{ $forma_pagamento->id }}" {{($efetivo->movimentacao->forma_pagamento->id == $forma_pagamento->id) ? 'selected' : '' }}>{{ $forma_pagamento->forma }}</option>
                                @endforeach
                            </select>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>                    
                </div>

                <br>

                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="valor" class="{{($errors->first('valor') ? 'form-error-label' : '')}}">Valor</label>
                            <input type="text" style="background-color: {{ $efetivo->tipo == 'EG' ? '#D3D3D3' : 'white' }};" class="form-control {{($errors->first('valor') ? 'form-error-field' : '')}}" id="valor" name="valor" value="{{$efetivo->movimentacao->valor ?? ''}}" placeholder="Valor" onInput="mascaraMoeda(event);" {{ $efetivo->tipo == 'EG' ? 'disabled' : 'required' }}>    
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="path_comprovante" class="{{($errors->first('path_comprovante') ? 'form-error-label' : '')}}">Comprovante Pagamento (imagem/pdf)
                            @if($efetivo->movimentacao && $efetivo->movimentacao->path_comprovante)
                            <a href="{{ route('efetivo.download', ['efetivo' => $efetivo->id, 'tipo_documento' => 'CP']) }}">
                                <i class="mdi mdi-file-download mdi-18px" style="color: goldenrod;cursor: pointer" title="Download do Comprovante de Pagamento"></i>
                            </a>
                            @endif
                        </label>
                        <div class="form-group custom-file">
                            <input type="file" class="custom-file-input {{($errors->first('path_comprovante') ? 'form-error-field' : '')}}" id="path_comprovante" name="path_comprovante" accept="image/*, application/pdf" {{ $efetivo->tipo == 'EG' ? 'disabled' : '' }}>
                            <label style="background-color: {{ $efetivo->tipo == 'EG' ? '#D3D3D3' : 'white' }};"  id="path_comprovante_lbl" class="custom-file-label" for="path_comprovante">Selecionar Comprovante</label>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="nota" class="{{($errors->first('nota') ? 'form-error-label' : '')}}">Doc. Comprobatório</label>
                            <input type="text" style="background-color: {{ $efetivo->tipo == 'EG' ? '#D3D3D3' : 'white' }};" class="form-control {{($errors->first('nota') ? 'form-error-field' : '')}}" id="nota" name="nota" value="{{$efetivo->movimentacao->nota ?? '...'}}" placeholder="Número Nota" {{ $efetivo->tipo == 'EG' ? 'disabled' : '' }}>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="path_nota" class="{{($errors->first('path_nota') ? 'form-error-label' : '')}}">Doc. Comprobatório (imagem/pdf)
                            @if($efetivo->movimentacao && $efetivo->movimentacao->path_nota)
                            <a href="{{ route('efetivo.download', ['efetivo' => $efetivo->id, 'tipo_documento' => 'NT']) }}">
                                <i class="mdi mdi-file-download mdi-18px" style="color: goldenrod;cursor: pointer" title="Download da Nota"></i>
                            </a>
                            @endif
                        </label>
                        <div class="form-group custom-file">
                            <input type="file" class="custom-file-input {{($errors->first('path_nota') ? 'form-error-field' : '')}}" id="path_nota" name="path_nota" accept="image/*, application/pdf" {{ $efetivo->tipo == 'EG' ? 'disabled' : '' }}>
                            <label style="background-color: {{ $efetivo->tipo == 'EG' ? '#D3D3D3' : 'white' }};" id="path_nota_lbl" class="custom-file-label" for="path_nota">Selecionar Nota</label>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                </div>

                <div class="bg-soft-primary p-3 rounded" style="margin-bottom:10px;">
                    <h5 class="text-primary font-size-14" style="margin-bottom: 0px;">Outros anexos</h5>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label for="path_anexo" class="{{($errors->first('path_anexo') ? 'form-error-label' : '')}}">Anexo (imagem/pdf/xls/doc)
                            @if($efetivo->movimentacao && $efetivo->movimentacao->path_anexo)
                            <a href="{{ route('efetivo.download', ['efetivo' => $efetivo->id, 'tipo_documento' => 'AN']) }}">
                                <i class="mdi mdi-file-download mdi-18px" style="color: goldenrod;cursor: pointer" title="Download do Anexo"></i>
                            </a>
                            @endif
                        </label>
                        <div class="form-group custom-file">
                            <input type="file" class="custom-file-input {{($errors->first('path_anexo') ? 'form-error-field' : '')}}" id="path_anexo" name="path_anexo"  accept="image/*,application/pdf,.doc,.docx,.xml,.xls,.xlsx,application/msword" {{ $efetivo->tipo == 'EG' ? 'disabled' : '' }}>
                            <label style="background-color: {{ $efetivo->tipo == 'EG' ? '#D3D3D3' : 'white' }};"  id="path_anexo_lbl" class="custom-file-label" for="path_anexo">Selecionar Anexo</label>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                </div>                
                <br>
                @endif

            <!-- Dados Pessoais -- FIM -->
            @if($user->roles->contains('name', 'Cliente'))
                @can('edit_efetivo')
                    <button class="btn btn-primary" type="submit">Salvar Cadastro</button>
                </form>
                @endcan
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
    <script>
    @if($user->roles->contains('name', 'Cliente'))

        $(document).ready(function(){
            $('#valor').trigger('input');  
        });

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

        const mascaraMoeda = (event) => {
            const onlyDigits = event.target.value
                .split("")
                .filter(s => /\d/.test(s))
                .join("")
                .padStart(3, "0")
            const digitsFloat = onlyDigits.slice(0, -2) + "." + onlyDigits.slice(-2)
            event.target.value = maskCurrency(digitsFloat)
        }

        const maskCurrency = (valor, locale = 'pt-BR', currency = 'BRL') => {
            return new Intl.NumberFormat(locale, {
                style: 'currency',
                currency
            }).format(valor)
        }           

    @endif

    </script>

@endsection



