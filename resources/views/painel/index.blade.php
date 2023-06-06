@extends('painel.layout.index')


@section('content')
MFSAgro.

@if(session()->has('message.level'))
<div class="row">
    <div class="col-12">
        <div class="alert alert-{{ session('message.level') }}">
        {!! session('message.content') !!}
        </div>
    </div>
</div>
@endif

@endsection


@section('search_tools')
Adicionar a ferramenta de busca
@endsection
