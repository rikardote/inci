
@foreach($checadas as $checada)
    <table class="">
        <th align="center">{{ $checada->num_empleado }} - 
        <th>{{ $checada->father_lastname }} {{ $checada->mother_lastname }} {{ $checada->name }}</th>
        <th align="right">{{$checada->horario}}</th>
    
    </table>
        
@endforeach


