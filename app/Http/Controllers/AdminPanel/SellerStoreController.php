<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Http\Models\Users;
use App\Http\Models\Seller;
use App\Http\Models\SellerStore;
use App\Http\Models\SellerProduct;
use App\Http\Models\Product;
use App\Http\Models\Cart;
use App\Http\Models\Order;
use App\Http\Models\OrderDetails;
use App\Http\Models\City;
use GuzzleHttp\Psr7\Response;

class SellerStoreController extends Controller
{
    public function __construct()
    {
        $this->users = new Users();
        $this->seller = new Seller();
        $this->sellerstore = new SellerStore();
        $this->sellerproduct = new SellerProduct();
        $this->product = new Product();
        $this->cart =new Cart();
        $this->order  = new Order();
        $this->orderdetails = new OrderDetails();
        $this->city = new City();
    }

    public function getSellerStre($id)
    {

        $output = $this->seller
            ->leftjoin($this->users->getTable(), $this->seller->getTable() . '.UserID', '=', $this->users->getTable() . '.UserID')
            ->leftjoin($this->sellerstore->getTable(), $this->seller->getTable() . '.SellerID', '=', $this->sellerstore->getTable() . '.SellerID')
            ->leftjoin($this->city->getTable(), $this->seller->getTable() . '.CityID', '=', $this->city->getTable() . '.CityID')
            ->where($this->seller->getTable() . '.UserID', '=', $id)
            ->groupby($this->seller->getTable() . '.SellerID')
            ->get();
        return Response()->json($output);
    }

    public function setNewSellerStore()
    {
        $input = Request()->all();
        $insert = $this->seller->create($input);
        $input['SellerID'] = $insert['SellerId'];

        $SellerID = $insert['SellerId'];
        $insert = $this->sellerstore->create($input);


        $output = $this->seller
            ->leftjoin($this->users->getTable(), $this->seller->getTable() . '.UserID', '=', $this->users->getTable() . '.UserID')
            ->leftjoin($this->sellerstore->getTable(), $this->seller->getTable() . '.SellerID', '=', $this->sellerstore->getTable() . '.SellerID')
            ->where($this->seller->getTable() . '.SellerID', '=', $SellerID)
            ->get();
        return Response()->json($output);

    }

    public function upDateSellerStore($id,$StoreID)
    {

        $input = Request()->all();
        $this->seller->find($id)->update([
            "UserID" =>$input['UserID'],
            "name" =>$input['name'],
            "nameen" =>$input['nameen'],
            "Descrption" =>$input['Descrption'],
            "DescrptionEn" =>$input['DescrptionEn'],
            "CityID"=>$input['CityID']

        ]);
        $this->sellerstore->where($this->sellerstore->getTable() . '.StoreID', '=', $StoreID)->update([
            "SellerID" =>$input['SellerID'],
            "Type" =>1,
            "Long" =>$input['Long'],
            "Lat" =>$input['Lat']
        ]);

        $output = $this->seller
            ->leftjoin($this->users->getTable(), $this->seller->getTable() . '.UserID', '=', $this->users->getTable() . '.UserID')
            ->leftjoin($this->sellerstore->getTable(), $this->seller->getTable() . '.SellerID', '=', $this->sellerstore->getTable() . '.SellerID')
            ->leftjoin($this->city->getTable(), $this->seller->getTable() . '.CityID', '=', $this->city->getTable() . '.CityID')
            ->where($this->seller->getTable() . '.SellerID', '=', $id)
            ->get();
        return Response()->json($output);

    }

    public function deleteSellerStore($id)
    {
        $check = $this->sellerproduct->where($this->sellerproduct->getTable() . '.SellerID', '=', $id)->get();
        if (count($check) > 0) {
            return ['state' => 201];
        } else {
            $this->seller->where($this->seller->getTable() . '.SellerID', '=', $id)->delete();
            $this->sellerstore->where($this->sellerstore->getTable() . '.SellerID', '=', $id)->delete();
            return ['state' => 202];
        }
    }

    public function assginproduct()
    {
        $input = Request()->all();

        $output = $this->sellerproduct->create($input);

        $SellerPrductID = $output->SellerPrductID;
        $output = $this->sellerproduct
            ->leftjoin($this->product->getTable(), $this->sellerproduct->getTable() . '.ProductID', '=', $this->product->getTable() . '.ProductID')
            ->where($this->sellerproduct->getTable() . '.SellerPrductID', '=', $SellerPrductID)
            ->get();
        return Response()->json($output);

    }

    public function getproductforseller($id)
    {
        $output = $this->sellerproduct
            ->leftjoin($this->product->getTable(), $this->sellerproduct->getTable() . '.ProductID', '=', $this->product->getTable() . '.ProductID')
            ->where($this->sellerproduct->getTable() . '.StoreID', '=', $id)
            ->groupby($this->sellerproduct->getTable().'.SellerPrductID')
            ->get();
        return Response()->json($output);

    }

//    public function deleteProdutFroSellerStore($id){
////        $check = $this->sellerproduct
////            ->leftjoin($this->cart->getTable(),$this->sellerproduct->getTable())
//
//    }
    public function DeleteProductFromStore($id,$SellerPrductID){

        $check = $this->sellerproduct
            ->Join($this->orderdetails->getTable(),$this->sellerproduct->getTable().'.ProductID','=',$this->orderdetails->getTable().'.ProductID')
//            ->leftJoin($this->order->getTable(),$this->orderdetails->getTable().'.OrderID','=',$this->order->getTable().'.OrderID')
            ->where($this->sellerproduct->getTable().'.ProductID','=',$id)
            ->get();
//        return $check;
        if(count($check) > 0){
            return ['state'=> 203];
        }else{
          $this->sellerproduct->where('SellerPrductID','=',$SellerPrductID)->delete();
            return ['state'=> 202];
        }
    }

}
