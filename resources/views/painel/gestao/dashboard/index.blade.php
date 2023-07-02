@extends('painel.layout.index')

@section('content')

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

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Dashboard</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
@endsection

@section('head-css')
@endsection

@section('script-js')
@endsection


