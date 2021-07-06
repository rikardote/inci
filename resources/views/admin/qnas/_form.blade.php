<div class="form-group">
  {!! Form::label('qna', 'Qna') !!}
  
  {!! Form::text('qna', null, [
    'class' => 'form-control',
    'placeholder' => 'Qna', 
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
  {!! Form::label('description', 'Descripcion') !!}
  {!! Form::text('description', null, [
    'class' => 'form-control',
    'placeholder' => 'Descripcion', 
    'required'
  ]) !!}
</div>


<div class="form-group">
  {!! Form::submit('Registrar', ['class' => 'btn btn-primary']) !!}
</div>