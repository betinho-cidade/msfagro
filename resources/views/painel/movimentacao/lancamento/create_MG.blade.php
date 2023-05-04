@extends('painel.layout.index')


@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Novo Lançamento de Movimentação Bovina para o Cliente</h4>
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

            <h4 class="card-title">Formulário de Cadastro - Lançamento de Movimentação Bovina</h4>
            <p class="card-title-desc">O Lançamento cadastrado estará disponível para os movimentos no sistema.</p>
            <form name="create_lancamento" method="POST" action="{{route('lancamento.store_MG')}}"  class="needs-validation"  accept-charset="utf-8" enctype="multipart/form-data" novalidate>
                @csrf

                <div class="bg-soft-primary p-3 rounded" style="margin-bottom:10px;">
                    <h5 class="text-primary font-size-14" style="margin-bottom: 0px;">Dados do Lançamento de Movimentação Bovina</h5>
                </div>

                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="tipo" class="{{($errors->first('tipo') ? 'form-error-label' : '')}}">Tipo Movimentação</label>
                            <select id="tipo" name="tipo" class="form-control {{($errors->first('tipo') ? 'form-error-field' : '')}} dynamic_tipo" required>
                                <option value="">---</option>
                                <option value="CP" {{(old('tipo') == 'CP') ? 'selected' : '' }}>Compra</option>
                                <option value="VD" {{(old('tipo') == 'VD') ? 'selected' : '' }}>Venda</option>
                                <option value="EG" {{(old('tipo') == 'EG') ? 'selected' : '' }}>Engorda</option>
                            </select>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="categoria" class="{{($errors->first('categoria') ? 'form-error-label' : '')}}">Categoria</label>
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
                            <label for="empresa" class="{{($errors->first('empresa') ? 'form-error-label' : '')}}">Empresa</label>
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
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="item_macho" class="{{($errors->first('item_macho') ? 'form-error-label' : '')}}">Classificação Machos</label>
                            <select id="item_macho" name="item_macho" class="form-control {{($errors->first('item_macho') ? 'form-error-field' : '')}} select2" required>
                                <option value="">---</option>
                                <option value="M1" {{(old('item_macho') == 'M1') ? 'selected' : '' }}>Macho de 0 à 12 meses</option>
                                <option value="M2" {{(old('item_macho') == 'M2') ? 'selected' : '' }}>Macho de 12 à 24 meses</option>
                                <option value="M3" {{(old('item_macho') == 'M3') ? 'selected' : '' }}>Macho de 25 à 36 meses</option>
                                <option value="M4" {{(old('item_macho') == 'M4') ? 'selected' : '' }}>Macho acima de 36 meses</option>
                            </select>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="qtd_macho">Qtd. Machos</label>
                            <input type="text" class="form-control" id="qtd_macho" name="qtd_macho" value="{{old('qtd_macho')}}" placeholder="Qtd. Machos" required>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="item_femea" class="{{($errors->first('item_femea') ? 'form-error-label' : '')}}">Classificação Fêmeas</label>
                            <select id="item_femea" name="item_femea" class="form-control {{($errors->first('item_femea') ? 'form-error-field' : '')}} select2" required>
                                <option value="">---</option>
                                <option value="F1" {{(old('item_femea') == 'F1') ? 'selected' : '' }}>Fêmea de 0 à 2 meses</option>
                                <option value="F2" {{(old('item_femea') == 'F2') ? 'selected' : '' }}>Fêmea de 12 à 24 meses</option>
                                <option value="F3" {{(old('item_femea') == 'F3') ? 'selected' : '' }}>Fêmea de 25 à 36 meses</option>
                                <option value="F4" {{(old('item_femea') == 'F4') ? 'selected' : '' }}>Fêmea acima de 36 meses</option>
                            </select>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="qtd_femea">Qtd. Fêmeas</label>
                            <input type="text" class="form-control" id="qtd_femea" name="qtd_femea" value="{{old('qtd_femea')}}" placeholder="Qtd. Fêmeas" required>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="gta">Número GTA</label>
                            <input type="text" class="form-control" id="gta" name="gta" value="{{old('gta')}}" placeholder="Número GTA">
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="path_gta" class="{{($errors->first('path_gta') ? 'form-error-label' : '')}}">GTA (imagem/pdf)</label>
                        <div class="form-group custom-file">
                            <input type="file" class="custom-file-input {{($errors->first('path_gta') ? 'form-error-field' : '')}}" id="path_gta" name="path_gta" accept="image/*, application/pdf">
                            <label class="custom-file-label" for="path_gta">Selecionar GTA</label>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label id="origem_lbl" for="origem" class="{{($errors->first('origem') ? 'form-error-label' : '')}}">Origem</label>
                            <select id="origem" name="origem" class="form-control {{($errors->first('origem') ? 'form-error-field' : '')}} select2" required>
                                <option value="">---</option>
                                @foreach($produtors as $produtor)
                                    <option value="{{ $produtor->id }}" {{(old('produtor') == $produtor->id) ? 'selected' : '' }}>{{ $produtor->nome_produtor }}</option>
                                @endforeach
                            </select>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label id="destino_lbl" for="destino" class="{{($errors->first('destino') ? 'form-error-label' : '')}}">Destino</label>
                            <select id="destino" name="destino" class="form-control {{($errors->first('destino') ? 'form-error-field' : '')}} select2" required>
                                <option value="">---</option>
                                @foreach($produtors as $produtor)
                                    <option value="{{ $produtor->id }}" {{(old('produtor') == $produtor->id) ? 'selected' : '' }}>{{ $produtor->nome_produtor }}</option>
                                @endforeach
                            </select>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                </div>

                <br>

                <div class="bg-soft-primary p-3 rounded" style="margin-bottom:10px;">
                    <h5 class="text-primary font-size-14" style="margin-bottom: 0px;">Dados do Pagamento</h5>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <label for="data_programada">Data Programada</label>
                        <input type="date" class="form-control" id="data_programada" name="data_programada" value="{{old('data_programada')}}" placeholder="Data Programada" required>
                        <div class="valid-feedback">ok!</div>
                        <div class="invalid-feedback">Inválido!</div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="produtor" class="{{($errors->first('produtor') ? 'form-error-label' : '')}}">Produtor</label>
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
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="forma_pagamento" class="{{($errors->first('forma_pagamento') ? 'form-error-label' : '')}}">Forma Pagamento</label>
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
                            <label for="valor" class="{{($errors->first('valor') ? 'form-error-label' : '')}}">Valor</label>
                            <input type="hidden" class="form-control" id="valor" name="valor" value="">
                            <input type="text" class="form-control updValor mask_valor {{($errors->first('valor') ? 'form-error-field' : '')}}" id="valor_view" name="valor_view" value="{{old('valor_view')}}" placeholder="Valor" required>
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
                            <label for="documento">Número Nota</label>
                            <input type="text" class="form-control" id="documento" name="documento" value="{{old('documento')}}" placeholder="Número Nota">
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="path_documento" class="{{($errors->first('path_documento') ? 'form-error-label' : '')}}">Nota Fiscal (imagem/pdf)</label>
                        <div class="form-group custom-file">
                            <input type="file" class="custom-file-input {{($errors->first('path_documento') ? 'form-error-field' : '')}}" id="path_documento" name="path_documento" accept="image/*, application/pdf">
                            <label class="custom-file-label" for="path_documento">Selecionar Nota</label>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                </div>

                <br>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="observacao">Observações</label>
                            <textarea rows="3" class="form-control" id="observacao" name="observacao" placeholder="Observação">{{old('observacao')}}</textarea>
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

