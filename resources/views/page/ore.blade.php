@include('layouts.navbar')

@if(isset($success))

    <div class="alert alert-danger">{{ $success }}</div>
@endif
<head>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/dropdownButton.css') }}">
</head>

<body>
<div>
    <div class="container bg-#202124 rounded shadow">
        <table class="uk-table uk-table-hover uk-table-striped" style="width:100%; color: white;" id="orario_lavoro" name="orario_lavoro">
            <div id="filtri_all" class="container">
                <!-- Add Button -->
                <div class="col-md-2">
                    <button type="button" id="addButton" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#aggiungi" style="margin-top: 53px">
                        <i class="fa-solid fa-plus" style="color: #202124;"></i>
                    </button>
                </div>
                <!-- Month Filter -->
                <div class="col-md-2" style="margin:34px;">
                    <label id="label_mesi" for="mesi_filtro">FILTRO MESE</label>
                    <select id="mesi_filtro" class="form-control" onchange="updateUrlExcel(), updateUrlPdf()">
                        @foreach($mesi as $mese)
                            <option value="{{ $mese->id_mese }}">{{ $mese->descrizione }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Year Filter -->
                <div class="col-md-2" style="margin:34px;">
                    <label id="label_anni" for="anni_filtro">FILTRO ANNO</label>
                    <select id="anni_filtro" class="form-control" onchange="updateUrlExcel(), updateUrlPdf()">
                        @foreach($anni as $anno)
                            <option value="{{ $anno->year }}">{{ $anno->year }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Excel Download -->
                <div class="col-md-2" style="margin-left: 300px">
                    <div class="dropdown">
                        <button class="mainmenubtn btn btn-primary"><i class="fa-solid fa-cloud-arrow-down"> DOWNLOAD</i></button>
                        <div class="dropdown-child">
                            <a id="excel_button" href=""><i class="fa-solid fa-file-excel" style="color:#D2691E"> EXCEL</i></a>
                            <a id="pdf_button" href=""><i class="fa-solid fa-file-pdf"  style="color:#D2691E"> PDF</i></a>
                        </div>
                    </div>
                </div>

            </div>
            <thead style="background-color: #D2691E;">
                <tr>
                    <th colspan='1' style="color: #202124;"></th>
                    <th style="color: #202124;">DATA</th>
                    <th style="color: #202124;">DA ORA</th>
                    <th style="color: #202124;">A ORA</th>
                    <th style="color: #202124;">ORE FATTE</th>
                    <th style="color: #202124;">TIPO GIORNATA</th>
                    <th id="totale" style="color: #202124;">TOTALE</th>
                    <th id="ferie"  style="color: #202124;">Ferie</th>
                </tr>
            </thead>

            <tbody>
            </tbody>

            <tfoot>
                @if($totale != 0)
                    <tr style="background-color: #D2691E; text-align:center !important">
                        <th colspan="2">TOTALE</th>
                    </tr>
                    <tr>
                            <td></td>
                    </tr>
                @endif
            </tfoot>
        </table>
    </div>
</div>
</body>
@include('layouts.footer')

<script>

        function updateUrlExcel(){
            var mese = $('#mesi_filtro').val();
            var anno = $('#anni_filtro').val();

            //console.log(mese);
            //console.log(anno);
            var excel = document.getElementById("excel_button");
            excel.href = '/export-orario-excel/' + mese + '/' + anno;

            console.log(excel.href);

        }

        function updateUrlPdf(){
            var mese = $('#mesi_filtro').val();
            var anno = $('#anni_filtro').val();

            //console.log(mese);
            //console.log(anno);
            var excel = document.getElementById("pdf_button");
            excel.href = '/export-orario/' + mese + '/' + anno;

            console.log(excel.href);

        }

    $(document).ready( function () {
        var data = new Date();
        var month = data.getMonth();
        console.log( month + 1);

        var year = data.getFullYear();
        console.log( year );

        var selectElement = document.getElementById("mesi_filtro");
        var optionValueToSelect = month + 1;
        selectElement.value = optionValueToSelect;

        var selectElement2 = document.getElementById("anni_filtro");
        var optionValueToSelect2 = year;
        selectElement2.value = optionValueToSelect2;

    });

    $('#mesi_filtro, #anni_filtro').on('change', function() {
        var mese = $('#mesi_filtro').val();
        var anno = $('#anni_filtro').val();
        //console.log(mese);
        //console.log(anno);

        var nuovoUrl = "/get-orario/" + mese + '/' + anno;

        // Aggiorna l'URL della chiamata Ajax della DataTable
        var dataTable = $('#orario_lavoro').DataTable();
        dataTable.ajax.url(nuovoUrl).load();
    });

    $(document).ready( function () {
          $('#orario_lavoro').DataTable({
            "searching": true,
            "paging": false,
            "info": true,
            "processing": true,
            "serverSide": true,
            "language": {
        		"url": "//cdn.datatables.net/plug-ins/1.10.18/i18n/Italian.json"
        	},
        	"order": [], //Initial no order.
            "responsive": true,
            "ajax": "{{ url('/get-orario/mese/anno') }}",
            "columns" : [
              { data: 'action', name: 'action', orderable: false},
              { data: 'data', name: 'data'},
              { data: 'da_ora', name: 'da_ora' },
              { data: 'a_ora', name: 'a_ora' },
              { data: 'ore_fatte', name: 'ore_fatte'},
              { data: 'tipo_giornata', name: 'tipo_giornata'},
              { data: 'totale', name: 'totale', visible: false},
              { data: 'ferie', name: 'ferie', visible: false},
            ],
            // Funzione di callback per il footer
            footerCallback: function(row, data, start, end, display) {
                //console.log(data.length);
                var api = this.api();
            if(data.length != 0){
            // Calcolo del totale
            var ferie = api.column(7).data().reduce(function(a, b) {
                //console.log(a);
                    return a;
            });
            var total = api.column(4).data().reduce(function(a, b) {
                //console.log(b);
                //console.log(a);
                return parseFloat(a) + parseFloat(b);
            });

            // Inserimento del totale nella cella del footer
            $(api.column(0).footer()).text('Totale: ' + total + ' + ' + ' gg Ferie: ' + ferie);
            }else{
                $(api.column(0).footer()).text('');
            }
            }
        });
    });

    function confirmDelete(){
        if(confirm('Sei sicuro di voler cancellare questa giornata lavorativa? Non sarà recuperabile in nessun modo!')){
        }else{
            return
        }
    }
</script>

@if(isset($openModal))
    <script>
        $(document).ready(function() {
                $('#edit').modal('show');
        });
    </script>
@endif






@include('modal.add_modal')

@if(isset($openModal))
    @include('modal.edit_modal')
@endif







<!--<a id="excel_button" name="excel_button" href="">
                                    <button type="button" class="btn btn-primary m-5">
                                        <i class="fa-solid fa-file-excel" style="color: #202124;"> Download </i>
                                    </button>
                            </a>-->
