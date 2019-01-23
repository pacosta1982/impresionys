<div class="box box-primary">
    <div class="box-body">
    <div class="row">
            <form action="/filtros" method="post">
                {{ csrf_field() }}
        <div class="col-md-3">
                
                <div class="form-group">
                        <label>Programa</label>
                        <select id="progid" name="progid" class="form-control">
                          <option value="0">Seleccione un Programa</option>
                          <option value="1" {{ old('progid',isset($progid)?$progid:'') == '1' ? "selected":""}}>FONAVIS</option>
                          <option value="2" {{ old('progid',isset($progid)?$progid:'') == '2' ? "selected":""}}>VYA RENDA</option>
                          <option value="3" {{ old('progid',isset($progid)?$progid:'') == '3' ? "selected":""}}>CHE TAPYI</option>
                          <option value="4" {{ old('progid',isset($progid)?$progid:'') == '4' ? "selected":""}}>SEMBRANDO</option>
                          <option value="5" {{ old('progid',isset($progid)?$progid:'') == '5' ? "selected":""}}>EBY</option>
                        </select>
                      </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                    <label>Nro Resolucion</label>
                    <input type="text" id="resid" value="{{old('resid',isset($resid)?$resid:'')}}" name="resid" class="form-control" placeholder="Ingrese Resolución">
                    </div>
        </div>
        <div class="col-md-3">
                <div class="form-group">
                        <label for="task_date">Fecha:</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" value="{{old('dateid',isset($dateid)?$dateid:'')}}"  name="dateid" id="dateid" class="form-control date"  placeholder="Ingrese Fecha">
                        </div>
                    </div>
                    
            </div>
            <div class="col-md-3">
                    <div class="form-group">
                            <label>Cédula</label>
                            <input type="text" id="cedula" value="{{old('cedula',isset($cedula)?$cedula:'')}}" name="cedula" class="form-control" placeholder="Ingrese cedula">
                            </div>
                            <button type="submit" class="btn btn-primary pull-right">Buscar</button>
            </div>
            
        </form>
    </div>
</div>
</div>
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Beneficiarios</h3>
        <div class="pull-right">{{ $projects->appends(request()->except('_token'))->links() }}</div>  
    </div>
    <!-- /.box-header -->
    
    
    <div class="box-body">
            
        <table id="example" class="table">
            <thead>
                <tr>
                  <th>Codigo</th>
                  <th>Beneficiario</th>
                  <th style="text-align:center;">Cédula</th>
                  <th style="text-align:center;">Nro Resolución</th>
                  <th style="text-align:center;">Fecha Resolución</th>
                  <th>Programa</th>
                  <th style="text-align:center;">Impreso</th>
                  <th style="text-align:center;">Acciones</th>
                </tr>
                </thead>
            <tbody>
                @foreach($projects as $project)  
              <tr>
                <td>{!! $project->CerNro !!}</td>
                <td>{!! $project->CerposNom !!}</td>
                <td style="text-align:center;">{!! $project->CerPosCod !!}</td>
                <td style="text-align:center;">{!! $project->CerResNro !!}</td>
                <td style="text-align:center;">{!! date('d-m-Y', strtotime($project->CerFeRe))  !!}</td>
                <td>{!! $name[$project->CerPrgCod] !!}</td>
                <td style="text-align:center;">
                    @if ($project->CerFecSus == null)
                    <i class="fa fa-warning" style="color:darkorange"></i>
                    @else
                    <i class="fa fa-check" style="color:forestgreen"></i>
                    @endif
                </td>
                <td style="text-align:center;">
                    <a href="{!! action('FileController@generateDocx', ['id'=>$project->CerNro]) !!}" > 
                        <button class="btn btn-success" type="button"> Imprimir </button>
                    </a>
                </td>
              </tr>
             @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>Codigo</th>
                    <th>Beneficiario</th>
                    <th style="text-align:center;">Cédula</th>
                    <th style="text-align:center;">Nro Resolución</th>
                    <th style="text-align:center;">Fecha Resolución</th>
                    <th>Programa</th>
                    <th style="text-align:center;">Impreso</th>
                    <th style="text-align:center;">Acciones</th>
                </tr>
            </tfoot>
        </table>
      <!-- /.table-responsive -->
    </div>
    <!-- /.box-body -->
    <!-- /.box-footer -->
  </div>

@section('js')
<script src="{{asset('js/bootstrap-datepicker.es.min.js')}}" charset="UTF-8"></script>
<script src="{{asset('fullcalendar/lib/jquery-ui.custom.min.js')}}"></script>
<script src="{{asset('fullcalendar/lib/moment.min.js')}}"></script>
<script src="{{asset('fullcalendar/fullcalendar.js')}}"></script>
<script src="{{asset('fullcalendar/lang-all.js')}}"></script>
<script src="{{asset('js/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('js/bootstrap-datetimepicker.js')}}"></script>
<script src="{{asset('js/locale-all.js')}}" charset="UTF-8"></script>
<script src="{{asset('bootstrap-timepicker/js/bootstrap-timepicker.js')}}"></script>
<script type="text/javascript">

    $('.date').datepicker({  
       format: 'yyyy-mm-dd',
       autoclose: 'true',
       languaje: 'es'
     });
     
     $('.timepicker').timepicker({
        showMeridian: false,
        //defaultTime: false
    })

</script> 

@stop