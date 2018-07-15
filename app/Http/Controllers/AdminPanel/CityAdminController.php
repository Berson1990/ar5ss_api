<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\City;
use App\Http\Models\SuppliersSupport;
use DB;

class CityAdminController extends Controller
{
    public function __construct()
    {
        $this->city = new City();
        $this->suppliersupport = new SuppliersSupport();
    }

    public function getAllCity()
    {
        $output = $this->city->all();
        return $output;
    }

    public function CreateCity()
    {
        $input = Request()->all();
        $output = $this->city->create($input);
        return $output;
    }

    public function UpdateCity($id)
    {
        $input = Request()->all();
        $output = $this->city->find($id)->update($input);
        $output = $this->city->where('CityID', '=', $id)->get();
        return $output;
    }

    public function GetCitySupplier($id)
    {
//        $output = $this->suppliersupport
//            ->leftJoin($this->city->getTable(), function ($join) use ($id) {
//
//                $join->on(DB::raw("" . $this->suppliersupport->getTable() . '.SupplierID' . ""), DB::raw('='), DB::raw("'" . $id . "'"))
//                    ->on(DB::raw("" . $this->city->getTable() . '.CityID' . ""), DB::raw('='), DB::raw("" . $this->suppliersupport->getTable() . '.CityID' . ""));
//
//            })
//            ->leftjoin($this->city->getTable(), $this->suppliersupport->getTable() . '.CityID', '=', $this->city->getTable() . '.CityID')
//            ->orWhere($this->suppliersupport->getTable() . '.SupplierID', '=', $id)
//            ->get();
        $output = DB::SELECT("select tblcity.* ,tblsupplierssupport.ID, IFNULL(tblsupplierssupport.CityID ,null) as SupplierCityID from tblcity left join tblsupplierssupport on tblcity.CityID = tblsupplierssupport.CityID where  tblsupplierssupport.SupplierID = '" . $id . "'");
        return $output;

    }

    public function SetMyList()
    {
        $input = Request()->all();
        $output= $this->suppliersupport->create($input);
        $id = $output->ID;
        $output = DB::SELECT("select tblcity.* , tblsupplierssupport.ID,tblsupplierssupport.CityID as SupplierCityID from tblcity left join tblsupplierssupport on tblcity.CityID = tblsupplierssupport.CityID where tblsupplierssupport.ID = '" . $id . "'");
//       $output = $output['0'];
        return $output;
    }
    public function removeFrommylist($id){
        $this->suppliersupport->find($id)->delete();
//        $output = $output['0'];
        return 1;
    }
}
