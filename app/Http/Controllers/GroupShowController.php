<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\Paginator;
use App\Http\Models\Brand;
use App\Http\Models\Cart;
use App\Http\Models\Category;
use App\Http\Models\Color;
use App\Http\Models\Favorit;
use App\Http\Models\GroupShow;
use App\Http\Models\Product;
use App\Http\Models\ProductColor;
use App\Http\Models\ProductDetails;
use App\Http\Models\ProductImage;
use App\Http\Models\ProductPrice;
use App\Http\Models\Seller;
use App\Http\Models\SellerProduct;
use App\Http\Models\Size;
use App\Http\Models\Users;
use App\Http\Models\OrderDetails;
use App\Http\Models\City;
use App\Http\Models\SuppliersSupport;
use DB;

class GroupShowController extends Controller
{
    //
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
        $this->categry = new Category();
        $this->color = new Color();
        $this->seller = new Seller();
        $this->favorit = new Favorit();
        $this->cart = new Cart();
        $this->users = new Users();
        $this->orderdetails = new OrderDetails();
        $this->city = new City();
        $this->supplierssuppot = new SuppliersSupport();
    }

    public function getGroupShow($parmater, $cityname)
    {


        $ar5ss = DB::SELECT('SELECT p.ProductID, p.BarCode FROM  tblproduct p
  join tblproductprice  pr
  on  p.ProductID = pr.ProductID
  JOIN 
( SELECT p.ProductID ,p.BarCode , MIN(ProductPriceDesc) ProductPriceDesc
  FROM tblproduct p 
  join tblproductprice  pr2
  on  p.ProductID = pr2.ProductID
  GROUP BY BarCode
  ) p2
ON pr.ProductPriceDesc  =  p2.ProductPriceDesc  and p.BarCode = p2.BarCode');

        DB::SELECT('update tblproduct set Ar5ssThan = 0');
        foreach ($ar5ss as $ar5ssThan) {

            $productId = $ar5ssThan->ProductID;
            $this->product->where('ProductID', $productId)->update(['Ar5ssThan' => 1]);
        }


        /* check for best seller*/
        $BestSeller = $this->orderdetails
            ->select(
                $this->orderdetails->getTable() . '.ProductID',
                DB::raw("Count('" . $this->orderdetails->getTable() . '.ProductID' . "') as BestSeler"))
            ->groupBy($this->orderdetails->getTable() . '.ProductID')
            ->Limit(15)->get();

        foreach ($BestSeller as $ProductID) {
            $ProductID = $ProductID->ProductID;
            $this->product
                ->where($this->product->getTable() . '.ProductID', '=', $ProductID)
                ->where($this->product->getTable() . '.GroupShowID', '!=', 4)
                ->update([
                    "GroupShowID" => '1'
                ]);
        }


        /* check for gllobal ItmQTY */
//        $getLastUpdateInSellerProduct = $this->sellerproduct
//            ->select(
//                $this->sellerproduct->getTable() . '.Date',
//                $this->sellerproduct->getTable() . '.ProductQTY'
//            )
//            ->get();
//
//        foreach ($getLastUpdateInSellerProduct as $ProductDate) {
//            $Date = $ProductDate->Date;
//            $ProductQTY = $ProductDate->ProductQTY;
//            $checkItemQTY = $this->orderDetaisl
//                ->Join($this->order->getTable(), $this->orderDetaisl->getTable() . '.OrderID', '=', $this->order->getTable() . '.OrderID')
//                ->select(DB::raw('Sum(ItemsQTY) as ItemsQTY'))
//                ->where($this->orderdetails->getTable() . '.created_at', '>=', $Date)
//                ->where($this->order->getTable() . '.OrderState', '=', 3)
//                ->get();
//
//
//            global $ItemsQty;
//            foreach ($checkItemQTY as $Items) {
//                $ItemsQty = $Items->ItemsQty;
//                $FinalQty = $ProductQTY - $ItemsQty;
//            }
//        }


        global $data;

        $checkCityName = $this->supplierssuppot
            ->leftJoin($this->city->getTable(), $this->supplierssuppot->getTable() . '.CityID', '=', $this->city->getTable() . '.CityID')
            ->where($this->city->getTable() . '.city_nameen', 'like', '%' . $cityname . '%')
            ->get();

        if (count($checkCityName) == 0) {

            return $checkCityName;
        } else {


            foreach ($checkCityName as $cityName) {
                $CityID = $cityName->CityID;
                $groupsow = $this->groupshow
                    ->select(
                        $this->groupshow->getTable() . '.*',
//                $this->product->getTable() . '.*',
                        DB::raw('COUNT("' . $this->product->getTable() . '.GroupShowID' . '") as Item')
                    )
                    ->join($this->product->getTable(), $this->groupshow->getTable() . '.GroupShowID', '=', $this->product->getTable() . '.GroupShowID')
                    ->join($this->sellerproduct->getTable(), $this->product->getTable() . '.ProductID', '=', $this->sellerproduct->getTable() . '.ProductID')
                    ->join($this->supplierssuppot->getTable(), $this->sellerproduct->getTable() . '.SupplierID', '=', $this->supplierssuppot->getTable() . '.SupplierID')
                    ->where($this->product->getTable() . '.Updated', '=', 1)
                    ->where($this->product->getTable() . '.GroupShowID', '!=', 0)
                    ->where($this->supplierssuppot->getTable() . '.CityID', '=', $CityID)
                    ->groupby($this->product->getTable() . '.GroupShowID')
                    ->orderBy($this->groupshow->getTable() . '.GroupShowID', 'ASC')
                    ->get();


                $data = array();
                foreach ($groupsow as $groupshowData) {
                    $GroupShowData = $groupshowData;
                    $GroupShowID = $groupshowData->GroupShowID;

                    $finalObject =
                        $this->product
                            ->select(
                                $this->product->getTable() . '.*',
                                $this->brand->getTable() . '.*',
                                $this->ProductPrice->getTable() . '.*',
                                $this->categry->getTable() . '.*',
                                $this->cart->getTable() . '.*',
                                $this->favorit->getTable() . '.*',
                                $this->color->getTable() . '.*',
                                $this->size->getTable() . '.*',
//                        $this->seller->getTable().'.*',
                                $this->sellerproduct->getTable() . '.*',
                                $this->product->getTable() . '.*',
                                $this->productImage->getTable() . '.*'
                            )
                            ->leftjoin($this->ProductPrice->getTable(), $this->product->getTable() . '.ProductID', '=', $this->ProductPrice->getTable() . '.ProductID')
                            ->leftjoin($this->cart->getTable(), function ($join) use ($parmater) {

                                if (is_numeric($parmater)) {
                                    $join->on(DB::raw("" . $this->cart->getTable() . '.UserID' . ""), DB::raw('='), DB::raw("'" . $parmater . "'"))
//                                ->on($this->cart->getTable() . '.ProductID', '=', $this->product->getTable() . '.ProductID');
                                        ->on(DB::raw($this->cart->getTable() . '.ProductID'), DB::raw('='), DB::raw($this->product->getTable() . '.ProductID'));
                                } else {
                                    $join->on(DB::raw("" . $this->cart->getTable() . '.TokenID' . ""), DB::raw('='), DB::raw("'" . $parmater . "'"))
//                                ->on($this->cart->getTable() . '.ProductID', '=', $this->product->getTable() . '.ProductID');
                                        ->on(DB::raw($this->cart->getTable() . '.ProductID'), DB::raw('='), DB::raw($this->product->getTable() . '.ProductID'));
                                }


                            })
                            ->leftjoin($this->favorit->getTable(), function ($join) use ($parmater) {

                                if (is_numeric($parmater)) {
                                    $join->on(DB::raw("" . $this->favorit->getTable() . '.UserID' . ""), DB::raw('='), DB::raw("'" . $parmater . "'"))
                                        ->on($this->favorit->getTable() . '.ProductID', '=', $this->product->getTable() . '.ProductID');
                                } else {
                                    $join->on(DB::raw("" . $this->favorit->getTable() . '.TokenID' . ""), DB::raw('='), DB::raw("'" . $parmater . "'"))
                                        ->on($this->favorit->getTable() . '.ProductID', '=', $this->product->getTable() . '.ProductID');
                                }


                            })
                            ->leftjoin($this->brand->getTable(), $this->product->getTable() . '.BrandID', '=', $this->brand->getTable() . '.BrandID')
                            ->leftjoin($this->categry->getTable(), $this->product->getTable() . '.CategoryID', '=', $this->categry->getTable() . '.CategoryID')
                            ->leftjoin($this->size->getTable(), $this->product->getTable() . '.SizeID', '=', $this->size->getTable() . '.SizeID')
                            ->join($this->sellerproduct->getTable(), $this->product->getTable() . '.ProductID', '=', $this->sellerproduct->getTable() . '.ProductID')
                            ->join($this->supplierssuppot->getTable(), $this->sellerproduct->getTable() . '.SupplierID', '=', $this->supplierssuppot->getTable() . '.SupplierID')
                            ->leftjoin($this->ProductColor->getTable(), $this->product->getTable() . '.ProductID', '=', $this->ProductColor->getTable() . '.ProductID')
                            ->leftjoin($this->color->getTable(), $this->ProductColor->getTable() . '.ColorID', '=', $this->color->getTable() . '.ColorID')
                            ->leftjoin($this->productImage->getTable(), $this->ProductColor->getTable() . '.ProductColorID', '=', $this->productImage->getTable() . '.ProductColorID')
                            ->where($this->product->getTable() . '.GroupShowID', '=', $GroupShowID)
                            ->where($this->product->getTable() . '.Updated', '=', 1)
                            ->where($this->product->getTable() . '.Ar5ssThan', '=', 1)
                            ->where($this->supplierssuppot->getTable() . '.CityID', '=', $CityID)
                            ->where($this->sellerproduct->getTable() . '.allow', '!=', 0);


                    $finalObject
                        ->groupby($this->product->getTable() . '.ProductID')
                        ->orderBy($this->product->getTable() . '.ProductID', 'DESC')
                        ->limit(20);
                    $output = $finalObject->get();


                    array_push($data, ['Group' => $GroupShowData, 'Products' => $output]);

                }
            }
        }


        return Response()->json($data);
    }


    public function GroupShowPaginet($parmater, $GroupShowID, $Currentpage)
    {

        Paginator::currentPageResolver(function () use ($Currentpage) {
            return $Currentpage;
        });


        $output =
            $this->product
                ->leftjoin($this->ProductPrice->getTable(), $this->product->getTable() . '.ProductID', '=', $this->ProductPrice->getTable() . '.ProductID')
                ->leftjoin($this->cart->getTable(), function ($join) use ($parmater) {

                    if (is_numeric($parmater)) {
                        $join->on(DB::raw("" . $this->cart->getTable() . '.UserID' . ""), DB::raw('='), DB::raw("'" . $parmater . "'"))
                            ->on($this->cart->getTable() . '.ProductID', '=', $this->product->getTable() . '.ProductID');
                    } else {
                        $join->on(DB::raw("" . $this->cart->getTable() . '.TokenID' . ""), DB::raw('='), DB::raw("'" . $parmater . "'"))
                            ->on($this->cart->getTable() . '.ProductID', '=', $this->product->getTable() . '.ProductID');
                    }

                })
                ->leftjoin($this->favorit->getTable(), function ($join) use ($parmater) {

                    if (is_numeric($parmater)) {
                        $join->on(DB::raw("" . $this->favorit->getTable() . '.UserID' . ""), DB::raw('='), DB::raw("'" . $parmater . "'"))
                            ->on($this->favorit->getTable() . '.ProductID', '=', $this->product->getTable() . '.ProductID');
                    } else {
                        $join->on(DB::raw("" . $this->favorit->getTable() . '.TokenID' . ""), DB::raw('='), DB::raw("'" . $parmater . "'"))
                            ->on($this->favorit->getTable() . '.ProductID', '=', $this->product->getTable() . '.ProductID');
                    }


                })
                ->leftjoin($this->brand->getTable(), $this->product->getTable() . '.BrandID', '=', $this->brand->getTable() . '.BrandID')
                ->leftjoin($this->categry->getTable(), $this->product->getTable() . '.CategoryID', '=', $this->categry->getTable() . '.CategoryID')
                ->leftjoin($this->size->getTable(), $this->product->getTable() . '.SizeID', '=', $this->size->getTable() . '.SizeID')
                ->leftjoin($this->sellerproduct->getTable(), $this->product->getTable() . '.ProductID', '=', $this->sellerproduct->getTable() . '.ProductID')
                ->leftjoin($this->ProductColor->getTable(), $this->product->getTable() . '.ProductID', '=', $this->ProductColor->getTable() . '.ProductID')
                ->leftjoin($this->color->getTable(), $this->ProductColor->getTable() . '.ColorID', '=', $this->color->getTable() . '.ColorID')
                ->leftjoin($this->productImage->getTable(), $this->ProductColor->getTable() . '.ProductColorID', '=', $this->productImage->getTable() . '.ProductColorID')
                ->where($this->product->getTable() . '.GroupShowID', '=', $GroupShowID)
                ->where($this->product->getTable() . '.Updated', '=', 1)
                ->where($this->product->getTable() . '.Pending', '=', 2)
                ->where($this->product->getTable() . '.Ar5ssThan', '=', 1)
                ->where($this->sellerproduct->getTable() . '.ProductQTY', '!=', 0)
                ->groupby($this->product->getTable() . '.ProductID')
                ->paginate(3);
//                ->get();


        return Response()->json($output);
    }


    public function getGroupShowTest($parmater)
    {


        $groupsow = $this->groupshow
//            ->orderBy($this->groupshow->getTable() . '.GroupShowID', 'ASC')
            ->get();
        $data = array();
        foreach ($groupsow as $groupshowData) {
            $GroupShowData = $groupshowData;
            $GroupShowID = $groupshowData->GroupShowID;
            $finalObject = $this->product
                ->leftjoin($this->cart->getTable(), function ($join) use ($parmater) {

                    $join->where($this->cart->getTable() . '.UserID', '=', "$parmater")
                        ->where($this->cart->getTable() . '.TokenID', '=', "$parmater");
                })
                ->leftjoin($this->favorit->getTable(), function ($join) use ($parmater) {
                    $join->where($this->favorit->getTable() . '.UserID', '=', "$parmater")
                        ->where($this->favorit->getTable() . '.TokenID', '=', "$parmater");
                })
                ->leftjoin($this->brand->getTable(), $this->product->getTable() . '.BrandID', '=', $this->brand->getTable() . '.BrandID')
                ->leftjoin($this->categry->getTable(), $this->product->getTable() . '.CategoryID', '=', $this->categry->getTable() . '.CategoryID')
                ->leftjoin($this->size->getTable(), $this->product->getTable() . '.SizeID', '=', $this->size->getTable() . '.SizeID')
                ->leftjoin($this->sellerproduct->getTable(), $this->product->getTable() . '.ProductID', '=', $this->sellerproduct->getTable() . '.ProductID')
                ->leftjoin($this->ProductColor->getTable(), $this->product->getTable() . '.ProductID', '=', $this->ProductColor->getTable() . '.ProductID')
                ->leftjoin($this->color->getTable(), $this->ProductColor->getTable() . '.ColorID', '=', $this->color->getTable() . '.ColorID')
                ->leftjoin($this->productImage->getTable(), $this->ProductColor->getTable() . '.ProductColorID', '=', $this->productImage->getTable() . '.ProductColorID')
                ->where($this->product->getTable() . '.GroupShowID', '=', $GroupShowID)
                ->where($this->product->getTable() . '.Updated', '=', 1)
                ->where($this->product->getTable() . '.Ar5ssThan', '=', 1)
                ->where($this->product->getTable() . '.Pending', '=', 2);


            $finalObject->groupby($this->product->getTable() . '.ProductID');
            $output = $finalObject->paginate(5);

//            $data[$GroupShowID] = array($GroupShowData, $output);
            array_push($data, ['Group' => $GroupShowData, 'Products' => $output]);

        }

        return Response()->json($data);
    }


    public function getPrdouctForGroup($parmater, $id, $cityname)
    {
        $checkCityName = $this->supplierssuppot
            ->leftJoin($this->city->getTable(), $this->supplierssuppot->getTable() . '.CityID', '=', $this->city->getTable() . '.CityID')
            ->where($this->city->getTable() . '.city_nameen', 'like', '%' . $cityname . '%')
            ->get();
        global $output;
        foreach ($checkCityName as $cityName) {
            $CityID = $cityName->CityID;


            $finalObject =
                $this->product
                    ->select(
                        $this->product->getTable() . '.*',
                        $this->brand->getTable() . '.*',
//                    $this->ProductPrice->getTable() . '.*',
                        $this->ProductPrice->getTable() . '.ProductPriceDESC as ProductPrice',
                        $this->categry->getTable() . '.*',
                        $this->cart->getTable() . '.*',
                        $this->favorit->getTable() . '.*',
                        $this->color->getTable() . '.*',
                        $this->size->getTable() . '.*',
                        $this->sellerproduct->getTable() . '.*',
                        $this->product->getTable() . '.*',
                        $this->productImage->getTable() . '.*'
                    )
                    ->leftjoin($this->ProductPrice->getTable(), $this->product->getTable() . '.ProductID', '=', $this->ProductPrice->getTable() . '.ProductID')
                    ->leftjoin($this->cart->getTable(), function ($join) use ($parmater) {

                        if (is_numeric($parmater)) {
                            $join->on(DB::raw("" . $this->cart->getTable() . '.UserID' . ""), DB::raw('='), DB::raw("'" . $parmater . "'"))
                                ->on(DB::raw($this->cart->getTable() . '.ProductID'), DB::raw('='), DB::raw($this->product->getTable() . '.ProductID'));
                        } else {
                            $join->on(DB::raw("" . $this->cart->getTable() . '.TokenID' . ""), DB::raw('='), DB::raw("'" . $parmater . "'"))
                                ->on(DB::raw($this->cart->getTable() . '.ProductID'), DB::raw('='), DB::raw($this->product->getTable() . '.ProductID'));
                        }


                    })
                    ->leftjoin($this->favorit->getTable(), function ($join) use ($parmater) {

                        if (is_numeric($parmater)) {
                            $join->on(DB::raw("" . $this->favorit->getTable() . '.UserID' . ""), DB::raw('='), DB::raw("'" . $parmater . "'"))
                                ->on($this->favorit->getTable() . '.ProductID', '=', $this->product->getTable() . '.ProductID');
                        } else {
                            $join->on(DB::raw("" . $this->favorit->getTable() . '.TokenID' . ""), DB::raw('='), DB::raw("'" . $parmater . "'"))
                                ->on($this->favorit->getTable() . '.ProductID', '=', $this->product->getTable() . '.ProductID');
                        }


                    })
                    ->leftjoin($this->brand->getTable(), $this->product->getTable() . '.BrandID', '=', $this->brand->getTable() . '.BrandID')
                    ->leftjoin($this->categry->getTable(), $this->product->getTable() . '.CategoryID', '=', $this->categry->getTable() . '.CategoryID')
                    ->leftjoin($this->size->getTable(), $this->product->getTable() . '.SizeID', '=', $this->size->getTable() . '.SizeID')
                    ->join($this->sellerproduct->getTable(), $this->product->getTable() . '.ProductID', '=', $this->sellerproduct->getTable() . '.ProductID')
                    ->join($this->supplierssuppot->getTable(), $this->sellerproduct->getTable() . '.SupplierID', '=', $this->supplierssuppot->getTable() . '.SupplierID')
                    ->leftjoin($this->ProductColor->getTable(), $this->product->getTable() . '.ProductID', '=', $this->ProductColor->getTable() . '.ProductID')
                    ->leftjoin($this->color->getTable(), $this->ProductColor->getTable() . '.ColorID', '=', $this->color->getTable() . '.ColorID')
                    ->leftjoin($this->productImage->getTable(), $this->ProductColor->getTable() . '.ProductColorID', '=', $this->productImage->getTable() . '.ProductColorID')
                    ->where($this->product->getTable() . '.GroupShowID', '=', $id)
                    ->where($this->supplierssuppot->getTable() . '.CityID', '=', $CityID)
                    ->where($this->product->getTable() . '.Updated', '=', 1)
                    ->where($this->product->getTable() . '.Pending', '=', 1)
                    ->where($this->product->getTable() . '.Ar5ssThan', '=', 1)
                    ->where($this->sellerproduct->getTable() . '.allow', '!=', 0);


            $finalObject
                ->groupby($this->product->getTable() . '.ProductID')
                ->orderBy($this->product->getTable() . '.ProductID', 'DESC')
                ->limit(50);

            $output = $finalObject->get();
        }
        return Response()->json($output);

    }

    public function UpdateImages()
    {

        $output = $this->productImage->select('ImageID', 'Image')->get();
        foreach ($output as $Images) {
            $Image = $Images->Image;
            $ImageID = $Images->ImageID;
            $newLink = substr($Image, 26);
            $newLink = 'http://188.226.135.249' . $newLink;
            $this->productImage->find($ImageID)->update(["Image" => $newLink]);
        }
    }


}
