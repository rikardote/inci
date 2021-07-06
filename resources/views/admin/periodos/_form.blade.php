<div class="form-group">
  {!! Form::label('periodo', 'Periodo') !!}
  
  {!! Form::text('periodo', null, [
    'class' => 'form-control',
    'placeholder' => 'Periodo', 
    'required'
  ]) !!}
</div>
  
<div class="form-group">
  {!! Form::label('year', 'Año') !!}
  {!! Form::text('year', null, [
    'class' => 'form-control',
    'placeholder' => 'Año', 
    'required'
  ]) !!}
</div>


<div class="form-group">
  {!! Form::submit('Registrar', ['class' => 'btn btn-primary']) !!}
</div>