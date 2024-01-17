@extends('painel.layout.index')


@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Nova Notificação</h4>
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

            <h4 class="card-title">Formulário de Cadastro - Notificação</h4>
            <p class="card-title-desc">A Notificação cadastrada estará disponível para os clientes do sistema.</p>
            <form name="create_notificacao" method="POST" action="{{route('notificacao.store')}}"  class="needs-validation"  accept-charset="utf-8" enctype="multipart/form-data" novalidate>
                @csrf
                <div class="bg-soft-primary p-3 rounded" style="margin-bottom:10px;">
                    <h5 class="text-primary font-size-14" style="margin-bottom: 0px;">Dados Notificação</h5>
                </div>

                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="nome">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" value="{{old('nome')}}" placeholder="Informe o nome da Notificação" required>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>

                    <div class="col-md-7">
                        <div class="form-group">
                            <label for="resumo">Resumo</label>
                            <textarea class="form-control" name="resumo" id="resumo" rows="3" maxlength="500" placeholder="Informe o resumo da Notificação" required>{{old('resumo')}}</textarea>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>                            
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="data_inicio">Data Início</label>
                            <input type="date" class="form-control" id="data_inicio" name="data_inicio" value="{{old('data_inicio')}}" placeholder="Informe a data início da Notificação" required>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="hora_inicio">Hora Início</label>
                            <input type="time" class="form-control" id="hora_inicio" name="hora_inicio" value="{{old('hora_inicio')}}" placeholder="Informe a hora início da Notificação" required>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>                    

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="data_fim">Data Fim</label>
                            <input type="date" class="form-control" id="data_fim" name="data_fim" value="{{old('data_fim')}}" placeholder="Informe a data final da Notificação" required>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="hora_fim">Hora Fim</label>
                            <input type="time" class="form-control" id="hora_fim" name="hora_fim" value="{{old('hora_fim')}}" placeholder="Informe a hora final da Notificação" required>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="url_notificacao">URL <i>*disponível em texto "mais informações"</i></label>
                            <input type="url" class="form-control" id="url_notificacao" name="url_notificacao" value="{{old('url_notificacao')}}" placeholder="Informe a url para download da Notificação">
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="destaque">Destaque ?</label>
                            <select id="destaque" name="destaque" class="form-control" required>
                                <option value="">---</option>
                                <option value="S" {{(old('destaque') == 'S') ? 'selected' : '' }}>Sim</option>
                                <option value="N" {{(old('destaque') == 'N') ? 'selected' : '' }}>Não</option>
                            </select>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>                              
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="todos">Todos os Clientes ?</label>
                            <select id="todos" name="todos" class="form-control" required>
                                <option value="">---</option>
                                <option value="S" {{(old('todos') == 'S') ? 'selected' : '' }}>Sim</option>
                                <option value="N" {{(old('todos') == 'N') ? 'selected' : '' }}>Não</option>
                            </select>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>                                        
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="situacao">Situação</label>
                            <select id="situacao" name="situacao" class="form-control" required>
                                <option value="">---</option>
                                <option value="A" {{(old('situacao') == 'A') ? 'selected' : '' }}>Ativo</option>
                                <option value="I" {{(old('situacao') == 'I') ? 'selected' : '' }}>Inativo</option>
                            </select>
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
@endsection
