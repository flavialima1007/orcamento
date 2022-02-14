<div class="card p-3">  
    <div class="form-row p-1">
        <div class="form-group col-md-2"><strong>Dotação:</strong> {{ $request_FO->dotacao_id }}</div>
        <div class="form-group col-md-2"><strong>Data:</strong> {{ $request_FO->data }}</div>
        <div class="form-group col-md-2"><strong>Empenho:</strong> {{ $request_FO->empenho }}</div>
        <div class="form-group col-md-6"><strong>Descrição:</strong> {{ $request_FO->descricao }}</div>
    </div>
    <div class="form-row p-1">
        @if($request_FO->debito != 0)
            <div class="form-group col-md-2"><strong>Débito:</strong> {{ $request_FO->debito }}</div>
        @else
            <div class="form-group col-md-2"><strong>Crédito:</strong> {{ $request_FO->credito }}</div>
        @endif
        <div class="form-group col-md-10"><strong>Observação:</strong> {{ $request_FO->observacao }}</div>
    </div>
    <hr>
    <br>
    <div class="card-header">
        <h3>Contra-Partida da Ficha Orçamentária</h3>
    </div>
    @foreach($tipocontaid_quantidades as $tipocontaid_quantidades_key=>$tipocontaid_quantidades_value)
        @for($i=0; $i < $tipocontaid_quantidades_value; $i++)
            <div class="form-row">
                <div class="form-group col-md-9">
                    <label for="conta">Escolha uma Conta: </label>

                    <select class="contas_select form-control" name="conta_id[]" tabindex="1">
                        <option value=" ">&nbsp;</option>
                        @foreach($tipocontaid_descricaoconta as $tipocontaid_descricaoconta_key=>$tipocontaid_descricaoconta_value)
                            @foreach($lista_contas_ativas as $lista_conta)

                            <option value="{{ $lista_conta->id }}">{{ $lista_conta->nome }}  ({{ $lista_conta->descricao}})</option>

                            @endforeach
                        @endforeach
                    </select>

                </div>
                <div class="form-group col-md-1">
                    <label for="grupo">Grupo</label>
                    <input type="text" class="form-control" name="grupo[]" value="{{ $dotorcamentaria[0]->grupo ?? $lancamento->grupo ?? old('grupo') }}" placeholder="[ Ex: 080 ]">
                </div>
                <div class="form-group col-md-1">
                    <label for="receita_{{ $tipocontaid_quantidades_key }}_{{ $i }}" class="checkbox-inline">Receita</label><br>
                    <input type="checkbox" name="receita[]" id="receita_{{ $tipocontaid_quantidades_key }}_{{ $i }}" value="1" 
                    @if ($dotorcamentaria[0]->receita === 1)
                        checked
                    @endif >
                </div>
                @if($request_FO->debito != 0)
                    <div class="form-group col-md-1">
                        <label for="debito">Débito</label>
                        <input type="text" class="form-control" name="debito[]" value="{{ $request_FO->debito ?? old('debito') }}" placeholder="[ Ex: 100,00 ]">
                    </div>
                @else
                    <div class="form-group col-md-1">
                        <label for="credito">Crédito</label>
                        <input type="text" class="form-control" name="credito[]" value="{{ $request_FO->credito ?? old('credito') }}" placeholder="[ Ex: 100,00 ]">
                    </div>
                @endif
            </div>
        @endfor
    @endforeach
</div>
<br>
<div class="form-row">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="form-group col-md-12">
                <input type="submit" class="btn btn-primary" value="Salvar">
                <input type="reset" class="btn btn-warning" value="Desfazer">
            </div>
        </div>
    </div>
</div>


@section('javascripts_bottom')
<script>
    $(document).ready(function() {
        $('.contas_select').select2();
    });
</script>
@stop