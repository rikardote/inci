<table cellspacing="0" style="width:100%; border-collapse:collapse; font-family:Arial; font-size:9pt; text-align:center; border:1px solid #999; table-layout:fixed;">
    <thead >
        <tr>
            <th style="width:10%; padding:4px; font-weight:bold; background-color:rgb(171,165,160); border:1px solid #666;">Num Empleado</th>
            <th style="width:30%; padding:4px; font-weight:bold; background-color:rgb(171,165,160); border:1px solid #666;">Empleado</th>
            <th style="width:8%; padding:4px; font-weight:bold; background-color:rgb(171,165,160); border:1px solid #666;">Codigo</th>
            <th style="width:13%; padding:4px; font-weight:bold; background-color:rgb(171,165,160); border:1px solid #666;">Fecha Inicial</th>
            <th style="width:13%; padding:4px; font-weight:bold; background-color:rgb(171,165,160); border:1px solid #666;">Fecha Final</th>
            <th style="width:13%; padding:4px; font-weight:bold; background-color:rgb(171,165,160); border:1px solid #666;">Centro</th>
        </tr>
    </thead>
    <tbody style="font-family:Arial; font-size:9pt;">
        @php
            $tmp = "";
            $isFirst = true;
        @endphp
        @forelse ($incidencias as $incidencia)
            @if (isset($incidencia->num_empleado) && $incidencia->num_empleado == $tmp)
                <tr>
                    <td style="width:13%; padding:2px; border:1px solid #ccc; vertical-align:top; text-align:center;"></td>
                    <td style="width:13%; padding:2px; border:1px solid #ccc; vertical-align:top; text-align:center;"></td>
                    <td style="width:13%; padding:2px; border:1px solid #ccc; vertical-align:top; text-align:center;">
                        @if(isset($incidencia->code))
                            {{ str_pad($incidencia->code, 2, '0', STR_PAD_LEFT) }}
                        @endif
                    </td>
                    <td style="width:13%; padding:2px; border:1px solid #ccc; vertical-align:top; text-align:center;">
                        @if(isset($incidencia->fecha_inicio))
                            {{ fecha_dmy($incidencia->fecha_inicio) }}
                        @endif
                    </td>
                    <td style="width:13%; padding:2px; border:1px solid #ccc; vertical-align:top; text-align:center;">
                        @if(isset($incidencia->fecha_final))
                            {{ fecha_dmy($incidencia->fecha_final) }}
                        @endif
                    </td>

                    <td style="border:1px solid #ccc; padding:2px;"></td>
                </tr>
            @else

                @php $isFirst = false; @endphp
                <tr>
                    <td style="width:10%; padding:2px; border:1px solid #ccc; vertical-align:top; text-align:center; font-weight:bold;">
                        {{ $incidencia->num_empleado ?? '' }}
                    </td>
                    <td style="width:10%; padding:2px; border:1px solid #ccc; vertical-align:top; text-align:left; font-weight:bold;">
                        {{ $incidencia->father_lastname ?? '' }}
                        {{ $incidencia->mother_lastname ?? '' }}
                        {{ $incidencia->name ?? '' }}
                        <p style="font-weight: normal;">{{ $incidencia->puesto ?? '' }}</p>
                    </td>
                    <td style="width:13%; padding:2px; border:1px solid #ccc; vertical-align:top; text-align:center;">
                        @if(isset($incidencia->code))
                            {{ str_pad($incidencia->code, 2, '0', STR_PAD_LEFT) }}
                        @endif
                    </td>
                    <td style="width:13%; padding:2px; border:1px solid #ccc; vertical-align:top; text-align:center;">
                        @if(isset($incidencia->fecha_inicio))
                            {{ fecha_dmy($incidencia->fecha_inicio) }}
                        @endif
                    </td>
                    <td style="width:13%; padding:2px; border:1px solid #ccc; vertical-align:top; text-align:center;">
                        @if(isset($incidencia->fecha_final))
                            {{ fecha_dmy($incidencia->fecha_final) }}
                        @endif
                    </td>

                    <td style="width:13%; padding:2px; border:1px solid #ccc; vertical-align:top; text-align:center;">
                        @if(isset($incidencia->deparment_id) && $incidencia->deparment_id)
                            {{ get_departamento($incidencia->deparment_id) }}
                        @else
                            &nbsp;
                        @endif
                    </td>
                </tr>
                @php $tmp = $incidencia->num_empleado ?? ''; @endphp
            @endif
        @empty
            <tr>
                <td
                    <strong>No hay incidencias que mostrar para esta fecha</strong>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
