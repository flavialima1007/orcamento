<div class="form-group col-md-3 p-3">
  <div class="card p-3">
    <h3><strong>Balancete</strong></h3>
  </div>
  <br> 
  <form method="get" action="/relatorios/balancete">
    @csrf
    <div class="row">
    <div class="col-sm input-group">
    <input type="text" class="form-control datepicker data" name="data" value="{{ Carbon\Carbon::now()->format('d/m/Y') }}">
    <span class="input-group-btn">
      <button type="submit" class="btn btn-success"><strong>OK</strong></button>
    </span>
    </div>
    </div>
  </form>
</div>
