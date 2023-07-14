<input type="hidden" name="_token" value={{ csrf_token() }} id="token">
<input type="hidden" id="empleado_id" name="empleado_id" value="{{$employee->emp_id}}">


<div class="form-group">
  {!! Form::label('codigodeincidencia_id', 'Codigo de Incidencia') !!}
  {!! Form::select('codigodeincidencia_id', $codigosdeincidencias, null, [
    'id' => 'codigo',
    'class' => 'form-control',
    'placeholder' => 'Selecciona una incidencia',
    'required'
  ]) !!}

</div>

<div id="periodo" class="form-group well well-sm">
  {!! Form::label('periodo_id', 'Periodo') !!}
  <select class="form-control" id="periodo_id" name="periodo_id">
    <option value="">Selecciona un Periodo</option>
    @foreach($periodos as $periodo)
      <option value="{{$periodo->id}}">{{$periodo->periodo}}/{{$periodo->year}}</option>
    @endforeach
  </select>
  {!! Form::label('saltar_validacion', 'Ignorar validación de dias vacacionales: ') !!}
    <input name="saltar_validacion" id="saltar_validacion" type="checkbox" />

</div>
<div id="medicos" class="form-group well well-sm">
  {!! Form::label('medico_id', 'Medico') !!}
    <select id="medico_id" name="medico_id" class="form-control">
         <option value="">Selecciona un medico</option>
            @foreach($medicos as $medico)
             <option value="{{ $medico->id }}" >{{ $medico->num_empleado }} - {{ $medico->father_lastname }} {{ $medico->mother_lastname }} {{ $medico->name }} </option>
            @endforeach
    </select>


  {!! Form::label('diagnostico', 'Diagnostico') !!}
  {!! Form::text('diagnostico', null, [

    'class' => 'form-control',
    'placeholder' => 'Diagnostico',
   ]) !!}

   {!! Form::label('fecha_expedida', 'Fecha Expedida') !!}
   {!! Form::text('fecha_expedida', null, [
    'class' => 'form-control',
    'placeholder' => 'Fecha Expedida',
    'id' => 'datepicker_expedida'
    ]) !!}
    {!! Form::label('num_licencia', 'Folio') !!}
  {!! Form::text('num_licencia', null, [
    'class' => 'form-control',
    'placeholder' => 'Folio',

   ]) !!}
   <div id="div_saltar_validacion_inca">
      {!! Form::label('saltar_validacion_inca', 'Ignorar validación de Incapacidades: ') !!}
      <input name="saltar_validacion_inca" id="saltar_validacion_inca" type="checkbox" />
   </div>

</div>
<div id="otorgado" class="well well-sm">
  {!! Form::label('otorgado_id', 'Razón de otorgado') !!}
  {!! Form::text('otorgado_id', null, [
    'class' => 'form-control',
    'placeholder' => 'Razón otorgado',
   ]) !!}
</div>

<div id="becas" class="well well-sm">
    {!! Form::label('becas_comments', 'Dictamen de beca') !!}
    {!! Form::text('becas_comments', null, [
      'class' => 'form-control',
      'placeholder' => 'Dictamen de beca',
     ]) !!}
  </div>

  <div id="horas_otorgadas_div" class="well well-sm">
    {!! Form::label('horas_otorgadas', 'Oficio') !!}
    {!! Form::text('horas_otorgadas', null, [
      'class' => 'form-control',
      'placeholder' => 'Ingresa el acta u oficio.',
     ]) !!}
  </div>

<div id="div_saltar_validacion_lic" class="well well-sm">
      {!! Form::label('saltar_validacion_lic', 'Ignorar validación de Licencias Economicas: ') !!}
      <input name="saltar_validacion_lic" id="saltar_validacion_lic" type="checkbox" />
</div>
<div id="coberturaTXT" class="well well-sm">


        {!! Form::label('cobertura_txt', 'Sustituto') !!}
        {!! Form::text('cobertura_txt', null, [
          'class' => 'form-control',
          'placeholder' => 'Ingresar nombre completo del sustituto',
         ]) !!}

      <br>
      <div>
        {!! Form::label('saltar_validacion_txt', 'Ignorar validación de TXT: ') !!}
        <input name="saltar_validacion_txt" id="saltar_validacion_txt" type="checkbox" />
    </div>
</div>

<div id="pendientes" class="well well-sm">
  {!! Form::label('pendientes_com', 'Pendientes') !!}
  {!! Form::text('pendientes_com', null, [
    'class' => 'form-control',
    'placeholder' => 'Pendientes',
   ]) !!}
</div>



<div id="qnas" class="form-group well well-sm">
  {!! Form::label('qna_id', 'Selecciona la Quincena.') !!}
  <select class="form-control" id="qna_id" name="qna_id">
    <option value="" selected disabled></option>
    @foreach($qnas as $qna)
      <option value="{{$qna->id}}">{{$qna->qna}}/{{$qna->year}} - {{ $qna->description }}</option>
    @endforeach
  </select>
</div>

<div id="ingresar_fechas" class="pull-left">Ingresar Fechas</div>
<br>

<div class="form-group col-sm-4">

  {!! Form::text('fecha_inicio', null, [
    'class' => 'form-control',
    'placeholder' => 'DD/MM/AAAA',
    'required',
    'autocomplete' => 'off',
    'id' => 'datepicker_inicial'
  ]) !!}

</div>
<div class="form-group col-sm-4">

  {!! Form::text('fecha_final', null, [
    'class' => 'form-control',
    'placeholder' => 'DD/MM/AAAA',
    'required',
    'autocomplete' => 'off',
    'id' => 'datepicker_final'
  ]) !!}

</div>



<div class="form-group pull-right">
  {!! link_to('#', $title='Registrar', $attributes= ['id' => 'register', 'class' => 'btn btn-primary'], $secure=null) !!}

</div>
