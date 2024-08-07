@extends('master')
@section('title')
    Tipos de Contas - Edição
@endsection
@section('content')
<div class="card p-3">
    <h2><strong>Tipos de Contas - Edição</strong></h2>
</div>
<br>
<div class="border rounded bg-light">
    <div class="p-4">
        @include('messages.flash')
        @include('messages.errors')
        <form method="post" action="/tipocontas/{{$tipoconta->id}}">
            @csrf
            @method('patch')
            @include('tipocontas.form')
        </form>
    </div>
</div>
@stop
