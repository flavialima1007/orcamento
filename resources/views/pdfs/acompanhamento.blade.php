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
  <br><h3><center>Acompanhamento do grupo {{ request()->grupo }} - {{ $saldo_inicial->descricaogrupo }} até o dia {{ request()->data }}</center></h3>
  <table width="100%" border="0px">
    <thead>
      <tr>
        <th width="80%">Descrição</th>
        <th width="20%">Saldo</th>
      </tr>
    </thead>
    <tbody>
        <tr>
          <td align="left" style="border: 1px solid black">Saldo Inicial</td>
          <td align="right" style="border: 1px solid black">{{ number_format($saldo_inicial->SDOINICIAL, 2, ',', '.') }}</td>
        </tr>
      @php $subtotal_suplementacoes = 0; @endphp
      @foreach ($suplementacoes as $key => $acompanhamento)
        @php $subtotal_suplementacoes = $subtotal_suplementacoes+$acompanhamento->TOTALCREDITO; @endphp
        <tr>
          <td align="left" style="border: 1px solid black">{{ $acompanhamento->descricao }}</td>
          <td align="right" style="border: 1px solid black">{{ number_format($acompanhamento->TOTALCREDITO, 2, ',', '.') }}</td>
        </tr>
      @endforeach
        <tr>
            <td align="left" style="border: 1px solid black"><b>SUBTOTAL</b></td>
            <td align="right" style="border: 1px solid black"><b>{{ number_format($subtotal_suplementacoes, 2, ',', '.') }}</b></td>
        </tr>
      @php $total = $subtotal_suplementacoes+$saldo_inicial->SDOINICIAL; @endphp
        <tr>
            <td align="left" style="border: 1px solid black"><b>TOTAL</b></td>
            <td align="right" style="border: 1px solid black"><b>{{ number_format($total, 2, ',', '.') }}</b></td>
        </tr>
    </tbody>
  </table>

  <h3><center>SITUAÇÃO ORÇAMENTÁRIA - GASTOS EFETIVOS</center></h3>
  <table width="100%" border="0px">
    <thead>
      <tr>
        <th width="80%">Descrição</th>
        <th width="20%">Saldo</th>
      </tr>
    </thead>
    @php $subtotal_acompanhamento = 0; @endphp
    @foreach ($table as $key => $acompanhamento)
      @php $subtotal_acompanhamento = $subtotal_acompanhamento+$acompanhamento['saldo']; @endphp
      <tr>
        <td align="left" style="border: 1px solid black">{{ $acompanhamento['nome_conta'] }}</td>
        <td align="right" style="border: 1px solid black">{{ number_format($acompanhamento['saldo'],2) }}</td>
      </tr>
    @endforeach
    <tr>
      <td align="left" style="border: 1px solid black"><b>SUBTOTAL</b></td>
      <td align="right" style="border: 1px solid black"><b>{{ number_format($subtotal_acompanhamento, 2, ',', '.') }}</b></td>
    </tr>
    <tr>
      <td align="left" style="border: 1px solid black"><b>DISPONÍVEL</b></td>
      <td align="right" style="border: 1px solid black"><b>{{ number_format($total - $subtotal_acompanhamento, 2, ',', '.') }}</b></td>
    </tr>
    </tbody>
  </table>

  @if(request()->receita_acompanhamento == null && request()->grupo == (int)80)
    <h1><center>Básica {{ request()->grupo }} - Previsão da Administração até o dia {{ request()->data }}</center></h1>
    <table width="100%" border="0px">
    <thead>
      <tr>
        <th width="80%">Descrição</th>
        <th width="20%">Saldo</th>
      </tr>
    </thead>
    <tbody>
        <tr>
          <td align="left" style="border: 1px solid black"><b>SALDO ORÇAMENTÁRIO</b></td>
          <td align="right" style="border: 1px solid black"><b>{{ number_format($total - $subtotal_acompanhamento, 2, ',', '.') }}</b></td>
        </tr>
    @php $subtotal_basica = 0 @endphp
    @foreach($orcamento as $key => $acompanhamento)
      @php $subtotal_basica = $subtotal_basica+($acompanhamento->TOTALCREDITO - $acompanhamento->TOTALDEBITO); @endphp
        <tr>
          <td align="left" style="border: 1px solid black">{{ $acompanhamento->nome }}</td>
          <td align="right" style="border: 1px solid black">{{ number_format($acompanhamento->TOTALCREDITO - $acompanhamento->TOTALDEBITO, 2, ',', '.') }}</td>
        </tr>
    @endforeach
        <tr>
            <td align="left" style="border: 1px solid black"><b>SUBTOTAL</b></td>
            <td align="right" style="border: 1px solid black"><b>{{ number_format($subtotal_basica, 2, ',', '.') }}</b></td>
        </tr>
        @php $total_basica = ($total - $subtotal_acompanhamento)-$subtotal_basica; @endphp
        <tr>
            <td align="left" style="border: 1px solid black"><b>TOTAL</b></td>
            <td align="right" style="border: 1px solid black"><b>{{ number_format($total_basica, 2, ',', '.') }}</b></td>
        </tr>
    </tbody>

  @elseif(request()->receita_acompanhamento != null && request()->grupo == (int)80)
    <h1><center>Renda Industrial {{ request()->grupo }} - Administração até o dia {{ request()->data }}</center></h1>
    <table width="100%" border="0px">
    <thead>
      <tr>
        <th width="80%">Descrição</th>
        <th width="20%">Saldo</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td align="left" style="border: 1px solid black"><b>SALDO ORÇAMENTÁRIO</b></td>
        <td align="right" style="border: 1px solid black"><b>{{ number_format($total - $subtotal_acompanhamento, 2, ',', '.') }}</b></td>
      </tr>
      @php $subtotal_renda = 0; @endphp
      @foreach($renda_industrial as $key => $acompanhamento)
      @php $subtotal_renda = $subtotal_renda+$acompanhamento->TOTALCREDITO; @endphp
        <tr>
          <td align="left" style="border: 1px solid black">{{ $acompanhamento->nome }}</td>
          <td align="right" style="border: 1px solid black">{{ number_format($acompanhamento->TOTALCREDITO, 2, ',', '.') }}</td>
        </tr>
      @endforeach
      <tr>
        <td align="left" style="border: 1px solid black"><b>SUBTOTAL</b></td>
        <td align="right" style="border: 1px solid black"><b>{{ number_format($subtotal_renda, 2, ',', '.') }}</b></td>
      </tr>
      @php $total_renda = ($total - $subtotal_acompanhamento)-$subtotal_renda; @endphp
        <tr>
          <td align="left" style="border: 1px solid black"><b>TOTAL</b></td>
          <td align="right" style="border: 1px solid black"><b>{{ number_format($total_renda, 2, ',', '.') }}</b></td>
        </tr>
    </tbody>
  </table>
  @endif
@endsection
