<?php

namespace App\Http\Controllers;

use App\Http\Models\Vat;
use Illuminate\Http\Request;
use App\Http\Models\Vata;

class VatController extends Controller
{
    //
    public function getVat(){

        $output = Vat::all();
        return $output[0];
    }
}
