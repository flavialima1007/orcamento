<div class="card p-3">  
    @include('lancamentos.partials.filtro')
  <div class="form-group col-md-1">
    <label for="grupo">Percentual</label>
        <input type="text" class="form-control" name="percentual" value="100">
  </div>
  <br>
  <div class="form-row">
      <div class="panel panel-default">
          <div class="panel-body">
              <div class="form-group col-md-12">
                  <input type="hidden" name="id" value="{{ $lancamento->id }}">
                  <input type="submit" class="btn btn-success" value="Salvar">
                  <input type="reset" class="btn btn-warning" value="Desfazer">
              </div>
          </div>
      </div>
  </div>
</div>
@section('javascripts_bottom')
<script type="text/javascript">
  // CSRF Token
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $(document).ready(function(){
      function filtro(valor){
        if( valor ) {
          $.ajax({
            url:"{{ route('percentual.getLancamentoContas') }}",
            type: 'post',
            dataType: "json",
            data: {
                _token: CSRF_TOKEN,
                search: valor,
            },
            beforeSend: function() {
              $('#conta').html('<option value="">Aguarde... </option>');
            },
            success: function( data ) {
                var options = '<option value="">Selecione a conta...</option>';
                for (var i = 0; i < data.length; i++) {
                options += '<option value="' + data[i].id + '">'
                    +data[i].nome + '</option>';
                }
                $("#conta").html(options);
            },
            complete: function(){
              $('#conta').val("{{ old('contas') }}");
            }
          });
        }
        else {
          $('#conta').html('<option value="">Selecione um Tipo de conta</option>');
        }
      }
      
      if( $("#tipoconta").val() ) filtro($("#tipoconta").val())

      $("#tipoconta").change(function () {
        filtro($(this).val())
      });
    });
</script>
@endsection