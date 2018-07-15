<?php

namespace App\Http\Controllers;

use App\Http\Models\Brand;
use App\Http\Models\Cart;
use App\Http\Models\Category;
use App\Http\Models\Color;
use App\Http\Models\Favorit;
use App\Http\Models\Product;
use App\Http\Models\ProductColor;
use App\Http\Models\ProductDetails;
use App\Http\Models\ProductImage;
use App\Http\Models\ProductPrice;
use App\Http\Models\SellerProduct;
use App\Http\Models\Size;
use App\Http\Models\City;
use App\Http\Models\SuppliersSupport;
use DB;
use Illuminate\Pagination\Paginator;


class CategroryController extends Controller
{
    public function __construct()
    {


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
        $this->city = new City();
        $this->supplierssuppot = new SuppliersSupport();
    }

    public function GetCategroy($cityname)
    {

        $checkCityName = $this->supplierssuppot
            ->leftJoin($this->city->getTable(), $this->supplierssuppot->getTable() . '.CityID', '=', $this->city->getTable() . '.CityID')
            ->where($this->city->getTable() . '.city_nameen', 'like', '%' . $cityname . '%')
            ->get();
        global $output;
        foreach ($checkCityName as $cityName) {
            $CityID = $cityName->CityID;

            $output = $this->category
                ->select(
                    $this->category->getTable() . '.*',
                    DB::raw("COUNT(tblproduct.*)as ProductCont"))
                ->join($this->product->getTable(), $this->category->getTable() . '.CategoryID', '=', $this->product->getTable() . '.CategoryID')
                ->join($this->sellerproduct->getTable(), $this->product->getTable() . '.ProductID', '=', $this->sellerproduct->getTable() . '.ProductID')
                ->join($this->supplierssuppot->getTable(), $this->sellerproduct->getTable() . '.SupplierID', '=', $this->supplierssuppot->getTable() . '.SupplierID')
//            ->having(DB::raw('COUNT(tblproduct.ProductID)as ProductCont != 0') )
                ->where($this->product->getTable() . '.Pending', '=', 1)
                ->where($this->sellerproduct->getTable() . '.allow', '!=', 0)
                ->where($this->supplierssuppot->getTable() . '.CityID', '=', $CityID)
                ->groupby($this->category->getTable() . '.CategoryID')
//                ->groupby($this->product->getTable() . '.ProductID')
                ->orderBy($this->category->getTable() . '.CategoryID', 'Desc')
                ->get();
//

        }
        return Response()->json($output);
    }


