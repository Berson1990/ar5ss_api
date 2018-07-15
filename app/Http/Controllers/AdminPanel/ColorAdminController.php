<?php

namespace App\Http\Controllers\AdminPanel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\Color;
class ColorAdminController extends Controller
{
    //
    public function __construct()
    {
        $this->color = new Color();

    }

    public function  GetColor(){
        $output = $this->color->all();
        return $output;
    }
    public function CreateColor(){
        $input =Request()->all();
        $output = $this->color->create($input);
        return $output;
    }
    public  function  UpdateColor($id){
        $input =Request()->all();
        $output = $this->color->find($id)->update($input);
        $output = $this->color->where('ColorID','=',$id)->get();
        return $output;
    }

}
