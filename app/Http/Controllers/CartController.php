<?php

namespace App\Http\Controllers;

use App\Http\Models\Brand;
use App\Http\Models\Cart;
use App\Http\Models\Category;
use App\Http\Models\Color;
use App\Http\Models\Favorit;
use App\Http\Models\GroupShow;
use App\Http\Models\Location;
use App\Http\Models\Product;
use App\Http\Models\ProductColor;
use App\Http\Models\ProductDetails;
use App\Http\Models\ProductImage;
use App\Http\Models\ProductPrice;
use App\Http\Models\Seller;
use App\Http\Models\SellerProduct;
use App\Http\Models\SellerStore;
use App\Http\Models\ShipingSetting;
use App\Http\Models\Size;
use App\Http\Models\Users;
use DB;

class CartController extends Controller
{
    public function __construct()
    {
        $this->groupshow = new GroupShow();
        $this->product = new Product();
        $this->sellerproduct = new SellerProduct();
        $this->ProductColor = New ProductColor();
        $this->productDetails = new ProductDetails();
        $this->productImage = new ProductImage();
        $this->ProductPrice = new ProductPrice();
        $this->brand = new Brand();
        $this->size = new Size();
        $this->category = new Category();
        $this->color = new Color();
        $this->favorit = new Favorit();
        $this->cart = new Cart();
        $this->cart = new Cart();
        $this->sellerstore = new SellerStore();
        $this->seller = new Seller();
        $this->shipingsetting = new ShipingSetting();
        $this->user = new Users();
        $this->location = new Location();
    }

    public function getCart($id)
    {
        $output = $this->product
            ->select(
                $this->product->getTable() . '.*',
                $this->cart->getTable() . '.*',
                $this->ProductPrice->getTable() . '.*',
                $this->ProductPrice->getTable() . '.ProductPriceDesc as ProductPrice',
                $this->brand->getTable() . '.*',
                $this->category->getTable() . '.*',
                $this->size->getTable() . '.*',
                $this->ProductColor->getTable() . '.*',
                $this->color->getTable() . '.*',
                $this->productImage->getTable() . '.*',
                $this->sellerproduct->getTable().'.Shiping as Real_Shiping'
            )
            ->leftjoin($this->cart->getTable(), $this->product->getTable() . '.ProductID', '=', $this->cart->getTable() . '.ProductID')
            ->leftjoin($this->ProductPrice->getTable(), $this->product->getTable() . '.ProductID', '=', $this->ProductPrice->getTable() . '.ProductID')
            ->leftjoin($this->brand->getTable(), $this->product->getTable() . '.BrandID', '=', $this->brand->getTable() . '.BrandID')
            ->leftjoin($this->category->getTable(), $this->product->getTable() . '.CategoryID', '=', $this->category->getTable() . '.CategoryID')
            ->leftjoin($this->size->getTable(), $this->product->getTable() . '.SizeID', '=', $this->size->getTable() . '.SizeID')
            ->leftjoin($this->sellerproduct->getTable(), $this->product->getTable() . '.ProductID', '=', $this->sellerproduct->getTable() . '.ProductID')
            ->leftjoin($this->ProductColor->getTable(), $this->product->getTable() . '.ProductID', '=', $this->ProductColor->getTable() . '.ProductID')
            ->leftjoin($this->color->getTable(), $this->ProductColor->getTable() . '.ColorID', '=', $this->color->getTable() . '.ColorID')
            ->leftjoin($this->productImage->getTable(), $this->ProductColor->getTable() . '.ProductColorID', '=', $this->productImage->getTable() . '.ProductColorID')
            ->where($this->cart->getTable() . '.UserID', '=', $id)
            ->where($this->cart->getTable() . '.CartState', '=', 1)
            ->groupby($this->product->getTable() . '.ProductID')
            ->get();
        return Response()->json($output);

    }

