<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use App\Http\Models\About;
class AboutController extends Controller
{
    //

    public function index(){
        $output= About::all();
        return Response()->json($output['0']);
    }
}
