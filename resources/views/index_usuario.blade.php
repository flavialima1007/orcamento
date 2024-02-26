@extends('master')
@section('title')
    Contas - {{ $user->name }}
@stop
@section('content')
    @include('messages.flash')
    @include('messages.errors')
<div class="card p-3">
    <h2><strong>Contas vinculadas ao usuário: {{ $user->name }}</strong></h2>
<br>
<form method="get" action="/home_usuario">
@csrf
    <div class="form-group col-md-9">
        <label for="conta">Escolha uma Conta: </label>
        <select class="contas_select form-control" name="conta_id" tabindex="1">           
            <option value=" ">&nbsp;</option>
            @foreach($contas as $conta)
                @if(old('conta_id') == '')
                    <option value="{{ $conta->id }}"
                    {{ ( old('conta_id') == $conta->id ) ? 'selected' : ''}}> {{ $conta->nome }} </option>
                @else
                    <option value="{{ $conta->id }}"> {{ $conta->nome }} </option>
                @endif
            @endforeach
        </select>
    </div>
        <div class="form-group col-md-4">
            <input type="text" class="form-control datepicker data" name="data_inicial" value="01/01/{{ session('ano') }}" placeholder="[ Ex: 01/01/2020 ]">
        </div>
        <div class="form-group col-md-4">
            <input type="text" class="form-control datepicker data" name="data_final" value="31/01/{{ session('ano') }}" placeholder="[ Ex: 01/01/2020 ]">
        </div>
        <div class="form-group col-md-4">
        <button type="submit" class="btn btn-success"><strong>Buscar</strong></button>
        </div>
    </div>
    <br>
    <div class="card p-3">
        <h2><strong>Lançamentos</strong></h2>
    <div class="table-responsive">
        <table class="table table-striped" border="0">
            <thead>
                <tr>
                    <th width="10%" align="left">Data</th>
                    <th width="34%" align="left">Descrição</th>
                    <th width="34%" align="left">Observação</th>
                    <th width="7%" align="left">Débito</th>
                    <th width="7%" align="center">Crédito</th>
                    <th width="7%" align="center">Saldo</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lancamentos as $lancamento)
                    <tr>
                            <td align="left">{{ $lancamento->data }}</td>
                            <td align="left">{{ $lancamento->descricao }}</td>
                            <td align="left">{{ $lancamento->observacao }}</td>
                            @foreach($lancamento->contas as $conta)
                            @if($lancamento->debito != 0.00)
                                <td>{{ number_format((float)($lancamento->debito_raw * $conta->pivot->percentual/100),2, ',', '.') }}</td>
                            @else
                                <td>&nbsp;</td>
                            @endif
                            @if($lancamento->credito != 0.00)
                            <td>{{ number_format((float)($lancamento->credito_raw * $conta->pivot->percentual/100),2, ',', '.') }}</td>
                            @else
                                <td>&nbsp;</td>
                            @endif
                            @endforeach
                            <td>{{ number_format(($lancamento->credito_raw - $lancamento->debito_raw), 2, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr> <td colspan="6" align="center"> Não há lançamentos cadastrados nesse período. </td> </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</form>
@endsection