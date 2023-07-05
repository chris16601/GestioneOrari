<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\tc_giornataLavorativa;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class OrarioExport implements FromView, ShouldAutoSize
{
protected $mese, $anno;

    function __construct($mese, $anno){
        $this->mese = $mese;
        $this->anno = $anno;
    }

    public function view(): View
    {
        $orario = tc_giornataLavorativa::orderBy('data_giornata', 'DESC')
                                            ->whereMonth('data_giornata', '=', $this->mese)
                                            ->whereYear('data_giornata', '=', $this->anno)
                                            ->get();
        $sum = tc_giornataLavorativa::orderBy('data_giornata', 'DESC')
                                            ->whereMonth('data_giornata', '=', $this->mese)
                                            ->whereYear('data_giornata', '=', $this->anno)
                                            ->sum('ore_fatte');

        $ferie = tc_giornataLavorativa::orderBy('data_giornata', 'DESC')
                                            ->whereMonth('data_giornata', '=', $this->mese)
                                            ->whereYear('data_giornata', '=', $this->anno)
                                            ->where('id_tipo_Giornata', '=', 1)
                                            ->count();

        return view('page.orarioExport', [
            'orario' => $orario,
            'sum' => $sum,
            'ferie' => $ferie,
            'mese' => $this->mese,
            'anno' => $this->anno,
        ]);
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath(public_path('img/LogoGestioneOrario.svg'));
        $drawing->setHeight(120);
        $drawing->setCoordinates('E1');

        return $drawing;
    }
}