    public function getCartForUnRegister($tokenID)
    {

        $output = $this->product
            ->select(
                $this->product->getTable() . '.*',
                $this->cart->getTable() . '.*',
                $this->ProductPrice->getTable() . '.*',
                $this->ProductPrice->getTable() . '.ProductPriceDesc as ProductPrice',
                $this->brand->getTable() . '.*',
                $this->category->getTable() . '.*',
                $this->size->getTable() . '.*',
                $this->ProductColor->getTable() . '.*',
                $this->color->getTable() . '.*',
                $this->productImage->getTable() . '.*',
                $this->sellerproduct->getTable().'.Shiping as Real_Shiping'

            )
            ->leftjoin($this->cart->getTable(), $this->product->getTable() . '.ProductID', '=', $this->cart->getTable() . '.ProductID')
            ->leftjoin($this->ProductPrice->getTable(), $this->product->getTable() . '.ProductID', '=', $this->ProductPrice->getTable() . '.ProductID')->leftjoin($this->brand->getTable(), $this->product->getTable() . '.BrandID', '=', $this->brand->getTable() . '.BrandID')
            ->leftjoin($this->category->getTable(), $this->product->getTable() . '.CategoryID', '=', $this->category->getTable() . '.CategoryID')
            ->leftjoin($this->size->getTable(), $this->product->getTable() . '.SizeID', '=', $this->size->getTable() . '.SizeID')
            ->leftjoin($this->sellerproduct->getTable(), $this->product->getTable() . '.ProductID', '=', $this->sellerproduct->getTable() . '.ProductID')
            ->leftjoin($this->ProductColor->getTable(), $this->product->getTable() . '.ProductID', '=', $this->ProductColor->getTable() . '.ProductID')
            ->leftjoin($this->color->getTable(), $this->ProductColor->getTable() . '.ColorID', '=', $this->color->getTable() . '.ColorID')
            ->leftjoin($this->productImage->getTable(), $this->ProductColor->getTable() . '.ProductColorID', '=', $this->productImage->getTable() . '.ProductColorID')
            ->where($this->cart->getTable() . '.TokenID', '=', $tokenID)
            ->where($this->cart->getTable() . '.CartState', '=', 1)
            ->groupby($this->product->getTable() . '.ProductID')
            ->get();
        return Response()->json($output);

    }

