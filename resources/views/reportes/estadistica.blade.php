{{-- filepath: d:\swtools\laragon\www\incidencias\resources\views\reportes\estadistica.blade.php --}}
<table class="table table-bordered">
    <tr align="center">
        <td>01</td>
        <td>02</td>
        <td>03</td>
        <td>04</td>
        <td>08</td>
        <td>09</td>
        <td>10</td>
        <td>14</td>
        <td>15</td>
        <td>17</td>
        <td>30</td>
        <td>40</td>
        <td>41</td>
        <td>42</td>
        <td>46</td>
        <td>47</td>
        <td>48</td>
        <td>49</td>
        <td>51</td>
        <td>53</td>
        <td>54</td>
        <td>55</td>
        <td>60</td>
        <td>61</td>
        <td>62</td>
        <td>63</td>
        <td>94</td>
        <td>100</td>
        <td>Total</td>

    </tr>

    <tr align="center">
        @php
            $total = 0;
        @endphp
        @foreach([1, 2, 3, 4, 8, 9, 10, 14, 15, 17, 30, 40, 41, 42, 46, 47, 48, 49, 51, 53, 54, 55, 60, 61, 62, 63, 94, 100] as $codigo)
            @php
                $codigoStr = sprintf("%02d", $codigo);
                $valor = $incidencias['codigo_' . $codigoStr] ?? 0;
                $total += $valor;
            @endphp
            <td><span class="badge">{{ $valor }}</span></td>
        @endforeach
        <td><span class="badge">{{ $total }}</span></td>
    </tr>
</table>
