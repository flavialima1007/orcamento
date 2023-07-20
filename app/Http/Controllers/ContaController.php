<?php

namespace App\Http\Controllers;

use App\Models\Conta;
use Illuminate\Http\Request;
use App\Http\Requests\ContaRequest;
use App\Models\Movimento;
use App\Models\TipoConta;
use App\Models\Area;
use App\Models\Lancamento;

class ContaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){

        $this->authorize('Todos');

        $contas = Conta::when($request->busca_nome, function ($query) use ($request) {
                      return $query->where('nome', 'LIKE', '%' . $request->busca_nome.'%');})
                    ->when($request->tipoconta_id, function ($query) use ($request) {
                        return $query->where('tipoconta_id','=', $request->tipoconta_id);})
                    ->orderBy('nome')
                    ->paginate(10);

        $lista_tipos_contas = TipoConta::lista_tipos_contas();
        return view('contas.index', compact('contas','lista_tipos_contas'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function contas_por_tipo_de_conta($tipoconta_id){
        $this->authorize('Todos');
        $contas = Conta::where('tipoconta_id','=',$tipoconta_id)->orderBy('nome')->paginate(10);
        $lista_tipos_contas = TipoConta::lista_tipos_contas();
        return view('contas.index',[
                    'contas'             => $contas,
                    'lista_tipos_contas' => $lista_tipos_contas,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function lancamentos_por_conta($conta){

        $this->authorize('Todos');
        
        $lancamentos = Lancamento::with('contas')->get();
        
        /*
        foreach($lancamentos as $lancamento){
            
            $pivot = $lancamento->contas->pluck('pivot');
            $conta_id = $pivot->pluck('conta_id');
            $lancamento_id = $pivot->pluck('lancamento_id');
            //dd($conta_id);

        }
        */

        $total_debito  = 0.00;
        $total_credito = 0.00;
        $concatena_debito = '';
        foreach($lancamentos as $lancamento){
            $total_debito     += $lancamento->debito_raw;
            $concatena_debito .= $lancamento->debito_raw . ' -  ';
            $total_credito    += $lancamento->credito_raw;
        }

        return view('lancamentos.index',[
                    'lancamentos'         => $lancamentos,
                    'total_debito'        => $total_debito,
                    'total_credito'       => $total_credito,
                    'lista_contas_ativas' => Conta::lista_contas_ativas(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        $this->authorize('Todos');
        return view('contas.create',[
                    'conta'              => new Conta,
                    'lista_tipos_contas' => TipoConta::lista_tipos_contas(),
                    'lista_areas'        => Area::lista_areas(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ContaRequest $request){
        $this->authorize('Todos');
        Conta::create($request->validated() + ['user_id' => \Auth::user()->id]);
        $request->session()->flash('alert-success', 'Conta [ ' . $request->nome . ' ] cadastrada com sucesso!');
        return redirect()->route('contas.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Conta  $conta
     * @return \Illuminate\Http\Response
     */
    public function show(Conta $conta){
        $this->authorize('Todos');
        return view('contas.show', compact('conta'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Conta  $conta
     * @return \Illuminate\Http\Response
     */
    public function edit(Conta $conta){
        $this->authorize('Administrador');
        $lista_tipos_contas = TipoConta::lista_tipos_contas();
        $lista_areas        = Area::lista_areas();
        return view('contas.edit',[
            'conta'              => $conta,
            'lista_tipos_contas' => $lista_tipos_contas,
            'lista_areas'        => $lista_areas,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Conta  $conta
     * @return \Illuminate\Http\Response
     */
    public function update(ContaRequest $request, Conta $conta){
        $this->authorize('Administrador');
        $validated = $request->validated();
        $validated['user_id'] = auth()->user()->id;
        $validated['ativo'] = $request->has('ativo');
        $conta->update($validated);
        $request->session()->flash('alert-success', 'Conta [ ' . $conta->nome . ' ] alterada com sucesso!');
        return redirect()->route('contas.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Conta  $conta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Conta $conta, Request $request){

        $this->authorize('Administrador');
        
        if($conta->lancamento->isNotEmpty()){
            request()->session()->flash('alert-danger','Conta [ ' . $conta->nome . ' ] não pode ser excluída,
            pois existem Lançamentos cadastrados nela.');
            return redirect("/contas");
        }
        $conta->delete();
        return redirect()->route('contas.index')->with('alert-success', 'Conta [ ' . $conta->nome . ' ] excluída com sucesso!');
    }
}
