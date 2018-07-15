<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Http\Models\GroupShow;
use App\Http\Models\Product;
use App\Http\Models\MostRequest;
use GuzzleHttp\Psr7\Request;


class GroupShowAdminController extends Controller
{
    public function __construct()
    {
        $this->product = new Product();
        $this->groupshow = new GroupShow();
        $this->mostrequest = new MostRequest();
    }

    public function getGroupshow()
    {
        $output = $this->groupshow
            ->get();
        return Response()->json($output);
    }

    public function getsetting()
    {
        $output = $this->mostrequest->all();
        return Response()->json($output);
    }

    public function setsetting($id)
    {
        $input = Request()->all();
        $this->mostrequest->find($id)->update($input);
        $output = $this->mostrequest->find($id)->get();
        return Response()->json($output);

    }

    public function setGrouShow()
    {
        $input = Request()->all();
        $output = $this->groupshow->create($input);
        $GroupShowID = $output->GroupShowID;
        $output = $this->groupshow->where($this->groupshow->getTable() . '.GroupShowID', '=', $GroupShowID)->get();
        return Response()->json($output);
    }

    public function update($id)
    {

        $input = Request()->all();
        $output = $this->groupshow->find($id)->update($input);
        $output = $this->groupshow->where($this->groupshow->getTable() . '.GroupShowID', '=', $id)->get();
        return Response()->json($output);

    }

    public function delete($id)
    {
        $check = $this->product->where($this->product->getTable() . '.GroupShowID', '=', $id)->get();

        if (count($check) > 0) {

            $output = ['state' => 404];

        } else {

            $this->groupshow->where($this->groupshow->getTable() . '.GroupShowID', '=', $id)->delete();
            $output = ['state' => 400];
        }
        return Response()->json($output);
    }

    public function product()
    {
        $output = $this->product()->get();
        return Response()->json($output);
    }

    public function ProductGroupShow($id)
    {
        $output = $this->product
            ->where($this->product->getTable() . '.GroupShowID', '=', $id)
            ->get();
        return Response()->json($output);
    }

    public function assginProducttoGroup()
    {
        $input = Request()->all();
        $ProductID = $input['ProductID'];
        $this->product->find($ProductID)->update([
            "GroupShowID" => $input["GroupShowID"]
        ]);
        $output = $this->product->with('GroupShow')
            ->where($this->product->getTable() . '.ProductID', '=', $ProductID)
            ->get();
        return Response()->json($output);

    }

    public function RemoveProductGroup($id)
    {


        $output = $this->product->find($id)->update([
            "GroupShowID" => 3
        ]);

        return Response()->json($output);

    }

}
