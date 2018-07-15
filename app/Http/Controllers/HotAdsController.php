<?php

namespace App\Http\Controllers;

use App\Http\Models\HotAdds;
use App\Http\Models\SliderChangeMode;
use Illuminate\Auth\Access\Response;
use App\Http\Models\Brand;
use App\Http\Models\Category;
use App\Http\Models\Color;
use App\Http\Models\Favorit;
use App\Http\Models\GroupShow;
use App\Http\Models\HotOffer;
use App\Http\Models\Product;
use App\Http\Models\ProductColor;
use App\Http\Models\ProductDetails;
use App\Http\Models\ProductImage;
use App\Http\Models\ProductPrice;
use App\Http\Models\SellerProduct;
use App\Http\Models\Size;
use Illuminate\Http\Request;
use App\Http\Models\City;
use App\Http\Models\SuppliersSupport;

use phpDocumentor\Reflection\DocBlock\Tags\Return_;

class HotAdsController extends Controller
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
        $this->category = new Category();
        $this->color = new Color();
        $this->favorit = new Favorit();
        $this->hotoffer = new HotOffer();
        $this->city = new City();
        $this->supplierssuppot = new SuppliersSupport();
        $this->hotadss = new HotAdds();
    }

    public function GetHotAds($cityname)
    {

        $checkCityName = $this->supplierssuppot
            ->leftJoin($this->city->getTable(), $this->supplierssuppot->getTable() . '.CityID', '=', $this->city->getTable() . '.CityID')
            ->where($this->city->getTable() . '.city_nameen', 'like', '%' . $cityname . '%')
            ->get();
        global $output;
        foreach ($checkCityName as $cityName) {
            $CityID = $cityName->CityID;
            $output = $this->hotadss
                ->select($this->hotadss->getTable() . '.*')
                ->leftjoin($this->product->getTable(), $this->hotadss->getTable() . '.ProductID', '=', $this->product->getTable() . '.ProductID')
                ->leftjoin($this->sellerproduct->getTable(), $this->hotadss->getTable() . '.ProductID', '=', $this->sellerproduct->getTable() . '.ProductID')
                ->leftjoin($this->supplierssuppot->getTable(), $this->sellerproduct->getTable() . '.SupplierID', '=', $this->supplierssuppot->getTable() . '.SupplierID')
//                ->where($this->product->getTable() . '.Updated', '=', 1)
//                ->where($this->product->getTable() . '.Pending', '=', 1)
                ->where($this->supplierssuppot->getTable() . '.CityID', '=', $CityID)
                ->where($this->product->getTable() . '.Ar5ssThan', '=', 1)

                ->orWhere($this->hotadss->getTable().'.ProductID','=',0)
//                ->where($this->sellerproduct->getTable() . '.allow', '!=', 0)
                ->groupby($this->product->getTable() . '.ProductID')
                ->get();
            $Mode = SliderChangeMode::all();
            return Response()->json([
                "Slider" => $output,
                "Mode" => $Mode,
            ]);
        }
    }
}
