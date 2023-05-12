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
            <p class="card-title-desc">O Lançamento registrado estará disponível para os movimentos no sistema.</p>
            <form name="edit_lancamento" method="POST" action="{{route('lancamento.update_MG', compact('lancamento'))}}"  class="needs-validation"  accept-charset="utf-8" enctype="multipart/form-data" novalidate>
                @csrf
                @method('PUT')

                <input type="hidden" id="tipo" name="tipo" value="{{ $lancamento->tipo }}">

                <div class="bg-soft-primary p-3 rounded" style="margin-bottom:10px;">
                    <h5 class="text-primary font-size-14" style="margin-bottom: 0px;">Dados do Lançamento do Efetivo Pecuário</h5>
                </div>

                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="tipo">Tipo Movimentação</label>
                            <input style="background-color: #D3D3D3;" type="text" class="form-control" value="{{$lancamento->tipo_lancamento_texto}}" disabled>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="categoria">Categoria</label>
                            <input style="background-color: #D3D3D3;" type="text" class="form-control" value="{{$lancamento->categoria->nome}}" disabled>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="empresa">Empresa</label>
                            <textarea style="background-color: #D3D3D3;" type="text" class="form-control" disabled>{{$lancamento->empresa->nome_empresa ?? '...'}}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="item_macho">Classificação Machos</label>
                            <input style="background-color: #D3D3D3;" type="text" class="form-control" value="{{$lancamento->classificacao_macho}}" disabled>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="qtd_macho">Qtd. Machos</label>
                            <input style="background-color: #D3D3D3;" type="text" class="form-control" value="{{$lancamento->qtd_macho}}" disabled>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="item_femea">Classificação Fêmeas</label>
                            <input style="background-color: #D3D3D3;" type="text" class="form-control" value="{{$lancamento->classificacao_femea}}" disabled>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="qtd_femea">Qtd. Fêmeas</label>
                            <input style="background-color: #D3D3D3;" type="text" class="form-control" value="{{$lancamento->qtd_femea}}" disabled>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="gta">Número GTA</label>
                            <input type="text" class="form-control" id="gta" name="gta" value="{{$lancamento->gta}}" placeholder="Número GTA">
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="path_gta" class="{{($errors->first('path_gta') ? 'form-error-label' : '')}}">GTA (imagem/pdf)
                            @if($lancamento->path_gta)
                            <a href="{{ route('lancamento.download', ['lancamento' => $lancamento->id, 'tipo_documento' => 'GT']) }}">
                                <i class="mdi mdi-file-download mdi-18px" style="color: goldenrod;cursor: pointer" title="Download da GTA"></i>
                            </a>
                            @endif
                        </label>
                        <div class="form-group custom-file">
                            <input type="file" class="custom-file-input {{($errors->first('path_gta') ? 'form-error-field' : '')}}" id="path_gta" name="path_gta" accept="image/*, application/pdf">
                            <label class="custom-file-label" for="path_gta">Selecionar GTA</label>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="origem">Origem</label>
                            <textarea style="background-color: #D3D3D3;" type="text" class="form-control" disabled>{{$lancamento->origem->nome_fazenda ?? '...'}}</textarea>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="destino">Destino</label>
                            <textarea style="background-color: #D3D3D3;" type="text" class="form-control" disabled>{{$lancamento->destino->nome_fazenda ?? '...'}}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="data_programada">Data Programada</label>
                            <input type="date" class="form-control" id="data_programada" name="data_programada" value="{{$lancamento->data_programada_ajustada}}" placeholder="Data Programada" required>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="observacao">Observações</label>
                            <textarea rows="3" class="form-control" id="observacao" name="observacao" placeholder="Observação">{{$lancamento->observacao}}</textarea>
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
                            <textarea style="background-color: #D3D3D3;" type="text" class="form-control" disabled>{{$lancamento->movimentacao->produtor->nome_produtor ?? '...'}}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="forma_pagamento">Forma Pagamento</label>
                            <textarea style="background-color: #D3D3D3;" type="text" class="form-control" disabled>{{$lancamento->movimentacao->forma_pagamento->forma ?? '...'}}</textarea>
                        </div>
                    </div>
                </div>

                <br>

                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="valor" class="{{($errors->first('valor') ? 'form-error-label' : '')}}">Valor</label>
                            <input type="hidden" class="form-control" id="valor" name="valor" value="{{ $lancamento->movimentacao->valor ?? '' }}">
                            <input type="text" style="background-color: {{ $lancamento->tipo == 'EG' ? '#D3D3D3' : 'white' }};" class="form-control updValor mask_valor {{($errors->first('valor') ? 'form-error-field' : '')}}" id="valor_view" name="valor_view" value="{{ $lancamento->movimentacao->valor ?? '' }}" placeholder="Valor" {{ $lancamento->tipo == 'EG' ? 'disabled' : 'required' }}>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="path_comprovante" class="{{($errors->first('path_comprovante') ? 'form-error-label' : '')}}">Comprovante Pagamento (imagem/pdf)
                            @if($lancamento->movimentacao && $lancamento->movimentacao->path_comprovante)
                            <a href="{{ route('lancamento.download', ['lancamento' => $lancamento->id, 'tipo_documento' => 'CP']) }}">
                                <i class="mdi mdi-file-download mdi-18px" style="color: goldenrod;cursor: pointer" title="Download do Comprovante de Pagamento"></i>
                            </a>
                            @endif
                        </label>
                        <div class="form-group custom-file">
                            <input type="file" class="custom-file-input {{($errors->first('path_comprovante') ? 'form-error-field' : '')}}" id="path_comprovante" name="path_comprovante" accept="image/*, application/pdf" {{ $lancamento->tipo == 'EG' ? 'disabled' : '' }}>
                            <label style="background-color: {{ $lancamento->tipo == 'EG' ? '#D3D3D3' : 'white' }};"  id="path_comprovante_lbl" class="custom-file-label" for="path_comprovante">Selecionar Comprovante</label>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="nota" class="{{($errors->first('nota') ? 'form-error-label' : '')}}">Número Nota Fiscal</label>
                            <input type="text" style="background-color: {{ $lancamento->tipo == 'EG' ? '#D3D3D3' : 'white' }};" class="form-control {{($errors->first('nota') ? 'form-error-field' : '')}}" id="nota" name="nota" value="{{$lancamento->movimentacao->nota ?? '...'}}" placeholder="Número Nota" {{ $lancamento->tipo == 'EG' ? 'disabled' : '' }}>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="path_nota" class="{{($errors->first('path_nota') ? 'form-error-label' : '')}}">Nota Fiscal (imagem/pdf)
                            @if($lancamento->movimentacao && $lancamento->movimentacao->path_nota)
                            <a href="{{ route('lancamento.download', ['lancamento' => $lancamento->id, 'tipo_documento' => 'NT']) }}">
                                <i class="mdi mdi-file-download mdi-18px" style="color: goldenrod;cursor: pointer" title="Download da Nota"></i>
                            </a>
                            @endif
                        </label>
                        <div class="form-group custom-file">
                            <input type="file" class="custom-file-input {{($errors->first('path_nota') ? 'form-error-field' : '')}}" id="path_nota" name="path_nota" accept="image/*, application/pdf" {{ $lancamento->tipo == 'EG' ? 'disabled' : '' }}>
                            <label style="background-color: {{ $lancamento->tipo == 'EG' ? '#D3D3D3' : 'white' }};" id="path_nota_lbl" class="custom-file-label" for="path_nota">Selecionar Nota</label>
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

