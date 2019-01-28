@extends('adminlte::page')

@section('content')

<section class="invoice">
  <!-- title row -->
  <div class="row">
    <div class="col-xs-12">
      <h2 class="page-header">
        <i class="fa fa-user"></i> {{$subsidio->CerposNom}}
        <small class="pull-right"><strong>{{$subsidio->CerNro}}</strong></small>
      </h2>
    </div>
    <!-- /.col -->
  </div>
  <!-- info row -->
  <div class="row invoice-info">
    <div class="col-sm-4 invoice-col">
      <strong>Titular</strong> 
      <address>
        Nombre: {{$subsidio->CerposNom}}<br>
        Documento: {{$subsidio->CerPosCod}}<br>
        Fecha de Adjudicación: <strong>{{date('d/m/Y', strtotime($subsidio->CerFeRe))}}</strong>
      </address>
      
    </div>
    <!-- /.col -->
    <div class="col-sm-4 invoice-col">
        <strong>Cónyuge (pareja)</strong>
      <address>
        Nombre: {{$subsidio->CerCoNo}}<br>
        Documento: {{$subsidio->CerCoCI}}<br>
        
      </address>
    </div>
    <!-- /.col -->
    <div class="col-sm-4 invoice-col">
        <strong>Ubicación</strong>
        <address>
          Lugar: {{$subsidio->CerIdent}}<br>
          Distrito: {{$ciudad->CiuNom}}<br>
          Departamento: {{$depto->DptoNom}}<br>
        </address>
    </div>
    <!-- /.col -->
  </div>

  <!-- this row will not appear when printing -->
  <div class="row no-print">
    <div class="col-xs-12">
        
        <a href="{!! action('FileController@imprimir', ['id'=>$subsidio->CerNro]) !!}" > 
            <button type="button" class="btn btn-success pull-right" style="margin-right: 5px;">
                <i class="fa fa-download"></i> Generar PDF
              </button>
        </a>
        <a href="{!! action('HomeController@index', ['progid' => $progid, 'resid' => $resid
            ,'dateid' => $dateid,'cedula' => $cedula,'page' => $page]) !!}" > 
            <button type="button" class="btn btn-warning pull-right" style="margin-right: 5px;">
                <i class="fa fa-download"></i> Volver
              </button>
        </a>
    </div>
  </div>
</section>

@endsection