    public function addtocart()
    {
        $input = Request()->all();

        if (Request()->has('UserID')) {

            $check = $this->cart
                ->where($this->cart->getTable() . '.ProductID', '=', $input['ProductID'])
                ->where($this->cart->getTable() . '.UserID', '=', $input['UserID'])
                ->where($this->cart->getTable() . '.CartState', '=', 1)
                ->get();

        } else if (Request()->has('TokenID')) {
            $check = $this->cart
                ->where($this->cart->getTable() . '.ProductID', '=', $input['ProductID'])
                ->where($this->cart->getTable() . '.TokenID', '=', $input['TokenID'])
                ->where($this->cart->getTable() . '.CartState', '=', 1)
                ->get();

        }

        if (Count($check) > 0) {

            return ['error' => 'this already in the cart'];
        } else {

            $output = $this->cart->create($input);
            $CartID = $output['CartID'];
            $SellerID = $input['SellerID'];
            $ProductID = $output['ProductID'];
            $checkofShipping = $this->sellerproduct
                ->where($this->sellerproduct->getTable() . '.SellerID', '=', $SellerID)
                ->where($this->sellerproduct->getTable() . '.ProductID', '=', $ProductID)
                ->get();

            $ShipingState = $checkofShipping['0']['ShipingState'];
            $SellerPrductID = $checkofShipping['0']['SellerPrductID'];
            $Shiping = $checkofShipping['0']['Shiping'];

            if ($ShipingState == 1 && $ShipingState == 0) {

                $this->cart->where($this->cart->getTable() . '.CartID', '=', $CartID)
                    ->update(["Shiping" => $Shiping]);
                return 'true';

            } else {

                // $UserID = $input['UserID'];
                $Lang = $input['Lang'];
                $Lat = $input['Lat'];
                $NearSellerID = $this->sellerstore
                    ->select(
                        $this->sellerstore->getTable() . '.StoreID',
                        DB::raw('Round((3959 * acos(cos(radians(' . $Lat . ')) * cos(radians(' . $this->sellerstore->getTable() . '.Lat' . ')) * 
            cos( radians(' . $this->sellerstore->getTable() . '.Long' . ') - radians(' . $Lang . ')) + sin(radians(' . $Lat . ')) * 
            sin(radians(' . $this->sellerstore->getTable() . '.Long' . '))))) as distance '))
                    ->Having('distance', '>', 50)
                    ->orderby('distance', 'ASC')
                    ->get();

//            $NerLication = $NearSellerID[0];
                global $StoreID;
                foreach ($NearSellerID as $Store) {
                    $StoreID = $Store->StoreID;
                }

                $CountOfKilo = $this->sellerstore
                    ->select(
                        DB::raw('TRUNCATE(DEGREES(ACOS(COS(RADIANS("' . $Lat . '"))
           * COS(RADIANS(tblsellerstore.Lat))
           * COS(RADIANS("' . $Lat . '" - tblsellerstore.Lat))
            + SIN(RADIANS("' . $Lat . '"))
           * SIN(RADIANS(tblsellerstore.Lat)))),2) AS distance_in_km'))
                    ->where($this->sellerstore->getTable() . '.StoreID', '=', $StoreID)
                    ->get();

                global $KiloMets;
                foreach ($CountOfKilo as $CountOfKilo) {
                    $KiloMets = $CountOfKilo->distance_in_km;
                }


                $pricForKilo = $this->shipingsetting
                    ->select(
                        $this->shipingsetting->getTable() . '.Price'
                    )
                    ->leftjoin($this->sellerstore->getTable(), $this->shipingsetting->getTable() . '.SellerID', '=', $this->sellerstore->getTable() . '.SellerID')
                    ->where($this->shipingsetting->getTable() . '.SellerID', '=', $SellerID)
                    ->where($this->shipingsetting->getTable() . '.StoreID', '=', $StoreID)
                    ->groupby($this->shipingsetting->getTable() . '.ShipingSettingID')
                    ->get();
                global  $price;
                foreach ($pricForKilo as $pricForKilo) {
                    $price = $pricForKilo->Price;
                }

                $Shipping = $price * $KiloMets;
                $this->cart->where($this->cart->getTable() . '.CartID', '=', $CartID)
                    ->update(['Shiping' => $Shipping]);

                return 'true';
            }

//
        }


    }


    public function addToCartOffline()
    {

        $input = Request()->all();

        $ProductID = array();
        $UserID = array();
        $TokenID = array();
        $CartID = array();
        $StoreID = array();
        $SellerID = array();
//        $Lang = array();
//        $Lat = array();

        for ($i = 0; $i < count($input); $i++) {

//            $SellerID = $input[$i]['SellerID'];
//            $Lang = $input[$i]['Lang'];
//            $Lat = $input[$i]['Lat'];

            array_push($SellerID, $input[$i]['SellerID']);
//            array_push($Lang, $Lo);
//            array_push($Lat, $La);

            if (isset($input[$i]['UserID'])) {

                array_push($ProductID, $input[$i]['ProductID']);
                array_push($UserID, $input[$i]['UserID']);

                $check = $this->cart
                    ->WhereIn($this->cart->getTable() . '.ProductID', $ProductID)
                    ->WhereIn($this->cart->getTable() . '.UserID', $UserID)
                    ->where($this->cart->getTable() . '.CartState', '=', 1)
                    ->get();

            } else if (isset($input[$i]['TokenID'])) {
                array_push($TokenID, $input[$i]['TokenID']);
                $check = $this->cart
                    ->whereIn($this->cart->getTable() . '.ProductID', $ProductID)
                    ->whereIn($this->cart->getTable() . '.TokenID', $TokenID)
                    ->where($this->cart->getTable() . '.CartState', '=', 1)
                    ->get();

            }
        }//end for

        if (Count($check) > 0) {

            return ['error' => 'this already in the cart'];
        } else {

            for ($i = 0; $i < count($input); $i++) {
                $output = $this->cart->create($input[$i]);

                if (isset($input[$i]['UserID'])) {

                    array_push($ProductID, $input[$i]['ProductID']);
                    array_push($UserID, $input[$i]['UserID']);

                    $outputdata = $this->cart
                        ->WhereIn($this->cart->getTable() . '.ProductID', $ProductID)
                        ->WhereIn($this->cart->getTable() . '.UserID', $UserID)
                        ->where($this->cart->getTable() . '.CartState', '=', 1)
                        ->get();

                } else if (isset($input[$i]['TokenID'])) {
                    array_push($TokenID, $input[$i]['TokenID']);
                    $outputdata = $this->cart
                        ->whereIn($this->cart->getTable() . '.ProductID', $ProductID)
                        ->whereIn($this->cart->getTable() . '.TokenID', $TokenID)
                        ->where($this->cart->getTable() . '.CartState', '=', 1)
                        ->get();

                }
            }

        }


        return 'true';
        //        return $outputdata;
    }


    private function getshpingtoofflinecart($CartID, $SellerID, $Lang, $Lat)
    {


    }

    public
    function cartQTY($id)
    {
        $input = Request()->all();
        $QTY = $input['QTY'];
        Cart::where('CartID', '=', $id)->update(['QTY' => $QTY]);
        $output = ['state' => '202'];
        return Response()->json($output);

    }

    public
    function deleteCart($id)
    {
        $output = $this->cart->where('CartID', '=', $id)->delete();
        $output = ['state' => '202'];
        return Response()->json($output);
    }

    public
    function deleteProductFromCart()
    {
        $input = Request()->all();
        if (Request()->has('TokenID')) {
            $output = $this->cart
                ->where('UserID', '=', $input['TokenID'])
                ->where('ProductID', '=', $input['ProductID'])
                ->delete();
            $output = ['state' => '202'];


        } else if (Request()->has('UserID')) {

            $output = $this->cart
                ->where('UserID', '=', $input['UserID'])
                ->where('ProductID', '=', $input['ProductID'])
                ->delete();
            $output = ['state' => '202'];

        }

        return Response()->json($output);
    }

}