@section('head-css')
    <link href="{{asset('nazox/assets/libs/select2/css/select2.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />
@endsection

@section('script-js')
    <script src="{{asset('nazox/assets/js/pages/form-validation.init.js')}}"></script>
    <script src="{{asset('nazox/assets/libs/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
    <script src="{{asset('nazox/assets/js/pages/form-element.init.js')}}"></script>
    <script src="{{asset('nazox/assets/libs/select2/js/select2.min.js')}}"></script>

    <!-- form mask -->
    <script src="{{asset('nazox/assets/libs/inputmask/jquery.inputmask.min.js')}}"></script>
    <script src="{{asset('js/jquery.maskMoney.min.js')}}"></script>

    <script>

    $(document).ready(function(){

        $('.select2').select2();

        $('.dynamic_tipo').change(function(){

            let tipo = document.getElementById('tipo').value;
            let origem = document.getElementById('origem');
            let origem_lbl = document.getElementById('origem_lbl');
            let destino = document.getElementById('destino');
            let destino_lbl = document.getElementById('destino_lbl');
            let empresa = document.getElementById('empresa');
            let forma_pagamento = document.getElementById('forma_pagamento');
            let valor = document.getElementById('valor');
            let valor_view = document.getElementById('valor_view');
            let path_comprovante = document.getElementById('path_comprovante');
            let path_comprovante_lbl = document.getElementById('path_comprovante_lbl');

            switch(tipo){
                case 'CP':
                    origem.options.selectedIndex = 0;
                    $('#origem').val(null).trigger('change');
                    origem.disabled = true;
                    origem_lbl.innerHTML = 'Origem - Empresa';

                    destino.disabled = false;
                    destino_lbl.innerHTML = 'Destino - Fazenda';

                    empresa.disabled = false;
                    forma_pagamento.disabled = false;
                    valor.disabled = false;
                    valor_view.disabled = false;
                    valor_view.style.backgroundColor = 'white';
                    path_comprovante_lbl.style.backgroundColor = 'white';
                    path_comprovante.disabled = false;
                    break;

                case 'VD':
                    origem.disabled = false;
                    origem_lbl.innerHTML = 'Origem - Fazenda';

                    destino.options.selectedIndex = 0;
                    $('#destino').val(null).trigger('change');
                    destino.disabled = true;
                    destino_lbl.innerHTML = 'Destino - Empresa';

                    empresa.disabled = false;
                    forma_pagamento.disabled = false;
                    valor.disabled = false;
                    valor_view.disabled = false;
                    valor_view.style.backgroundColor = 'white';
                    path_comprovante_lbl.style.backgroundColor = 'white';
                    path_comprovante.disabled = false;
                    break;

                case 'EG':
                    origem.disabled = false;
                    origem_lbl.innerHTML = 'Origem - Fazenda';

                    destino.disabled = false;
                    destino_lbl.innerHTML = 'Destino - Fazenda';

                    empresa.options.selectedIndex = 0;
                    $('#empresa').val(null).trigger('change');
                    empresa.disabled = true;

                    forma_pagamento.options.selectedIndex = 0;
                    $('#forma_pagamento').val(null).trigger('change');
                    forma_pagamento.disabled = true;

                    valor.value = '';
                    valor.disabled = true;
                    valor_view.value = '';
                    valor_view.disabled = true;
                    valor_view.style.backgroundColor = '#D3D3D3';

                    path_comprovante_lbl.innerHTML = 'Selecionar Comprovante';
                    path_comprovante_lbl.style.backgroundColor = '#D3D3D3';

                    path_comprovante.value = '';
                    path_comprovante.disabled = true;
                    path_comprovante.style.color = '#D3D3D3';
                    break;
            }

        });

        $('.dynamic_categoria').change(function(){

            let categoria = document.getElementById('categoria');
            let categoria_text = categoria.options[categoria.selectedIndex].text;

            let item_macho = document.getElementById('item_macho');
            let qtd_macho = document.getElementById('qtd_macho');
            let item_femea = document.getElementById('item_femea');
            let qtd_femea = document.getElementById('qtd_femea');

            switch(categoria_text){
                case 'Macho':
                    item_macho.disabled = false;
                    qtd_macho.disabled = false;
                    qtd_macho.style.backgroundColor = 'white';

                    item_femea.options.selectedIndex = 0;
                    $('#item_femea').val(null).trigger('change');
                    item_femea.disabled = true;

                    qtd_femea.value = '';
                    qtd_femea.disabled = true;
                    qtd_femea.style.backgroundColor = '#D3D3D3';
                    break;

                case 'Fêmea':
                    item_macho.options.selectedIndex = 0;
                    $('#item_macho').val(null).trigger('change');
                    item_macho.disabled = true;

                    qtd_macho.value = '';
                    qtd_macho.disabled = true;
                    qtd_macho.style.backgroundColor = '#D3D3D3';

                    item_femea.disabled = false;
                    qtd_femea.disabled = false;
                    qtd_femea.style.backgroundColor = 'white';
                    break;

                default :
                    item_macho.disabled = false;
                    qtd_macho.disabled = false;
                    qtd_macho.style.backgroundColor = 'white';

                    item_femea.disabled = false;
                    qtd_femea.disabled = false;
                    qtd_femea.style.backgroundColor = 'white';
                    break;
            }

        });

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

        $('.dynamic_tipo').trigger('change');
        $('.dynamic_categoria').trigger('change');
    });

    function formatValorMoeda(field){
        let element =  document.getElementById(field);

        if(element && element.value){
            valueFormatted = parseFloat(element.value.replace('R$ ', '').replace(',', '.')).toFixed(2).replace('.', ',');
            document.getElementById(field).value = valueFormatted;

            $('#'+field).trigger('select');
        }
    }

    </script>

@endsection

