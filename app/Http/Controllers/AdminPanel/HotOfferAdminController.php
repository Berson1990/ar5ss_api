<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Http\Models\HotOffer;
use App\Http\Models\Product;


class HotOfferAdminController extends Controller
{
    public function __construct()
    {
        $this->product = new Product();
        $this->hotoffer = new HotOffer();
    }

    public function getHotoffer()
    {
        $output = $this->hotoffer
            ->join($this->product->getTable(), $this->hotoffer->getTable() . '.HotOfferId', '=', $this->product->getTable() . '.HotOfferId')
            ->get();
        return Response()->json($output);
    }

    public function setHotoffer()
    {
        $input = Request()->all();
        $output = $this->hotoffer->create($input);
        $HotOfferID = $output['HotOfferId'];
        $ProductID = $input['ProductID'];
        $obj = ['HotOfferID' => $HotOfferID];

        $output = $this->product->where('ProductID', '=', $ProductID)->update($obj);

        $output = $this->hotoffer
            ->join($this->product->getTable(), $this->hotoffer->getTable() . '.HotOfferId', '=', $this->product->getTable() . '.HotOfferId')
            ->where($this->hotoffer->getTable() . '.HotOfferID', '=', $HotOfferID)->get();

        return Response()->json($output);
    }

    public function update($id)
    {

        $input = Request()->all();
        $output = $this->hotoffer->find($id)->update($input);
        $ProductID = $input['ProductID'];
        $this->product->where('ProductID', '=', $ProductID)->update([
            "HotOfferID" => $id
        ]);

        $output = $this->hotoffer
            ->join($this->product->getTable(), $this->hotoffer->getTable() . '.HotOfferId', '=', $this->product->getTable() . '.HotOfferId')
            ->where($this->hotoffer->getTable() . '.HotOfferID', '=', $id)->get();
        return Response()->json($output);

    }

    public function delete($id)
    {


        $this->hotoffer->where($this->hotoffer->getTable() . '.HotOfferID', '=', $id)->delete();

        $ProductID = $this->product->where('HotOfferID', '=', $id)->get();
        $ProductID = $ProductID['ProductID'];
        $this->product->where('ProductID', '=', $ProductID)->update([
            "HotOfferID" => 0
        ]);
        $output = ['state' => '400'];
        return Response()->json($output);
    }

    public function product()
    {
        $output = $this->product()->get();
        return Response()->json($output);
    }

    public function ProductHotoffer($id)
    {
        $output = $this->product
            ->where($this->product->getTable() . '.HotOfferID', '=', $id)
            ->get();
        return Response()->json($output);
    }

    public function ProducttoHotOffer()
    {
        $input = Request()->all();
        $output = $this->hotoffer->create($input);
        $HotOfferID = $output->HotOfferID;
        $ProductID = $input['ProductID'];
        $this->product->find($ProductID)->update([
            "HotOfferID" => $HotOfferID
        ]);
        $output = $this->product
            ->where($this->product->getTable() . '.ProductID', '=', $ProductID)
            ->get();
        return Response()->json($output);

    }

    public function RemoveHotOffer($id)
    {

        $output = $this->product->find($id)->update([
            "HotOfferID" => 0
        ]);

        return Response()->json($output);

    }
}
