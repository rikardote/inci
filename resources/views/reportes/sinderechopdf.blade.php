<table border="1" cellpadding="3" cellspacing="0" bordercolor="#000000" style="font-size:10pt font-family:family:Arial; width: 100%;  ">
     <thead>
     <tr>
        	<td align="center"td><strong>No.Emp.</strong></td>
          <td align="center"td><strong>Nombre</strong ></td>
          <td align="center"td><strong>  Clave  </strong></td>
          <td align="center"><strong>Denominacion de puesto</strong></td>
        </tr>
      </thead>

      <tbody>
        @foreach ($incidencias as $empleado)
          <tr> 
         	   <td align="center">{{$empleado->num_empleado}}</td>	
      		   <td>{{$empleado->father_lastname}} {{$empleado->mother_lastname}} {{$empleado->name}}</td>
             <td align="center">{{$empleado->clave}}</td>
             <td align="left">{{$empleado->puesto}}</td>
		      </tr> 
         @endforeach
      </tbody>
       
</table>
