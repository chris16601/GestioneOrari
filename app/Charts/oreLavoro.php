<?php

namespace App\Charts;

use App\Models\tc_giornataLavorativa;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class oreLavoro
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\BarChart
    {

    /*$orario = tc_giornataLavorativa::all();
    $days = cal_days_in_month(CAL_GREGORIAN, date('m'), 2023);

    $data = [];
    for ($i = 1; $i <= $days; $i++) {
        // Ottenere il valore corrispondente al giorno $i e aggiungerlo all'array $data
        $value = // Calcola il valore per il giorno $i
        $data[] = $value;
    }

    return $this->chart->barChart()
        ->setTitle('Ore di Lavoro')
        ->setSubtitle(date('F'))
        ->addData('San Francisco', $data)
        ->setXAxis(range(1, $days));
}*/
        $orario = tc_giornataLavorativa::all();
            //dd($ora);
            $days = cal_days_in_month(CAL_GREGORIAN, date('m'), 2023);

            for ($mese = 1; $mese <= 12; $mese++) {
                $ore_mese = tc_giornataLavorativa::whereMonth('data_giornata', '=', $mese)
                    ->whereYear('data_giornata', '=', date('Y'))
                    ->sum('ore_fatte');
                $ore_per_mese[$mese - 1] = $ore_mese;
            }
            //dd($ore_per_mese);


            return $this->chart->barChart()
            ->setTitle('Ore di Lavoro')
            ->setSubtitle('Gen-Dec 2023')
            ->addData('', $ore_per_mese,)
            ->setXAxis(['Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre']);

    }
}
