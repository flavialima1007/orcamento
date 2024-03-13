@extends('laravel-fflch-pdf::main')
@section('content')
  <style>
    #valor {
      text-align: right;
    }
    td {
      padding-right: 5 px;
      padding-left: 5 px;
      padding-top: 5 px;
      padding-bottom: 5 px;
    }
    thead {
      background-color: #0F1C78;
      color: #FFFFFF;
      padding-right: 5 px;
      padding-left: 5 px;
      padding-top: 5 px;
      padding-bottom: 5 px;
    }
  </style>
  <h1><center>[ Conta(tipo de conta) ]</center></h1>
  <table width="100%" border="0px">
    <thead>
      <tr>
        <th width="80%">Descrição</th>
        <th width="20%">Ativo</th>
      </tr>
    </thead>
    @foreach ($acompanhamento as $acompanhamento_valor)
      <tbody>
          <tr>
            <td align="left" style="border: 1px solid black">{{ $acompanhamento_valor->nome }}</td>
            <td align="right" style="border: 1px solid black">{{ $acompanhamento_valor->ativo == '1' ? 'Sim':'Não'}}</td>
          </tr>
      </tbody>
    @endforeach
  </table>
@endsection