    public function GetProductForCategory($id, $sort)
    {


        if ($sort == '1') {
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
                    $this->productImage->getTable() . '.*'
                )
                ->leftjoin($this->ProductPrice->getTable(), $this->product->getTable() . '.ProductID', '=', $this->ProductPrice->getTable() . '.ProductID')
                ->join($this->sellerproduct->getTable(), $this->product->getTable() . '.ProductID', '=', $this->sellerproduct->getTable() . '.ProductID')
                ->leftjoin($this->brand->getTable(), $this->product->getTable() . '.BrandID', '=', $this->brand->getTable() . '.BrandID')
                ->leftjoin($this->category->getTable(), $this->product->getTable() . '.CategoryID', '=', $this->category->getTable() . '.CategoryID')
                ->leftjoin($this->size->getTable(), $this->product->getTable() . '.SizeID', '=', $this->size->getTable() . '.SizeID')
                ->leftjoin($this->ProductColor->getTable(), $this->product->getTable() . '.ProductID', '=', $this->ProductColor->getTable() . '.ProductID')
                ->leftjoin($this->color->getTable(), $this->ProductColor->getTable() . '.ColorID', '=', $this->color->getTable() . '.ColorID')
                ->leftjoin($this->productImage->getTable(), $this->ProductColor->getTable() . '.ProductColorID', '=', $this->productImage->getTable() . '.ProductColorID')
                ->where($this->product->getTable() . '.CategoryID', '=', $id)
                ->where($this->product->getTable() . '.Updated', '=', 1)
                ->where($this->product->getTable() . '.Pending', '=', 1)
                ->where($this->product->getTable() . '.Ar5ssThan', '=', 1)
                ->where($this->sellerproduct->getTable() . '.allow', '!=', 0)
                ->groupby($this->product->getTable() . '.ProductID')
                ->orderby($this->ProductPrice->getTable() . '.ProductPriceDesc', 'ASC')
                ->get();
        } else if ($sort == '2') {
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
                    $this->productImage->getTable() . '.*'
                )
                ->leftjoin($this->ProductPrice->getTable(), $this->product->getTable() . '.ProductID', '=', $this->ProductPrice->getTable() . '.ProductID')
                ->join($this->sellerproduct->getTable(), $this->product->getTable() . '.ProductID', '=', $this->sellerproduct->getTable() . '.ProductID')
                ->leftjoin($this->brand->getTable(), $this->product->getTable() . '.BrandID', '=', $this->brand->getTable() . '.BrandID')
                ->leftjoin($this->category->getTable(), $this->product->getTable() . '.CategoryID', '=', $this->category->getTable() . '.CategoryID')
                ->leftjoin($this->size->getTable(), $this->product->getTable() . '.SizeID', '=', $this->size->getTable() . '.SizeID')
                ->leftjoin($this->ProductColor->getTable(), $this->product->getTable() . '.ProductID', '=', $this->ProductColor->getTable() . '.ProductID')
                ->leftjoin($this->color->getTable(), $this->ProductColor->getTable() . '.ColorID', '=', $this->color->getTable() . '.ColorID')
                ->leftjoin($this->productImage->getTable(), $this->ProductColor->getTable() . '.ProductColorID', '=', $this->productImage->getTable() . '.ProductColorID')
                ->where($this->product->getTable() . '.CategoryID', '=', $id)
                ->where($this->product->getTable() . '.Updated', '=', 1)
                ->where($this->product->getTable() . '.Pending', '=', 1)
                ->where($this->sellerproduct->getTable() . '.allow', '!=', 0)
                ->where($this->product->getTable() . '.Ar5ssThan', '=', 1)
                ->groupby($this->product->getTable() . '.ProductID')
                ->orderby($this->ProductPrice->getTable() . '.ProductPriceDesc', 'DESC')
                ->get();
        }


        return Response()->json($output);
    }

    public function GetCategoryProduct($Currentpage, $parmater, $id, $sort, $cityname)
    {
//        $input = Request()->all();
//        $Currentpage = $input['Currentpage'];
        Paginator::currentPageResolver(function () use ($Currentpage) {
            return $Currentpage;
        });


        $checkCityName = $this->supplierssuppot
            ->leftJoin($this->city->getTable(), $this->supplierssuppot->getTable() . '.CityID', '=', $this->city->getTable() . '.CityID')
            ->where($this->city->getTable() . '.city_nameen', 'like', '%' . $cityname . '%')
            ->get();
        global $output;
        foreach ($checkCityName as $cityName) {
            $CityID = $cityName->CityID;


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
                    $this->sellerproduct->getTable() . '.*',
                    $this->productImage->getTable() . '.*'
                )
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
                ->leftjoin($this->category->getTable(), $this->product->getTable() . '.CategoryID', '=', $this->category->getTable() . '.CategoryID')
                ->leftjoin($this->size->getTable(), $this->product->getTable() . '.SizeID', '=', $this->size->getTable() . '.SizeID')
                ->join($this->sellerproduct->getTable(), $this->product->getTable() . '.ProductID', '=', $this->sellerproduct->getTable() . '.ProductID')
                ->join($this->supplierssuppot->getTable(), $this->sellerproduct->getTable() . '.SupplierID', '=', $this->supplierssuppot->getTable() . '.SupplierID')
                ->leftjoin($this->ProductColor->getTable(), $this->product->getTable() . '.ProductID', '=', $this->ProductColor->getTable() . '.ProductID')
                ->leftjoin($this->color->getTable(), $this->ProductColor->getTable() . '.ColorID', '=', $this->color->getTable() . '.ColorID')
                ->leftjoin($this->productImage->getTable(), $this->ProductColor->getTable() . '.ProductColorID', '=', $this->productImage->getTable() . '.ProductColorID')
                ->where($this->product->getTable() . '.CategoryID', '=', $id)
                ->where($this->product->getTable() . '.Updated', '=', 1)
                ->where($this->product->getTable() . '.Pending', '=', 1)
                ->where($this->sellerproduct->getTable() . '.allow', '!=', 0)
                ->where($this->product->getTable() . '.Ar5ssThan', '=', 1)

                ->where($this->supplierssuppot->getTable() . '.CityID', '=', $CityID)
                ->groupby($this->product->getTable() . '.ProductID');

            if ($sort == '1') {
                $output->orderby($this->ProductPrice->getTable() . '.ProductPriceDesc', 'ASC');
            } elseif ($sort == '2') {
                $output->orderby($this->ProductPrice->getTable() . '.ProductPriceDesc', 'DESC');
            }


        }

        $output = $output->paginate(3);
