@if(isset($employee))

    <div class="social">
        <ul>
        <!--<li><a data-url="{{ route('biometrico.show', [$employee->num_empleado]) }}" class="load-form-modal panelColorGreen" data-toggle ="modal" data-target='#form-modal'><i class="fa fa-plus "></i> Ver Checadas</a></li>
            -->
            <li><a data-url="{{ route('incidencias.create.comment', [$employee->emp_id]) }}" class="load-form-modal panelColorGreen" data-toggle ="modal" data-target='#form-modal'><i class="fa fa-plus "></i> Comentario</a></li>
        </ul>
    </div>
            <div class="notice notice-info notice-sm">
                <h2 align="center"><strong>{{$employee->num_empleado}} - {{$employee->name}} {{$employee->father_lastname}} {{$employee->mother_lastname}}</strong></h2>  
            </div>

              <div align="center">
              @if($employee->code == "00104")
                {{str_pad("00105", 5, '0', STR_PAD_LEFT)}} - {{$employee->description}}
              @else
                {{str_pad($employee->code, 5, '0', STR_PAD_LEFT)}} - {{$employee->description}}
              @endif
              </div>
              <div align="center">{{$employee->puesto}}</div>
                
                <div align="center" >{{$employee->horario}} | {{$employee->jornada}} &nbsp;
                {{$employee->estancia ? '| ESTANCIA &nbsp;':''}} 
                {{$employee->comisionado ? '| COMISIONADO &nbsp;':''}}
                <br>
                {{$employee->lactancia ? 'LACTANCIA: '.fecha_dmy($employee->lactancia_inicio)." AL ".fecha_dmy($employee->lactancia_fin) :''}} 
                

                </div>
                <div align="center">
                  @if(isset($comment))

                     {!! Form::label('', $comment->comment) !!}

                  @endif
              </div>

@endif
