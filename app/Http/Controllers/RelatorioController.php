<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movimento;
use App\Models\Lancamento;
use App\Models\FicOrcamentaria;
use App\Models\Conta;
use App\Models\TipoConta;
use App\Models\DotOrcamentaria;
use App\Models\Nota;
use App\Models\Area;
use Illuminate\Support\Facades\DB;

use PDF;

class RelatorioController extends Controller
{
    public function relatorios(){
        $this->authorize('Todos');
        $movimento_ativo        = Movimento::movimento_ativo();
        $lista_contas_ativas    = Conta::lista_contas_ativas();
        $lista_tipos_contas     = TipoConta::lista_tipos_contas();
        $lista_dotorcamentarias = DotOrcamentaria::lista_dotorcamentarias();
        $lista_descricoes       = Nota::lista_descricoes();
        $lista_observacoes      = Nota::lista_observacoes();
        $lista_areas            = Area::lista_areas();

        return view('relatorios.index', compact('movimento_ativo',
                                                'lista_tipos_contas',
                                                'lista_contas_ativas',
                                                'lista_dotorcamentarias',
                                                'lista_descricoes',
                                                'lista_observacoes',
                                                'lista_areas'));
    }

    public function balancete(Request $request){
        if($request->data != null){
            $periodo = $request->data;
            $data_convertida = implode("-", array_reverse(explode("/", $request->data)));

            $balancete = DB::table('contas')
            ->join('tipo_contas', 'contas.tipoconta_id', '=', 'tipo_contas.id')
            ->join('lancamentos', 'contas.id', '=', 'lancamentos.conta_id')
            ->select('contas.nome', 'tipo_contas.descricao', DB::raw('SUM(lancamentos.debito) as total_debito'), DB::raw('SUM(lancamentos.credito) as total_credito'))
            ->where('tipo_contas.relatoriobalancete','=',1)
            ->where('lancamentos.receita','=',1)
            ->where('lancamentos.data','<=',$data_convertida)
            ->groupBy('contas.nome', 'tipo_contas.descricao')
            ->get();

            //dd($balancete);

        }
        else{
            request()->session()->flash('alert-info','Informe a Data.');
            return redirect("/relatorios");            
        }

        $pdf = PDF::loadView('pdfs.balancete', [
            'balancete' => $balancete,
            'periodo'   => $periodo,

        ])->setPaper('a4', 'landscape');
        return $pdf->download("balancete.pdf");
    }

    public function acompanhamento(Request $request){
        if($request->grupo != null){
            $tiposconta = TipoConta::All();
            $acompanhamento = Conta::All();
            $periodo = $request->data;
        }
        else{
            request()->session()->flash('alert-info','Informe o Grupo.');
            return redirect("/relatorios");            
        }

        $pdf = PDF::loadView('pdfs.acompanhamento', [
            'acompanhamento'    => $acompanhamento,
            'periodo'    => $periodo,

        ])->setPaper('a4', 'portrait');
        return $pdf->download("acompanhamento.pdf");
    }

    public function saldo_contas(Request $request){
        if($request->tipoconta_id != null){
            $descricao_tipoconta = TipoConta::descricao_tipo_conta($request->tipoconta_id);
            $saldo_contas = DB::table('contas')
            ->join('tipo_contas', 'contas.tipoconta_id', '=', 'tipo_contas.id')
            ->join('lancamentos', 'contas.id', '=', 'lancamentos.conta_id')
            ->select('contas.nome', 'tipo_contas.descricao', DB::raw('SUM(lancamentos.debito) as total_debito'), DB::raw('SUM(lancamentos.credito) as total_credito'))
            ->where('contas.tipoconta_id','=',$request->tipoconta_id)
            ->groupBy('contas.nome', 'tipo_contas.descricao')
            ->get();
        }
        else{
            request()->session()->flash('alert-info','Informe o Tipo de Conta.');
            return redirect("/relatorios");            
        }

        $pdf = PDF::loadView('pdfs.saldo_contas', [
            'saldo_contas'        => $saldo_contas,
            'descricao_tipoconta' => $descricao_tipoconta,
        ])->setPaper('a4', 'portrait');
        return $pdf->download("saldo_contas.pdf");
    }

