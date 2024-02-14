@extends('painel.layout.index')


@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Novo Lançamento de Movimentação Fiscal do Cliente</h4>
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

            <h4 class="card-title">Formulário de Cadastro - Lançamento de Movimentação Fiscal</h4>
            <p class="card-title-desc">O Lançamento cadastrado estará disponível para os movimentos no sistema.</p>
            <form name="create_movimentacao" method="POST" action="{{route('movimentacao.store')}}"  class="needs-validation" accept-charset="utf-8" enctype="multipart/form-data" novalidate>
                @csrf

                <div class="bg-soft-primary p-3 rounded" style="margin-bottom:10px;">
                    <h5 class="text-primary font-size-14" style="margin-bottom: 0px;">Dados da Movimentação Fiscal</h5>
                </div>

                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="tipo" class="{{($errors->first('tipo') ? 'form-error-label' : '')}}">Tipo Movimentação</label>
                            <select id="tipo" name="tipo" class="form-control dynamic_tipo {{($errors->first('tipo') ? 'form-error-field' : '')}}" required>
                                <option value="">---</option>
                                <option value="R" {{(old('tipo') == 'R') ? 'selected' : '' }}>Receita</option>
                                <option value="D" {{(old('tipo') == 'D') ? 'selected' : '' }}>Despesa</option>
                            </select>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="categoria" class="{{($errors->first('categoria') ? 'form-error-label' : '')}}">Categoria</label>
                            <img src="{{asset('images/loading.gif')}}" id="img-loading-categoria" style="display:none;max-width: 20px; margin-left: 12px;">
                            <select id="categoria" name="categoria" class="form-control {{($errors->first('categoria') ? 'form-error-field' : '')}} select2 dynamic_categoria" required>
                                <option value="">---</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}" {{(old('categoria') == $categoria->id) ? 'selected' : '' }}>{{ $categoria->nome }}</option>
                                @endforeach
                            </select>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="empresa" class="{{($errors->first('empresa') ? 'form-error-label' : '')}}">Empresa <a href="{{ route('empresa.create') }}" target="_blank"><i class="fas fa-plus-circle" style="color: goldenrod; margin-left: 5px; vertical-align: middle;" title="Nova Empresa"></i></a> <i onclick="refreshList('EP');" class="fas fa-sync-alt" style="color: goldenrod; margin-left: 5px; vertical-align: middle;" title="Atualizar Empresas"></i></label>
                            <img src="{{asset('images/loading.gif')}}" id="img-loading-empresa" style="display:none;max-width: 20px; margin-left: 12px;">
                            <select id="empresa" name="empresa" class="form-control {{($errors->first('empresa') ? 'form-error-field' : '')}} select2" required>
                                <option value="">---</option>
                                @foreach($empresas as $empresa)
                                    <option value="{{ $empresa->id }}" {{(old('empresa') == $empresa->id) ? 'selected' : '' }}>{{ $empresa->nome_empresa }}</option>
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
                        <input type="date" class="form-control" id="data_programada" name="data_programada" value="{{old('data_programada')}}" placeholder="Data Programada" required>
                        <div class="valid-feedback">ok!</div>
                        <div class="invalid-feedback">Inválido!</div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="data_pagamento" class="{{($errors->first('data_pagamento') ? 'form-error-label' : '')}}">Data Pagamento</label>
                            <input type="date" id="data_pagamento" name="data_pagamento" value="{{old('data_pagamento')}}" placeholder="Data Pagamento" class="form-control {{($errors->first('data_pagamento') ? 'form-error-field' : '')}}" value="">
                        </div>
                    </div>                    

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="item_texto" class="{{($errors->first('item_texto') ? 'form-error-label' : '')}}">Item Fiscal</label>
                            <textarea class="form-control {{($errors->first('item_texto') ? 'form-error-field' : '')}}" id="item_texto" name="item_texto" placeholder="Item Fiscal" required>{{old('item_texto')}}</textarea>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="observacao">Observações</label>
                            <textarea class="form-control" id="observacao" name="observacao" placeholder="Observação">{{old('observacao')}}</textarea>
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
                            <label for="produtor" class="{{($errors->first('produtor') ? 'form-error-label' : '')}}">Produtor <a href="{{ route('produtor.create') }}" target="_blank"><i class="fas fa-plus-circle" style="color: goldenrod; margin-left: 5px; vertical-align: middle;" title="Novo Produtor"></i></a> <i onclick="refreshList('PT');" class="fas fa-sync-alt" style="color: goldenrod; margin-left: 5px; vertical-align: middle;" title="Atualizar Produtores"></i></label>
                            <img src="{{asset('images/loading.gif')}}" id="img-loading-produtor" style="display:none;max-width: 20px; margin-left: 12px;">
                            <select id="produtor" name="produtor" class="form-control {{($errors->first('produtor') ? 'form-error-field' : '')}} select2" required>
                                <option value="">---</option>
                                @foreach($produtors as $produtor)
                                    <option value="{{ $produtor->id }}" {{(old('produtor') == $produtor->id) ? 'selected' : '' }}>{{ $produtor->nome_produtor }}</option>
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
                                    <option value="{{ $forma_pagamento->id }}" {{(old('forma_pagamento') == $forma_pagamento->id) ? 'selected' : '' }}>{{ $forma_pagamento->forma }}</option>
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
                            <label for="valor" class="{{($errors->first('valor') ? 'form-error-label' : '')}}">Valor (R$)</label>
                            <input type="number" class="form-control {{($errors->first('valor') ? 'form-error-field' : '')}}" id="valor" name="valor" min="0.01" step="0.01" value="{{old('valor')}}" placeholder="Valor" required>                            
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="path_comprovante" class="{{($errors->first('path_comprovante') ? 'form-error-label' : '')}}">Comprovante Pagamento (imagem/pdf)</label>
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
                            <input type="text" class="form-control {{($errors->first('nota') ? 'form-error-field' : '')}}" id="nota" name="nota" value="{{old('nota')}}" placeholder="Número Nota" required>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="path_nota" class="{{($errors->first('path_nota') ? 'form-error-label' : '')}}">Doc. Comprobatório (imagem/pdf)</label>
                        <div class="form-group custom-file">
                            <input type="file" class="custom-file-input {{($errors->first('path_nota') ? 'form-error-field' : '')}}" id="path_nota" name="path_nota" accept="image/*, application/pdf" required>
                            <label id="path_nota_lbl" class="custom-file-label" for="path_nota">Selecionar</label>
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
                        <label for="path_anexo" class="{{($errors->first('path_anexo') ? 'form-error-label' : '')}}">Anexo (imagem/pdf/xls/doc)</label>
                        <div class="form-group custom-file">
                            <input type="file" class="custom-file-input {{($errors->first('path_anexo') ? 'form-error-field' : '')}}" id="path_anexo" name="path_anexo" accept="image/*,application/pdf,.doc,.docx,.xml,.xls,.xlsx,application/msword">
                            <label id="path_anexo_lbl" class="custom-file-label" for="path_anexo">Selecionar Anexo</label>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                </div>                

                <br>

            <!-- Dados Pessoais -- FIM -->

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

    <script>
    $(document).ready(function(){
        $('.select2').select2();

        $('.dynamic_tipo').change(function(){
                console.log('entrou');   
                let valor_tipo = document.getElementById('tipo').value;

                console.log(valor_tipo);

                if(valor_tipo == 'D'){
                    refreshList('CD');

                } else if(valor_tipo == 'R'){
                    refreshList('CR');
                }                
        });   
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

        if(tipo == 'CR'){
            objectList = $('#categoria');
            objectName = 'categoria';
        }        

        if(tipo == 'CD'){
            objectList = $('#categoria');
            objectName = 'categoria';
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
    </script>

@endsection

