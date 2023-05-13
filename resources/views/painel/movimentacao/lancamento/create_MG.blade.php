@extends('painel.layout.index')


@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Novo Lançamento do Efetivo Pecuário para o Cliente</h4>
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

            <h4 class="card-title">Formulário de Cadastro - Lançamento de Efetivo Pecuário</h4>
            <p class="card-title-desc">O Lançamento cadastrado estará disponível para os movimentos no sistema.</p>
            <form name="create_lancamento" method="POST" action="{{route('lancamento.store_MG')}}"  class="needs-validation"  accept-charset="utf-8" enctype="multipart/form-data" novalidate>
                @csrf

                <div class="bg-soft-primary p-3 rounded" style="margin-bottom:10px;">
                    <h5 class="text-primary font-size-14" style="margin-bottom: 0px;">Dados do Lançamento do Efetivo Pecuário</h5>
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
                            <input type="number" class="form-control" id="qtd_macho" name="qtd_macho" value="{{old('qtd_macho')}}" placeholder="Qtd. Machos" required>
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
                            <input type="number" class="form-control" id="qtd_femea" name="qtd_femea" value="{{old('qtd_femea')}}" placeholder="Qtd. Fêmeas" required>
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
                            <label id="origem_lbl" for="origem" class="{{($errors->first('origem') ? 'form-error-label' : '')}}">Origem</label> <a href="{{ route('fazenda.create') }}" target="_blank"><i class="fas fa-plus-circle" style="color: goldenrod; margin-left: 5px; vertical-align: middle;" title="Nova Fazenda"></i></a> <i onclick="refreshList('OG');" class="fas fa-sync-alt" style="color: goldenrod; margin-left: 5px; vertical-align: middle;" title="Atualizar Fazendas"></i>
                            <img src="{{asset('images/loading.gif')}}" id="img-loading-origem" style="display:none;max-width: 20px; margin-left: 12px;">
                            <select id="origem" name="origem" class="form-control {{($errors->first('origem') ? 'form-error-field' : '')}} select2" required>
                                <option value="">---</option>
                                @foreach($fazendas as $fazenda)
                                    <option value="{{ $fazenda->id }}" {{(old('fazenda') == $fazenda->id) ? 'selected' : '' }}>{{ $fazenda->nome_fazenda }}</option>
                                @endforeach
                            </select>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label id="destino_lbl" for="destino" class="{{($errors->first('destino') ? 'form-error-label' : '')}}">Destino</label>  <a href="{{ route('fazenda.create') }}" target="_blank"><i class="fas fa-plus-circle" style="color: goldenrod; margin-left: 5px; vertical-align: middle;" title="Nova Fazenda"></i></a> <i onclick="refreshList('DT');" class="fas fa-sync-alt" style="color: goldenrod; margin-left: 5px; vertical-align: middle;" title="Atualizar Fazendas"></i>
                            <img src="{{asset('images/loading.gif')}}" id="img-loading-destino" style="display:none;max-width: 20px; margin-left: 12px;">
                            <select id="destino" name="destino" class="form-control {{($errors->first('destino') ? 'form-error-field' : '')}} select2" required>
                                <option value="">---</option>
                                @foreach($fazendas as $fazenda)
                                    <option value="{{ $fazenda->id }}" {{(old('fazenda') == $fazenda->id) ? 'selected' : '' }}>{{ $fazenda->nome_fazenda }}</option>
                                @endforeach
                            </select>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <label for="data_programada">Data Programada</label>
                        <input type="date" class="form-control" id="data_programada" name="data_programada" value="{{old('data_programada')}}" placeholder="Data Programada" required>
                        <div class="valid-feedback">ok!</div>
                        <div class="invalid-feedback">Inválido!</div>
                    </div>

                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="observacao">Observações</label>
                            <textarea rows="3" class="form-control" id="observacao" name="observacao" placeholder="Observação">{{old('observacao')}}</textarea>
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
                            <label for="nota" class="{{($errors->first('nota') ? 'form-error-label' : '')}}">Número Nota Fiscal</label>
                            <input type="text" class="form-control {{($errors->first('nota') ? 'form-error-field' : '')}}" id="nota" name="nota" value="{{old('nota')}}" placeholder="Número Nota">
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="path_nota" class="{{($errors->first('path_nota') ? 'form-error-label' : '')}}">Nota Fiscal (imagem/pdf)</label>
                        <div class="form-group custom-file">
                            <input type="file" class="custom-file-input {{($errors->first('path_nota') ? 'form-error-field' : '')}}" id="path_nota" name="path_nota" accept="image/*, application/pdf">
                            <label id="path_nota_lbl" class="custom-file-label" for="path_nota">Selecionar Nota</label>
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
            let produtor = document.getElementById('produtor');
            let forma_pagamento = document.getElementById('forma_pagamento');
            let valor = document.getElementById('valor');
            let valor_view = document.getElementById('valor_view');
            let path_comprovante = document.getElementById('path_comprovante');
            let path_comprovante_lbl = document.getElementById('path_comprovante_lbl');
            let nota = document.getElementById('nota');
            let path_nota = document.getElementById('path_nota');
            let path_nota_lbl = document.getElementById('path_nota_lbl');

            switch(tipo){
                case 'CP':
                    origem.options.selectedIndex = 0;
                    $('#origem').val(null).trigger('change');
                    origem.disabled = true;
                    origem_lbl.innerHTML = 'Origem - Empresa';

                    destino.disabled = false;
                    destino_lbl.innerHTML = 'Destino - Fazenda';

                    empresa.disabled = false;
                    produtor.disabled = false;
                    forma_pagamento.disabled = false;
                    valor.disabled = false;
                    valor_view.disabled = false;
                    valor_view.style.backgroundColor = 'white';
                    nota.disabled = false;
                    nota.style.backgroundColor = 'white';
                    path_nota_lbl.style.backgroundColor = 'white';
                    path_nota.disabled = false;
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
                    produtor.disabled = false;
                    forma_pagamento.disabled = false;
                    valor.disabled = false;
                    valor_view.disabled = false;
                    valor_view.style.backgroundColor = 'white';
                    nota.disabled = false;
                    nota.style.backgroundColor = 'white';
                    path_nota_lbl.style.backgroundColor = 'white';
                    path_nota.disabled = false;
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

                    produtor.disabled = true;

                    nota.value = '';
                    nota.disabled = true;
                    nota.style.backgroundColor = '#D3D3D3';

                    path_nota_lbl.innerHTML = 'Selecionar Nota';
                    path_nota_lbl.style.backgroundColor = '#D3D3D3';

                    path_nota.value = '';
                    path_nota.disabled = true;
                    path_nota.style.color = '#D3D3D3';

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
            let categoria_value = categoria.options[categoria.selectedIndex].value;

            let item_macho = document.getElementById('item_macho');
            let qtd_macho = document.getElementById('qtd_macho');
            let item_femea = document.getElementById('item_femea');
            let qtd_femea = document.getElementById('qtd_femea'); // 1->Macho 2->Fêna 3->Macho/Fêmea

            switch(categoria_value){
                case '1':
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

                case '2':
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

                case '3':
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

        if(tipo == 'OG'){
            objectList = $('#origem');
            objectName = 'origem';
        }

        if(tipo == 'DT'){
            objectList = $('#destino');
            objectName = 'destino';
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
                }
            },
            error:function(erro){
                document.getElementById("img-loading-"+objectName).style.display = 'none';
            }
        })
    }
    </script>

@endsection

