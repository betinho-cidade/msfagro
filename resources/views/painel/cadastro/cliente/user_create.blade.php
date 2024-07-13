@extends('painel.layout.index')


@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Novos Usuários para serem vinculados ao Cliente</h4>
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

            <h4 class="card-title">Formulário de Vínculo de Usuários ao Cliente</h4>
            <p class="card-title-desc">Os usuários relacionados estarão disponíveis para trabalharem com o cliente.</p>
            <form name="create_user_cliente" method="POST" action="{{route('cliente.user_store', compact('cliente'))}}"  class="needs-validation" novalidate>
                @csrf
                @method('put')
                
                <div class="bg-soft-primary p-3 rounded" style="margin-bottom:10px;">
                    <h5 class="text-primary font-size-14" style="margin-bottom: 0px;">Lista de Usuários</h5>
                </div>

                <div class="row">
                    <div class="col-md-9">
                        <div class="form-group">
                            <label for="users">Usuários Disponíveis (*somente assinates ativos)</label>
                            <select id="users[]" name="users[]" class="form-control select2" multiple required>
                                <option value="">---</option>
                                @foreach ($users as $usuario)
                                    <option value="{{ $usuario->id }}"
                                        {{ $usuario->id == old('users[]') ? 'selected' : '' }}>{{ $usuario->name }} ({{ $usuario->email }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="valid-feedback">ok!</div>
                            <div class="invalid-feedback">Inválido!</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="perfil" class="{{($errors->first('perfil') ? 'form-error-label' : '')}}">Perfil Acesso</label>
                            <select id="perfil" name="perfil" class="form-control {{($errors->first('perfil') ? 'form-error-field' : '')}} dynamic_perfil" required>
                                <option value="">---</option>
                                @foreach($perfils as $perfil)
                                    <option value="{{$perfil->id}}" {{($perfil->id == old('perfil')) ? 'selected' : '' }}>{{$perfil->description}}</option>
                                @endforeach
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
        });
    </script>
@endsection
