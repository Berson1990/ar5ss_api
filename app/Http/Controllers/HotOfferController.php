<?php

namespace App\Http\Controllers;

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


class HotOfferController extends Controller
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
        $this->hotoffer = new HotOffer();
        $this->city = new City();
        $this->supplierssuppot = new SuppliersSupport();

    }

    public function getHotoffer($cityname)
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
                    $this->hotoffer->getTable() . '.*',
                    $this->ProductPrice->getTable() . '.*',
                    $this->ProductPrice->getTable() . '.ProductPriceDesc as ProductPrice',
                    $this->brand->getTable() . '.*',
                    $this->category->getTable() . '.*',
                    $this->size->getTable() . '.*',
                    $this->ProductColor->getTable() . '.*',
                    $this->color->getTable() . '.*',
                    $this->productImage->getTable() . '.*',
                    $this->hotoffer->getTable() . '.*',
                    $this->sellerproduct->getTable() . '.*'
                )
                ->leftjoin($this->ProductPrice->getTable(), $this->product->getTable() . '.ProductID', '=', $this->ProductPrice->getTable() . '.ProductID')
                ->join($this->hotoffer->getTable(), $this->product->getTable() . '.HotOfferID', '=', $this->hotoffer->getTable() . '.HotOfferID')
                ->leftjoin($this->brand->getTable(), $this->product->getTable() . '.BrandID', '=', $this->brand->getTable() . '.BrandID')
                ->leftjoin($this->category->getTable(), $this->product->getTable() . '.CategoryID', '=', $this->category->getTable() . '.CategoryID')
                ->leftjoin($this->size->getTable(), $this->product->getTable() . '.SizeID', '=', $this->size->getTable() . '.SizeID')
                ->join($this->sellerproduct->getTable(), $this->product->getTable() . '.ProductID', '=', $this->sellerproduct->getTable() . '.ProductID')
                ->join($this->supplierssuppot->getTable(), $this->sellerproduct->getTable() . '.SupplierID', '=', $this->supplierssuppot->getTable() . '.SupplierID')
                ->leftjoin($this->ProductColor->getTable(), $this->product->getTable() . '.ProductID', '=', $this->ProductColor->getTable() . '.ProductID')
                ->leftjoin($this->color->getTable(), $this->ProductColor->getTable() . '.ColorID', '=', $this->color->getTable() . '.ColorID')
                ->leftjoin($this->productImage->getTable(), $this->ProductColor->getTable() . '.ProductColorID', '=', $this->productImage->getTable() . '.ProductColorID')
                ->where($this->product->getTable() . '.HotOfferID', '!=', 0)
                ->where($this->product->getTable() . '.Updated', '=', 1)
                ->where($this->product->getTable() . '.Pending', '=', 1)
                ->where($this->supplierssuppot->getTable() . '.CityID', '=', $CityID)
                ->where($this->sellerproduct->getTable() . '.ProductQTY', '!=', 0)
                ->groupby($this->product->getTable() . '.ProductID')
                ->get();
        }
        return Response()->json($output);

    }


    public function getOtherOffers($id, $cityname)
    {

        $checkCityName = $this->supplierssuppot
            ->leftJoin($this->city->getTable(), $this->supplierssuppot->getTable() . '.CityID', '=', $this->city->getTable() . '.CityID')
            ->where($this->city->getTable() . '.city_nameen', 'like', '%' . $cityname . '%')
            ->get();
        global $output;
        foreach ($checkCityName as $cityName) {
            $CityID = $cityName->CityID;


            $CategoryID = $this->product
                ->select($this->product->getTable() . '.CategoryID')->where($this->product->getTable() . '.ProductID', '=', $id)->get();

            foreach ($CategoryID as $CategoryID) {
                $CategoryID = $CategoryID->CategoryID;
            }
            $output = $this->product
                ->select(
                    $this->product->getTable() . '.*',
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
//            ->leftjoin($this->hotoffer->getTable(), $this->product->getTable() . '.HotOfferID', '=', $this->hotoffer->getTable() . '.HotOfferID')
                ->leftjoin($this->brand->getTable(), $this->product->getTable() . '.BrandID', '=', $this->brand->getTable() . '.BrandID')
                ->leftjoin($this->category->getTable(), $this->product->getTable() . '.CategoryID', '=', $this->category->getTable() . '.CategoryID')
                ->leftjoin($this->size->getTable(), $this->product->getTable() . '.SizeID', '=', $this->size->getTable() . '.SizeID')
                ->join($this->sellerproduct->getTable(), $this->product->getTable() . '.ProductID', '=', $this->sellerproduct->getTable() . '.ProductID')
                ->join($this->supplierssuppot->getTable(), $this->sellerproduct->getTable() . '.SupplierID', '=', $this->supplierssuppot->getTable() . '.SupplierID')
                ->leftjoin($this->ProductColor->getTable(), $this->product->getTable() . '.ProductID', '=', $this->ProductColor->getTable() . '.ProductID')
                ->leftjoin($this->color->getTable(), $this->ProductColor->getTable() . '.ColorID', '=', $this->color->getTable() . '.ColorID')
                ->leftjoin($this->productImage->getTable(), $this->ProductColor->getTable() . '.ProductColorID', '=', $this->productImage->getTable() . '.ProductColorID')
                ->where($this->product->getTable() . '.CategoryID', '=', $CategoryID)
                ->where($this->product->getTable() . '.Updated', '=', 1)
                ->where($this->product->getTable() . '.Pending', '=', 1)
                ->where($this->supplierssuppot->getTable() . '.CityID', '=', $CityID)
                ->where($this->sellerproduct->getTable() . '.allow', '!=', 0)
                ->groupby($this->product->getTable() . '.ProductID')
                ->get();
        }
        return Response()->json($output);
    }

    public function GlobalSearch($cityname)
    {
        $input = Request()->all();
        $KeyWord = $input['KeyWord'];

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
                    $this->ProductPrice->getTable() . '.*',
                    $this->ProductPrice->getTable() . '.ProductPriceDesc as ProductPrice',
                    $this->brand->getTable() . '.*',
                    $this->category->getTable() . '.*',
                    $this->size->getTable() . '.*',
                    $this->ProductColor->getTable() . '.*',
                    $this->color->getTable() . '.*',
                    $this->productImage->getTable() . '.*',
                    $this->hotoffer->getTable() . '.*',
                    $this->sellerproduct->getTable() . '.*'
                )
                ->leftjoin($this->ProductPrice->getTable(), $this->product->getTable() . '.ProductID', '=', $this->ProductPrice->getTable() . '.ProductID')
                ->leftjoin($this->hotoffer->getTable(), $this->product->getTable() . '.HotOfferID', '=', $this->hotoffer->getTable() . '.HotOfferID')
                ->leftjoin($this->brand->getTable(), $this->product->getTable() . '.BrandID', '=', $this->brand->getTable() . '.BrandID')
                ->leftjoin($this->category->getTable(), $this->product->getTable() . '.CategoryID', '=', $this->category->getTable() . '.CategoryID')
                ->leftjoin($this->size->getTable(), $this->product->getTable() . '.SizeID', '=', $this->size->getTable() . '.SizeID')
                ->leftjoin($this->sellerproduct->getTable(), $this->product->getTable() . '.ProductID', '=', $this->sellerproduct->getTable() . '.ProductID')
                ->join($this->supplierssuppot->getTable(), $this->sellerproduct->getTable() . '.SupplierID', '=', $this->supplierssuppot->getTable() . '.SupplierID')
                ->leftjoin($this->ProductColor->getTable(), $this->product->getTable() . '.ProductID', '=', $this->ProductColor->getTable() . '.ProductID')
                ->leftjoin($this->color->getTable(), $this->ProductColor->getTable() . '.ColorID', '=', $this->color->getTable() . '.ColorID')
                ->leftjoin($this->productImage->getTable(), $this->ProductColor->getTable() . '.ProductColorID', '=', $this->productImage->getTable() . '.ProductColorID')
                ->where($this->product->getTable() . '.Updated', '=', 1)
                ->where($this->product->getTable() . '.Pending', '=', 1)
                ->where($this->product->getTable() . '.Ar5ssThan', '=', 1)
                ->where($this->sellerproduct->getTable() . '.allow', '=', 1)
                ->where($this->supplierssuppot->getTable() . '.CityID', '=', $CityID);
            if (!is_numeric($KeyWord)) {

                $output = $output->Where($this->product->getTable() . '.product_name', 'LIKE', '%' . $KeyWord . '%')
                    ->orWhere($this->product->getTable() . '.product_nameen', 'LIKE', '%' . $KeyWord . '%');
            }
            else if (is_numeric($KeyWord)) {

                $output = $output->Where($this->product->getTable() . '.BarCode', 'LIKE', '%' . $KeyWord . '%')
                    ->orWhere($this->product->getTable() . '.SeialNumbers', 'LIKE', '%' . $KeyWord . '%');
            }
            $output = $output->groupby($this->product->getTable() . '.ProductID');
            $output = $output->get();
        }
        return Response()->json($output);


    }

}
