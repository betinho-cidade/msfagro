@extends('painel.layout.index')


@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Distribuição de Lucro do Cliente</h4>
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

            <h4 class="card-title">Formulário de Atualização - Distribuição de Lucro</h4>
                <span style="float: right">
                    <a href="{{route('lucro.index')}}"><i class="nav-icon fas fa-arrow-left" style="color: goldenrod; font-size: 14px;margin-right: 4px;" title="Distribuição de Lucros"></i></a>
                    <a href="{{route('painel')}}"><i class="nav-icon fas fa-home" style="color: goldenrod; font-size: 14px;margin-right: 4px;" title="Home"></i></a>
                </span>

            <p class="card-title-desc">A Distribuição de Lucro estará disponível no sistema.</p>
                <form name="edit_lucro" method="POST" action="{{route('lucro.update', compact('lucro'))}}"  class="needs-validation"  accept-charset="utf-8" enctype="multipart/form-data" novalidate>
                    @csrf
                    @method('PUT')

                <div class="bg-soft-primary p-3 rounded" style="margin-bottom:10px;">
                    <h5 class="text-primary font-size-14" style="margin-bottom: 0px;">Dados da Distribuição de Lucro</h5>
                </div>

                <div class="row">
                    <div class="col-md-6">
                         <div class="form-group">
                            <label for="produtor" class="{{($errors->first('produtor') ? 'form-error-label' : '')}}">Produtor <a href="{{ route('produtor.create') }}" target="_blank"><i class="fas fa-plus-circle" style="color: goldenrod; margin-left: 5px; vertical-align: middle;" title="Novo Produtor"></i></a> <i onclick="refreshList('PT');" class="fas fa-sync-alt" style="color: goldenrod; margin-left: 5px; vertical-align: middle;" title="Atualizar Produtores"></i></label>
                            <img src="{{asset('images/loading.gif')}}" id="img-loading-produtor" style="display:none;max-width: 20px; margin-left: 12px;">
                            <select id="produtor" name="produtor" class="form-control {{($errors->first('produtor') ? 'form-error-field' : '')}} dynamic_produtor select2" required>
                                <option value="">---</option>
                                    @foreach($produtors as $produtor)
                                        <option value="{{ $produtor->id }}" {{($lucro->produtor_id == $produtor->id) ? 'selected' : '' }}>{{ $produtor->nome_produtor }}</option>
                                    @endforeach
                            </select>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>                    

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="forma_pagamento" class="{{($errors->first('forma_pagamento') ? 'form-error-label' : '')}}">Forma Pagamento<a href="{{ route('forma_pagamento.create') }}" target="_blank"><i class="fas fa-plus-circle" style="color: goldenrod; margin-left: 5px; vertical-align: middle;" title="Nova Forma de Pagamento"></i></a> <i onclick="refreshList('FP');" class="fas fa-sync-alt" style="color: goldenrod; margin-left: 5px; vertical-align: middle;" title="Atualizar Forma de Pagamentos"></i></label>
                            <img src="{{asset('images/loading.gif')}}" id="img-loading-forma_pagamento" style="display:none;max-width: 20px; margin-left: 12px;">
                            <select id="forma_pagamento" name="forma_pagamento" class="form-control {{($errors->first('forma_pagamento') ? 'form-error-field' : '')}} select2" required>
                                <option value="">---</option>
                                    @foreach($forma_pagamentos as $forma_pagamento)
                                        <option value="{{ $forma_pagamento->id }}" {{($lucro->forma_pagamento_id == $forma_pagamento->id) ? 'selected' : '' }}>{{ $forma_pagamento->forma }}</option>
                                    @endforeach                                
                            </select>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <label for="data_lancamento">Data Lançamento</label>
                        <input type="date" class="form-control" id="data_programada" name="data_lancamento" value="{{$lucro->data_lancamento_ajustada}}" placeholder="Data Lançamento" required>
                        <div class="valid-feedback">ok!</div>
                        <div class="invalid-feedback">Inválido!</div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="valor" class="{{($errors->first('valor') ? 'form-error-label' : '')}}">Valor</label>
                            <input type="hidden" class="form-control" id="valor" name="valor" value="">
                            <input type="text" class="form-control updValor mask_valor {{($errors->first('valor') ? 'form-error-field' : '')}}" id="valor_view" name="valor_view" value="{{$lucro->valor}}" placeholder="Valor" required>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>       

                    <div class="col-md-4">
                        <label for="path_comprovante" class="{{($errors->first('path_comprovante') ? 'form-error-label' : '')}}">Comprovante (imagem/pdf)
                            @if($lucro->path_comprovante)
                            <a href="{{ route('lucro.download', ['lucro' => $lucro->id]) }}">
                                <i class="mdi mdi-file-download mdi-18px" style="color: goldenrod;cursor: pointer" title="Download do Comprovante"></i>
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
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="observacao">Observações</label>
                            <textarea class="form-control" rows="5" id="observacao" name="observacao" placeholder="Observação">{{$lucro->observacao}}</textarea>
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
                valor_new = valor_new.replace('R$ ', '').replace('.', '');
                valor.value = valor_new;
            }
        });

        $('.dynamic_produtor').change(function(){
            refreshList('FP');
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

    function refreshList(tipo) {

        var _token = $('input[name="_token"]').val();
        var _tipo = tipo;
        var objectList;
        var objectName;
        var objectValue;

        if(tipo == 'FP'){
            objectList = $('#forma_pagamento');
            objectName = 'forma_pagamento';
            objectValue = document.getElementById("produtor").value;
        }

        if(tipo == 'PT'){
            objectList = $('#produtor');
            objectName = 'produtor';
            objectValue = '';
        }

        document.getElementById("img-loading-"+objectName).style.display = '';

        $.ajax({
            url: "{{route('lucro.refreshList')}}",
            method: "POST",
            dataType: "json",
            data: {_token:_token, tipo:_tipo, produtor:objectValue},
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
                    objectList.find('option').not(':first').remove();
                    document.getElementById("img-loading-"+objectName).style.display = 'none';
                }
            },
            error:function(erro){
                document.getElementById("img-loading-"+objectName).style.display = 'none';
            }
        })
    }

    </script>

@endsection

