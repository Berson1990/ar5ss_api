<?php

namespace App\Http\Controllers\AdminPanel;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use App\Http\Models\About;



class AboutAdminController extends Controller
{
    //
    public function __construct()
    {
        $this->about = new About();

    }

    public function getAbout(){
        $output =$this->about->all();
        return Response()->json ($output);
    }
    public function Update($id){
        $input = Request()->all();
        $this->about->find($id)->update($input);
        $output= $this->about->find($id)->get();
        return Response()->json($output);

    }
}
