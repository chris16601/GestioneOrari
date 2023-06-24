<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Charts\oreLavoro;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    public function index( oreLavoro $chart)
    {
        return view('page.home', ['chart' => $chart->build()]);
    }
}
