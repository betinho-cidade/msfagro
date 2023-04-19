@extends('painel.layout.index')


@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Informações do Usuário</h4>
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

            <h4 class="card-title">Formulário de Atualização - Usuário {{$usuario->name}}</h4>
            <p class="card-title-desc">O usuário cadastrado poderá acessar ao sistema e realizar as ações necessárias conforme seu perfil de acesso GESTOR ou CLIENTE. Cada usuário somente poderá ter um ÚNICO perfil associado.</p>

            <form name="edit_usuario" method="POST" action="{{route('usuario.update', compact('usuario'))}}"  class="needs-validation" accept-charset="utf-8" enctype="multipart/form-data" novalidate>
                @csrf
                @method('PUT')

                <!-- Dados Pessoais - INI -->
                <div class="bg-soft-primary p-3 rounded" style="margin-bottom:10px;">
                    <h5 class="text-primary font-size-14" style="margin-bottom: 0px;">Dados Pessoais</h5>
                </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="nome" class="{{($errors->first('nome') ? 'form-error-label' : '')}}">Nome</label>
                                <input type="text" class="form-control {{($errors->first('nome') ? 'form-error-field' : '')}}" id="nome" name="nome" value="{{$usuario->name}}" placeholder="Nome" required>
                                <div class="valid-feedback">ok!</div>
                                <div class="invalid-feedback">Inválido!</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="email" class="{{($errors->first('email') ? 'form-error-label' : '')}}">Login Acesso</label>
                                <input type="text" class="form-control {{($errors->first('email') ? 'form-error-field' : '')}}" id="email" name="email" value="{{$usuario->email}}" placeholder="Login Acesso" required>
                                <div class="valid-feedback">ok!</div>
                                <div class="invalid-feedback">Inválido!</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="situacao" class="{{($errors->first('situacao') ? 'form-error-label' : '')}}">Situação</label>
                                <select id="situacao" name="situacao" class="form-control {{($errors->first('situacao') ? 'form-error-field' : '')}}" required>
                                    <option value="">---</option>
                                    <option value="A" {{($usuario->situacao['status'] == 'A') ? 'selected' : '' }}>Ativo</option>
                                    <option value="I" {{($usuario->situacao['status'] == 'I') ? 'selected' : '' }}>Inativo</option>
                                </select>
                                <div class="valid-feedback">ok!</div>
                                <div class="invalid-feedback">Inválido!</div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="perfil" class="{{($errors->first('perfil') ? 'form-error-label' : '')}}">Perfil Acesso</label>
                                <input type="text" class="form-control {{($errors->first('perfil') ? 'form-error-field' : '')}}" value="{{$usuario->perfil}}" disabled>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="password" class="{{($errors->first('password') ? 'form-error-label' : '')}}">Senha</label>
                                <input type="password" class="form-control {{($errors->first('password') ? 'form-error-field' : '')}}" id="password" name="password" placeholder="Senha">
                                <div class="valid-feedback">ok!</div>
                                <div class="invalid-feedback">Inválido!</div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="password_confirm" class="{{($errors->first('password_confirm') ? 'form-error-label' : '')}}">Senha Confirmação</label>
                                <input type="password" class="form-control {{($errors->first('password_confirm') ? 'form-error-field' : '')}}" id="password_confirm" name="password_confirm" placeholder="Senha de Confirmação">
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
