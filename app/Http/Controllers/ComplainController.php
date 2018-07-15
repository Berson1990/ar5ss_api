<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use App\Http\Models\CompalinType;
use App\Http\Models\Complain;
use App\Http\Models\Users;
use DB;

class ComplainController extends Controller
{


    public function __construct()
    {
        $this->complain = new Complain();
        $this->complaintype = new CompalinType();
        $this->users = new Users();
    }

    public function getComplain()
    {

        $output = DB::SELECT('SELECT * FROM `tblcomplain`  join tblusers on tblcomplain.UserID = tblusers.UserID where tblusers.UseType = 2 and tblcomplain.Hide =0  order by Sort DESC');
//        $output = $this->complain
//            ->join($this->complaintype->getTable(), $this->complain->getTable() . '.ComplainTypeId', '=', $this->complaintype->getTable() . '.ComplainTypeId')
//            ->leftjoin($this->users->getTable(), $this->complain->getTable() . '.UserID', '=', $this->complain->getTable() . '.UserID')
//            ->groupby($this->complain->getTable() . '.ComplainId')
//            ->orderby($this->complain->getTable().'.Sort','DESC')
//            ->get();
        return Response()->json($output);

    }


    public function GetcomplainState($state)
    {

        $output = DB::SELECT('SELECT * FROM `tblcomplain`  join tblusers on tblcomplain.UserID = tblusers.UserID
 where tblusers.UseType = 2  
 and   tblcomplain.Hide = "' . $state . '" 
 order by Sort DESC');

//            ->get();
        return Response()->json($output);

    }
    public function GetcomplainSupplier($state)
    {

        $output = DB::SELECT('SELECT * FROM `tblcomplain`  join tblusers on tblcomplain.UserID = tblusers.UserID
 where tblusers.UseType = 3  
 and   tblcomplain.Hide = "' . $state . '" 
 order by Sort DESC');

//            ->get();
        return Response()->json($output);

    }

    public function HideCompalin($id)
    {

        $this->complain->where($this->complain->getTable().'.ComplainId','=',$id)->update([
            "Hide" => 1
        ]);
    }

    public function getComplainForSuppliers()
    {

        $output = DB::SELECT('SELECT * FROM `tblcomplain`  join tblusers on tblcomplain.UserID = tblusers.UserID
where tblusers.UseType = 3   and  tblcomplain.Hide =  0 order by Sort DESC  ');

        return Response()->json($output);

    }

    public function GetsupplierComplain($id)
    {

        $output = DB::SELECT('SELECT * FROM `tblcomplain`  join tblusers on tblcomplain.UserID = tblusers.UserID
where tblusers.UserID ="' . $id . '" ');

        return Response()->json($output);

    }

    public function GetUserCompalin($id)
    {
        $output = $this->complain
            ->join($this->complaintype->getTable(), $this->complain->getTable() . '.ComplainTypeId', '=', $this->complaintype->getTable() . '.ComplainTypeId')
            ->join($this->users->getTable(), $this->complain->getTable() . '.UserID', '=', $this->complain->getTable() . '.UserID')
            ->where($this->complain->getTable() . '.UserID', '=', $id)
            ->groupby($this->complain->getTable() . '.ComplainId')
            ->orderby($this->complain->getTable() . '.Sort', 'DESC')
            ->get();
        return Response()->json($output);

    }

    public function ReadCompalin($id)
    {
        $output = $this->complain->find($id)->update(['IsRead' => 1]);
        $output = $this->complain->where($this->complain->getTable() . '.ComplainId', '=', $id)->get();
        return Response()->json($output);
    }

    public function Flag($id)
    {
        $output = $this->complain
            ->orderby($this->complain->getTable() . '.Sort', 'DESC')
//            ->get();
            ->first();

        $Sort = $output->Sort;
        $Sort = $Sort + 2;

        $output = $this->complain->find($id)->update(
            ['Sort' => $Sort,
                "Paind" => 1
            ]
        );
        $output = DB::SELECT('SELECT * FROM `tblcomplain`  join tblusers on tblcomplain.UserID = tblusers.UserID
where tblusers.UseType = 2 and tblcomplain.Hide = 0 ORDER By Sort DESC');

        return Response()->json($output);
    }

    public function removePain($id)
    {
        $output = $this->complain
            ->leftJoin($this->users->getTable(), $this->complain->getTable() . '.UserID', '=', $this->users->getTable() . '.UserID')
            ->where($this->complain->getTable() . '.ComplainId', '=', $id)
            ->get();
        $UseType = $output[0]['UseType'];


        $output = $this->complain->find($id)->update([
            'Sort' => 0,
            "Paind" => 0
        ]);
        $output = DB::SELECT('SELECT * FROM `tblcomplain`  join tblusers on tblcomplain.UserID = tblusers.UserID
where tblusers.UseType = "' . $UseType . '" ORDER By Sort DESC');

        return Response()->json($output);
    }

    public function getComplainType()
    {
        $output = CompalinType::all();
        return Response()->json($output);
    }

    public function create()
    {
        $input = Request()->all();
        $output = Complain::create($input);
        return Response()->json($output);
    }

    public function UpdateCompalin($id)
    {
        $input = Request()->all();
        $output = Complain::find($id)->update($input);
        $output = Complain::where($this->complain->getTable() . '.ComplainId', '=', $id)->get();

        return Response()->json($output);

    }

    public function destroy($id)
    {
        $output = Complain::find($id)->delete();
        return ['state' => 202];
    }

}
