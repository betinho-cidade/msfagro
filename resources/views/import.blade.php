@extends('layouts.app')

@section('content')

<form name="upload_import" role="form" method="POST" action="{{route('import.upload')}}"  accept-charset="utf-8" enctype="multipart/form-data">
    @csrf

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

    @if(session()->has('message.level'))
    <div class="row">
        <div class="col-12">
            <div class="alert alert-{{ session('message.level') }}">
            {!! session('message.content') !!}
            </div>
        </div>
    </div>
    @endif


    <div class="row">
        <div class="col-12 table-responsive">

            <div class="form-group col-10">
                <label for="iMembro">Membros</label>
                 <input type="file" name="iMembro" id="iMembro" accept="file/*">
                 <p class="help-block"></p>
            </div>

            <div class="row">
                <div class="col-12">
                    <button class="btn btn-primary" type="submit">Importar Membros</button>
                </div>
            </div>


        </div>
        <!-- /.col -->
    </div>


</form>


@endsection
