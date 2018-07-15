<?php

namespace App\Http\Controllers;

use App\Http\Models\Order;
use App\Http\Models\Product;
use App\Http\Models\Rate;
use App\Http\Models\Users;
use DB;


class RateController extends Controller
{

    public function __construct()
    {
        $this->rate = new Rate();
        $this->product = new Product();
        $this->users = new Users();
        $this->order = new Order();
    }

    public function NewRate()
    {
        $input = Request()->all();

        $check = $this->rate
            ->where($this->rate->getTable() . '.ProductID', '=', $input['ProductID'])
            ->where($this->rate->getTable() . '.UserID', '=', $input['UserID'])
            ->get();
        if (count($check) > 0) {
             $output = ['Error' => 'You Are Rated This Product Before'];
        } else {


            $this->rate->create($input);

            $output = ['state' => '202'];

            $this->order->where($this->order->getTable() . '.OrderID', '=', $input['OrderID'])
                ->update(['OrderState' => 2]);

            /* Insert Real Rate IN tblproduct Start*/
            $ProductID = $input['ProductID'];
            $AllRate = $this->rate
                ->select(
                    DB::raw("sum(tblrate.Rate)as TotalRate"),
                    DB::raw("Count(tblrate.Rate)as CountRate")
                )
                ->where($this->rate->getTable() . '.ProductID', '=', $ProductID)
                ->get();
            $FinalRate = $AllRate['0']['TotalRate'] / $AllRate['0']['CountRate'];
            $this->product->find($ProductID)->update([
                "Rate" => $FinalRate
            ]);
            /* end*/
        }

        return Response()->json($output);
    }

}