//        $output->get();
        return Response()->json($output);
    }

    public function SortClassicProduct($parmater, $sort, $CatID, $cityname)
    {


        $checkCityName = $this->supplierssuppot
            ->leftJoin($this->city->getTable(), $this->supplierssuppot->getTable() . '.CityID', '=', $this->city->getTable() . '.CityID')
            ->where($this->city->getTable() . '.city_nameen', 'like', '%' . $cityname . '%')
            ->get();
        global $output;
        foreach ($checkCityName as $cityName) {
            $CityID = $cityName->CityID;

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
                    $this->sellerproduct->getTable() . '.*'
                )
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
                ->leftjoin($this->category->getTable(), $this->product->getTable() . '.CategoryID', '=', $this->category->getTable() . '.CategoryID')
                ->leftjoin($this->size->getTable(), $this->product->getTable() . '.SizeID', '=', $this->size->getTable() . '.SizeID')
                ->join($this->sellerproduct->getTable(), $this->product->getTable() . '.ProductID', '=', $this->sellerproduct->getTable() . '.ProductID')
                ->join($this->supplierssuppot->getTable(), $this->sellerproduct->getTable() . '.SupplierID', '=', $this->supplierssuppot->getTable() . '.SupplierID')
                ->leftjoin($this->ProductColor->getTable(), $this->product->getTable() . '.ProductID', '=', $this->ProductColor->getTable() . '.ProductID')
                ->leftjoin($this->color->getTable(), $this->ProductColor->getTable() . '.ColorID', '=', $this->color->getTable() . '.ColorID')
                ->leftjoin($this->productImage->getTable(), $this->ProductColor->getTable() . '.ProductColorID', '=', $this->productImage->getTable() . '.ProductColorID')
                ->where($this->product->getTable() . '.CategoryID', '=', $CatID)
                ->where($this->supplierssuppot->getTable() . '.CityID', '=', $CityID)
                ->where($this->product->getTable() . '.Updated', '=', 1)
                ->where($this->product->getTable() . '.Pending', '=', 1)
                ->where($this->product->getTable() . '.Ar5ssThan', '=', 1)
                ->where($this->sellerproduct->getTable() . '.allow', '!=', 0)
                ->groupby($this->product->getTable() . '.ProductID');

            if ($sort == '1') {
                $output->orderby($this->ProductPrice->getTable() . '.ProductPriceDesc', 'ASC');
            } elseif ($sort == '2') {
                $output->orderby($this->ProductPrice->getTable() . '.ProductPriceDesc', 'DESC');
            }


        }
        $output = $output->get();
        return Response()->json($output);

    }


}