    public function saldo_dotacoes(Request $request){
        if($request->grupo != null){
            $saldo_dotacoes = DB::table('fic_orcamentarias')
            ->join('dot_orcamentarias', 'fic_orcamentarias.dotacao_id', '=', 'dot_orcamentarias.id')
            ->select('dot_orcamentarias.dotacao', 'dot_orcamentarias.grupo', 'dot_orcamentarias.item', 
                    DB::raw('SUM(fic_orcamentarias.debito) as total_debito'),
                    DB::raw('SUM(fic_orcamentarias.credito) as total_credito'))
            ->where('dot_orcamentarias.grupo','=',$request->grupo)
            ->groupBy('dot_orcamentarias.dotacao', 'dot_orcamentarias.grupo', 'dot_orcamentarias.item')
            ->get();
        }
        else{
            request()->session()->flash('alert-info','Informe o Grupo.');
            return redirect("/relatorios");            
        }

        $pdf = PDF::loadView('pdfs.saldo_dotacoes', [
            'saldo_dotacoes' => $saldo_dotacoes,
            'grupo'          => $request->grupo,

        ])->setPaper('a4', 'portrait');
        return $pdf->download("saldo_dotacoes.pdf");
    }

    public function lancamentos(Request $request){
        $lancamentos = new Lancamento;
        if($request->conta_id != null){
            $lancamentos = $lancamentos->where('conta_id','=',$request->conta_id);
        }
        else{
            request()->session()->flash('alert-info','Informe pelo menos a Conta.');
            return redirect("/relatorios");
        }
        if($request->grupo != null){
            $lancamentos = $lancamentos->where('grupo','=',$request->grupo);
        }
        //if(($request->data_inicial != null) and ($request->data_final != null)){
        //    $lancamentos = $lancamentos->whereBetween('data', [$request->data_inicial, $request->data_final]);
        //}
        if($request->descricao != null){
            $lancamentos = $lancamentos->where('descricao','=',$request->descricao);
        }
        if($request->observacao != null){
            $lancamentos = $lancamentos->where('observacao','=',$request->observacao);
        }
        $lancamentos = $lancamentos->orderBy('data')->get();
        $nome_conta  = Conta::nome_conta($request->conta_id);

        $pdf = PDF::loadView('pdfs.lancamentos', [
            'lancamentos'    => $lancamentos,
            'nome_conta'    => $nome_conta[0]->nome,
        ])->setPaper('a4', 'landscape');
        return $pdf->download("lancamentos.pdf");
    }

    public function ficha_orcamentaria(Request $request){
        $ficha_orcamentaria = new FicOrcamentaria;
        if($request->dotacao_id != null){
            $ficha_orcamentaria = $ficha_orcamentaria->where('dotacao_id','=',$request->dotacao_id);
        }
        else{
            request()->session()->flash('alert-info','Informe pelo menos a Dotação.');
            return redirect("/relatorios");
        }
        //if(($request->data_inicial != null) and ($request->data_final != null)){
        //    $lancamentos = $lancamentos->whereBetween('data', [$request->data_inicial, $request->data_final]);
        //}
        if($request->descricao != null){
            $ficha_orcamentaria = $ficha_orcamentaria->where('descricao','=',$request->descricao);
        }
        if($request->observacao != null){
            $ficha_orcamentaria = $ficha_orcamentaria->where('observacao','=',$request->observacao);
        }
        $ficha_orcamentaria = $ficha_orcamentaria->orderBy('data')->get();
        $dotacao      = DotOrcamentaria::dotacao($request->dotacao_id);

        $pdf = PDF::loadView('pdfs.ficha_orcamentaria', [
            'ficha_orcamentaria'    => $ficha_orcamentaria,
            'dotacao'    => $dotacao[0]->dotacao,

        ])->setPaper('a4', 'landscape');
        return $pdf->download("ficha_orcamentaria.pdf");
    }

    public function despesas(Request $request){
        if($request->area_id != null){
            $despesas = FicOrcamentaria::All();
        }
        else{
            request()->session()->flash('alert-info','Informe a Área.');
            return redirect("/relatorios");
        }
        $pdf = PDF::loadView('pdfs.despesas', [
            'despesas'    => $despesas,
        ])->setPaper('a4', 'landscape');
        return $pdf->download("despesas.pdf");
    }

    public function despesas_miudas(Request $request){
        if(($request->data_inicial != null) and ($request->data_final != null)){
            $despesas_miudas = FicOrcamentaria::All();
        }
        else{
            request()->session()->flash('alert-info','Informe o período.');
            return redirect("/relatorios");
        }

        $pdf = PDF::loadView('pdfs.despesas_miudas', [
            'despesas_miudas'    => $despesas_miudas,
        ])->setPaper('a4', 'landscape');
        return $pdf->download("despesas_miudas.pdf");
    }

}
