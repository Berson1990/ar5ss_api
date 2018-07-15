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

class ProductController extends Controller
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

    }


    public function getProductDetails($id)
    {

        $output = $this->product->with(['ProductColor' => (function ($query) {
            $query->with('ProductImage', 'ColorsOfProduct');
        })])
            ->select(
                $this->product->getTable() . '.*',
                $this->hotoffer->getTable() . '.*',
                $this->ProductPrice->getTable() . '.*',
                $this->ProductPrice->getTable() . '.ProductPriceDesc as ProductPrice',
                $this->brand->getTable() . '.*',
                $this->category->getTable() . '.*',
                $this->size->getTable() . '.*',
                $this->hotoffer->getTable() . '.*',
                $this->sellerproduct->getTable() . '.*'
            )
            ->join($this->sellerproduct->getTable(), $this->product->getTable() . '.ProductID', '=', $this->sellerproduct->getTable() . '.ProductID')
            ->leftjoin($this->ProductPrice->getTable(), $this->product->getTable() . '.ProductID', '=', $this->ProductPrice->getTable() . '.ProductID')
//            ->join($this->sellerproduct->getTable(), $this->product->getTable() . '.ProductID', '=', $this->sellerproduct->getTable() . '.ProductID')
            ->leftjoin($this->hotoffer->getTable(), $this->product->getTable() . '.HotOfferID', '=', $this->hotoffer->getTable() . '.HotOfferID')
            ->leftjoin($this->brand->getTable(), $this->product->getTable() . '.BrandID', '=', $this->brand->getTable() . '.BrandID')
            ->leftjoin($this->category->getTable(), $this->product->getTable() . '.CategoryID', '=', $this->category->getTable() . '.CategoryID')
            ->leftjoin($this->size->getTable(), $this->product->getTable() . '.SizeID', '=', $this->size->getTable() . '.SizeID')
            ->where($this->product->getTable() . '.ProductID', '=', $id)
            ->where($this->product->getTable() . '.Updated', '=', 1)
            ->where($this->product->getTable() . '.Pending', '=', 1)
            ->where($this->product->getTable() . '.Ar5ssThan', '=', 1)
            ->where($this->sellerproduct->getTable() . '.ProductQTY', '!=', 0)
            ->groupby($this->product->getTable() . '.ProductID')
            ->get();
        return Response()->json($output['0']);

    }

    public function getProductByBarcode($barcode)
    {

        $output = $this->product
            ->where($this->product->getTable() . '.BarCode', '=', $barcode)->get();
        if (count($output) <= 0) {
            $output = ["msg" => 'this barcode is not exist'];
            return Response()->json($output);
        } else {


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
                ->join($this->sellerproduct->getTable(), $this->product->getTable() . '.ProductID', '=', $this->sellerproduct->getTable() . '.ProductID')
                ->leftjoin($this->hotoffer->getTable(), $this->product->getTable() . '.HotOfferID', '=', $this->hotoffer->getTable() . '.HotOfferID')
                ->leftjoin($this->brand->getTable(), $this->product->getTable() . '.BrandID', '=', $this->brand->getTable() . '.BrandID')
                ->leftjoin($this->category->getTable(), $this->product->getTable() . '.CategoryID', '=', $this->category->getTable() . '.CategoryID')
                ->leftjoin($this->size->getTable(), $this->product->getTable() . '.SizeID', '=', $this->size->getTable() . '.SizeID')
                ->leftjoin($this->ProductColor->getTable(), $this->product->getTable() . '.ProductID', '=', $this->ProductColor->getTable() . '.ProductID')
                ->leftjoin($this->color->getTable(), $this->ProductColor->getTable() . '.ColorID', '=', $this->color->getTable() . '.ColorID')
                ->leftjoin($this->productImage->getTable(), $this->ProductColor->getTable() . '.ProductColorID', '=', $this->productImage->getTable() . '.ProductColorID')
                ->where($this->product->getTable() . '.BarCode', '=', $barcode)
                ->where($this->product->getTable() . '.Updated', '=', 1)
                ->where($this->product->getTable() . '.Ar5ssThan', '=', 1)
                ->where($this->product->getTable() . '.Pending', '=', 1)
                ->where($this->sellerproduct->getTable() . '.ProductQTY', '!=', 0)
                ->groupby($this->product->getTable() . '.ProductID')
                ->get();

        }
        return Response()->json($output['0']);
    }

    public function getProductColorImage()
    {
        $input = Request()->all();
        $ProductID = $input['ProductID'];
        $ColorID = $input['ColorID'];
        $output = $this->product
            ->select(
                $this->productImage->getTable() . '.Image',
                $this->product->getTable() . '.*',
                $this->ProductColor->getTable() . '.*',
                $this->color->getTable() . '.*'
//                $this->sellerproduct->getTable() . '.*'

            )
            ->leftjoin($this->ProductColor->getTable(), $this->product->getTable() . '.ProductID', '=', $this->ProductColor->getTable() . '.ProductID')
            ->leftjoin($this->color->getTable(), $this->ProductColor->getTable() . '.ColorID', '=', $this->color->getTable() . '.ColorID')
            ->leftjoin($this->productImage->getTable(), $this->ProductColor->getTable() . '.ProductColorID', '=', $this->productImage->getTable() . '.ProductColorID')
            ->where($this->product->getTable() . '.ProductID', '=', $ProductID)
            ->where($this->product->getTable() . '.Updated', '=', 1)
            ->where($this->product->getTable() . '.Pending', '=', 1)
            ->where($this->product->getTable() . '.Ar5ssThan', '=', 1)
            ->where($this->ProductColor->getTable() . '.ColorId', '=', $ColorID)
            ->get();
        return Response()->json($output);

    }

    public function getFovoritList()
    {
        $input = Request()->all();

        $UserID = $input['UserID'];
        $TokenID = $input['TokenID'];
        $output = $this->product->select($this->favorit->getTable() . '.UserID')
            ->join($this->favorit->getTable(), $this->product->getTable() . '.ProductID', '=', $this->favorit->getTable() . '.ProductID')->get();
        if ($UserID) {
            $output->where($this->favorit->getTable() . '.UserID', '=', $UserID);

        } elseif ($TokenID) {

            $output->where($this->favorit->getTable() . '.TokenID', '=', $TokenID);

        }
        $output = $output->get();
        return Response()->json($output);
    }
}
