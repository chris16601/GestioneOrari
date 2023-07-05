<!-- Modal -->
<div class="modal fade" id="edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div style="background-color: #D2691E;" class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Aggiungi Giornata Lavorativa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="background-color: #202124;">
        <form method="POST" action="{{ url('edit-hour-save') }}">
            @csrf
            <div class="row">
                <div class="col md-12 m-2">
                    <label for="data_giornata">Data Giornata Lavorativa</label>
                    <input class="form-control" type="date" name="data_giornata" id="data_giornata" value="{{ $orario->data_giornata }}">
                </div>
            </div>
            <input type="hidden" name="id" id="id" value="{{ $orario->id_giornataLavorativa }}">
            <div class="row">
                <div class="col md-6 m-2">
                    <label for="">Inizio Turno</label>
                    <input class="form-control" type="time" id="da_ora_edit" name="da_ora_edit" value="{{ $orario->formatted_time_da }}" onchange="getOre()">
                </div>
                <div class="col md-6 m-2">
                    <label for="">Fine Turno</label>
                    <input class="form-control" type="time" id="a_ora_edit" name="a_ora_edit" value="{{ $orario->formatted_time_a }}" onchange="getOre()">
                </div>
            </div>
            <div class="row">
                <div class="col md-6 m-2">
                    <label for="tipo_giornata" class="control-label">Tipo Giornata</label>
                        <select id="tipo_giornata" name="tipo_giornata" class="form-control">
                        @foreach($tipoGiornata as $gg)
                            <option value="{{ $gg->id_tipo_giornata }}">{{ $gg->descrizione }}</option>
                        @endforeach
                        </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-5 m-2">
                    <label for="ore_fatte" class="control-label">ORE TOTALI</label>
                    <input class="form-control" id="ore_fatte_edit" name="ore_fatte_edit" value="{{ $orario->ore_fatte }}"readonly>
                </div>

                <div>
                    <input id="ore_edit" name="ore_edit" hidden>
                </div>
            </div>
                <button type="submit" style="float: inline-end;" class="btn btn-primary" name="submit">Salva</button>
        </form>
      </div>
    </div>
  </div>
</div>


<script>
        function getOre(){
            var da_ora = $('#da_ora_edit').val();
            var a_ora = $('#a_ora_edit').val();
            var split = da_ora.split(":");
            var split2 = a_ora.split(":");

            console.log('SPL:',da_ora, 'SPL2:',a_ora);

            var ora_1  = split[0];
            var minuti_1 = split[1];

            var ora_2  = split2[0];
            var minuti_2 = split2[1];

            var data = new Date();
            data.setHours(ora_1);
            data.setMinutes(minuti_1);

            var data_2 = new Date();
            data_2.setHours(ora_2);
            data_2.setMinutes(minuti_2);
            var tot = data_2-data;

            console.log("DATA:", data);
            console.log("DATA2:",data_2);

            convertMsToHoursAndMinutes(tot)

            //$('#ore_fatte').val(ore_totali);
        }

        function convertMsToHoursAndMinutes(ms) {
            const minutes = Math.floor(ms / 60000);
            const hours = Math.floor(minutes / 60);
            const remainingMinutes = minutes % 60;
            if(remainingMinutes > 0) {
                var rMinutes = 5;
            }else{
                var rMinutes = 0;
            }

            console.log('minuti:', minutes);
            console.log('ore', hours);
            console.log('munuti restanti', remainingMinutes);

            $('#ore_fatte_edit').val('Ora: ' + hours + ' Minuti: ' + rMinutes)

            $('#ore_edit').val(hours + '.' + rMinutes);
    }
</script>

