<html>
<table>
<tr>
	</tr>
	<tr>
		<td></td>
	</tr>
	<tr>
		<td colspan="4" style="text-align: center"><b></b></td>
		<td colspan="8" style="text-align: center"><b></b></td>
	</tr>
	<tr>

		<td colspan="8" style="text-align: center"><b></b></td>
	</tr>
	<tr>
		<td colspan="4" style="text-align: center"><b></b></td>
		<td colspan="8"></td>
	</tr>
	<tr><td colspan="14"></td></tr>

    <tr>
        <td>{{ $mese }}/{{ $anno }}</td>
    </tr>
	<tr>
		<td style="background-color: #eeece1; border: 1px solid #C0C0C0; text-align: center"><b>DATA</b></td>
		<td style="background-color: #eeece1; border: 1px solid #C0C0C0; text-align: center"><b>DA ORA</b></td>
		<td style="background-color: #eeece1; border: 1px solid #C0C0C0; text-align: center"><b>A ORA</b></td>
		<td style="background-color: #eeece1; border: 1px solid #C0C0C0; text-align: center"><b>ORE FATTE</b></td>
        <td style="background-color: #eeece1; border: 1px solid #C0C0C0; text-align: center"><b>TIPO GIORNATA</b></td>
	</tr>

    @foreach($orario as $ora)
        <tr>
            <td style="text-align: center">{{ $ora->data_giornata }}</td>
            <td style="text-align: center">{{ $ora->da_ora }}</td>
            <td style="text-align: center">{{ $ora->a_ora }}</td>
            <td style="text-align: center">{{ $ora->ore_fatte }}</td>
            <td style="text-align: center">{{ $ora->tipologiaGiornata->descrizione }}</td>
        </tr>

    @endforeach
    <tr>
        <td style="text-align: center"><b>Ore Totali Fatte: {{ $sum }}, Giorni di Ferie: {{ $ferie }}</b></td>
    </tr>
</table>
</html>
