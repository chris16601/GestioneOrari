<?php

namespace App\Http\Controllers;

use App\Exports\ExcelExport;
use App\Exports\OrarioExport;
use App\Http\Controllers\Controller;
use App\Models\tc_giornataLavorativa;
use App\Models\tt_mesi;
use App\Models\tt_tipoGiornata;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class OrarioController extends Controller
{
    public function index(){
        return view('page.ore', [
            'tipoGiornata' => tt_tipoGiornata::all(),
            'totale' => tc_giornataLavorativa::where('id_tipo_Giornata', '=', 2)->sum('ore_fatte'),
            'anni' => tc_giornataLavorativa::select(DB::raw('YEAR(data_giornata) as year'))
                                            ->distinct('year')
                                            ->get(),
            'mesi' => tt_mesi::all(),
        ]);
    }

    public function getOrario($mese, $anno){
        //dd($mese);
        //dd($anno);
        if(is_numeric($mese) && is_numeric($anno)){
            //dd('numeric');
            $query = tc_giornataLavorativa::orderBy('data_giornata', 'DESC')
                                            ->whereMonth('data_giornata', '=', $mese)
                                            ->whereYear('data_giornata', '=', $anno)
                                            ->get();

            $sum = tc_giornataLavorativa::orderBy('data_giornata', 'DESC')
                                            ->whereMonth('data_giornata', '=', $mese)
                                            ->whereYear('data_giornata', '=', $anno)
                                            ->sum('ore_fatte');
        }else{
            //dd('nonNumeric');
            $query = tc_giornataLavorativa::orderBy('data_giornata', 'DESC')
                                            ->whereMonth('data_giornata', '=', date('n'))
                                            ->whereYear('data_giornata', '=', date('Y'))
                                            ->get();

            $sum = tc_giornataLavorativa::orderBy('data_giornata', 'DESC')
                                            ->whereMonth('data_giornata', '=', date('n'))
                                            ->whereYear('data_giornata', '=', date('Y'))
                                            ->sum('ore_fatte');
        }
        return datatables($query)
                    ->editColumn('data', function ($data){
                        $american_date = strtotime($data->data_giornata);
                        $european_date = date('d-m-Y', $american_date);

                        return $european_date;
                    })
                    ->editColumn('a_ora', function ($data){
                        return $data->a_ora;
                    })
                    ->editColumn('da_ora', function ($data){
                        return $data->da_ora;
                    })
                    ->editColumn('ore_fatte', function ($data){
                        return $data->ore_fatte;
                    })
                    ->editColumn('tipo_giornata', function ($data){
                        return $data->tipologiaGiornata->descrizione;
                    })
                    ->addColumn('totale', function () use ($sum) {
                        return $sum;
                    })
                    ->addColumn('action', function ($data){
                        return $this->button($data);
                    })
                    ->editColumn('ferie', function ($data){
                        $ferie = tc_giornataLavorativa::where('id_tipo_Giornata', '=', 1)->count();

                        return $ferie;
                    })
                    ->toJson();
    }

    private function button($data){
        $editUrl = '/edit-hour/' . $data->id_giornataLavorativa;
        $deleteUrl = '/delete-hour/' . $data->id_giornataLavorativa;

        return "
                <a data-value='$data->id' href='$editUrl' title='Modifica'><i class='fa-solid fa-pen-to-square' style='color: #D2691E;'></i></a>
                <a data-value='$data->id' onclick='confirmDelete()' href='$deleteUrl' title='Elimina'><i class='fa-solid fa-trash' style='color: #D2691E;'></i></a>
        ";
    }

    public function saveOrario(Request $request){
        $orario = new tc_giornataLavorativa();
        //dd($request);
        $orario->data_giornata = $request->data_giornata;
        $orario->da_ora = $request->da_ora_edit;
        $orario->a_ora = $request->a_ora_edit;
        $orario->ore_fatte = $request->ore;
        $orario->id_tipo_giornata = $request->tipo_giornata;

        $orario->save();

        return redirect('/hour')->with('success', 'Orario aggiunto correttamente');


    }

    public function deleteOrario($id){
        //dd($id);
        $orario = tc_giornataLavorativa::find($id);
        $orario->delete();

        return redirect('/hour')->with(['success' => 'Orario Eliminato con successo']);


    }

    public function editHour($id){
        $orario = tc_giornataLavorativa::where('id_giornataLavorativa', $id)
                                        ->select("*", DB::raw('SUBSTRING(da_ora, 1, 5) AS formatted_time_da'), DB::raw('SUBSTRING(a_ora, 1, 5) AS formatted_time_a'))
                                        ->first();
        $openModal = true;

        return view('page.ore', [
            'openModal' => $openModal,
            'orario' => $orario,
            'tipoGiornata' => tt_tipoGiornata::all(),
            'totale' => tc_giornataLavorativa::sum('ore_fatte'),
            'anni' => tc_giornataLavorativa::select(DB::raw('YEAR(data_giornata) as year'))
                                            ->distinct('year')
                                            ->get(),
            'mesi' => tt_mesi::all(),
        ]);
    }

    public function editHourSave(Request $request){
        $orario = tc_giornataLavorativa::find($request->id); // Trova l'elemento da aggiornare
        //dd($request);

        // Aggiorna i campi dell'elemento con i dati forniti nella richiesta
        $orario->data_giornata = $request->data_giornata;
        $orario->da_ora = $request->da_ora_edit;
        $orario->a_ora = $request->a_ora_edit;
        $orario->ore_fatte = $request->ore_edit;
        $orario->id_tipo_giornata  = $request->tipo_giornata;


        // ...aggiungi ulteriori campi che desideri aggiornare...

        // Salva le modifiche
        $orario->save();

        return redirect('/hour');

    }

    public function exportPdf($mese, $anno) {
        //$excel = Excel::download(new OrarioExport($mese, $anno), 'prova.xlsx');

        $orario = tc_giornataLavorativa::orderBy('data_giornata', 'DESC')
                                            ->whereMonth('data_giornata', '=', $mese)
                                            ->whereYear('data_giornata', '=', $anno)
                                            ->get();

        $sum = tc_giornataLavorativa::orderBy('data_giornata', 'DESC')
                                        ->whereMonth('data_giornata', '=', $mese)
                                        ->whereYear('data_giornata', '=', $anno)
                                        ->sum('ore_fatte');

        $ferie = tc_giornataLavorativa::orderBy('data_giornata', 'DESC')
                                        ->whereMonth('data_giornata', '=', $mese)
                                        ->whereYear('data_giornata', '=', $anno)
                                        ->where('id_tipo_Giornata', '=', 1)
                                        ->count();

        $data = [
            'orario' => $orario,
            'sum' => $sum,
            'ferie' => $ferie,
            'mese' => $mese,
            'anno' => $anno,
        ];
        $pdf = PDF::loadView('page.orarioExport', $data);

        return $pdf->stream($mese . '/' . $anno . '.pdf');
    }

    public function exportExcel($mese, $anno){
        $orario = tc_giornataLavorativa::orderBy('data_giornata', 'DESC')
                                            ->whereMonth('data_giornata', '=', $mese)
                                            ->whereYear('data_giornata', '=', $anno)
                                            ->get();

        $sum = tc_giornataLavorativa::orderBy('data_giornata', 'DESC')
                                        ->whereMonth('data_giornata', '=', $mese)
                                        ->whereYear('data_giornata', '=', $anno)
                                        ->sum('ore_fatte');

        $ferie = tc_giornataLavorativa::orderBy('data_giornata', 'DESC')
                                        ->whereMonth('data_giornata', '=', $mese)
                                        ->whereYear('data_giornata', '=', $anno)
                                        ->where('id_tipo_Giornata', '=', 1)
                                        ->count();

        return Excel::download(new OrarioExport($mese, $anno), 'prova.xlsx');
    }


}
