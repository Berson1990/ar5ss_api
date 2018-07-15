<?php

namespace App\Http\Controllers;

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
use App\Http\Models\SellerProduct;
use App\Http\Models\Size;
use App\Http\Models\Users;
use DB;


class WishListController extends Controller
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
        $this->users = new Users();
        $this->cart = new Cart();
    }

    public function insetNewItemTOwishlist()
    {
        $input = Request()->all();
        $output = $this->favorit->create($input);
        return 'true';
    }

    public function insetNewItemTOwishlistOffline()
    {
        $input = Request()->all();
        for ($i = 0; $i < count($input); $i++) {
            $output = $this->favorit->create($input[$i]);

        }
        return 'true';
    }

    public function getWishlistbyUserID($id)
    {

        $output = $this->favorit

            ->select(
                $this->favorit->getTable().'.*',
                $this->product->getTable().'.*',
                $this->ProductPrice->getTable().'.*',
                $this->ProductPrice->getTable().'.ProductPriceDesc as ProductPrice',
                $this->brand->getTable().'.*',
                $this->category->getTable().'.*',
                $this->size->getTable().'.*',
                $this->ProductColor->getTable().'.*',
                $this->color->getTable().'.*',
                $this->productImage->getTable().'.*',
                $this->sellerproduct->getTable().'.*'
            )
//            ->leftjoin('tblcart as usercart','usercart.UserID',DB::raw('='), DB::raw("'" . $id . "'"))
//            ->leftjoin('tblfaovorit as favoritcart','favoritcart.UserID',DB::raw('='), DB::raw("'" . $id . "'"))
//            ->join('tblusers as users1', 'users1.UserID', '=', 'tblcart.UserID')
//            ->join('tblusers as users2', 'users2.UserID', '=', 'tblfaovorit.UserID')

//            ->leftjoin($this->cart->getTable(), $this->product->getTable() . '.ProductID', '=', $this->cart->getTable() . '.ProductID')

            ->leftjoin($this->product->getTable(), $this->favorit->getTable() . '.ProductID', '=', $this->product->getTable() . '.ProductID')

            ->leftjoin($this->cart->getTable(), function ($join) use ($id) {

                $join->on(DB::raw("" . $this->cart->getTable() . '.UserID' . ""), DB::raw('='), DB::raw("'" . $id . "'"))
                    ->on($this->cart->getTable() . '.ProductID', '=', $this->favorit->getTable() . '.ProductID');

            })
            ->leftjoin($this->users->getTable(),$this->favorit->getTable().'.UserID','=',$this->users->getTable().'.UserID')
            ->leftjoin($this->ProductPrice->getTable(), $this->product->getTable() . '.ProductID', '=', $this->ProductPrice->getTable() . '.ProductID')
            ->leftjoin($this->brand->getTable(), $this->product->getTable() . '.BrandID', '=', $this->brand->getTable() . '.BrandID')
            ->leftjoin($this->category->getTable(), $this->product->getTable() . '.CategoryID', '=', $this->category->getTable() . '.CategoryID')
            ->leftjoin($this->size->getTable(), $this->product->getTable() . '.SizeID', '=', $this->size->getTable() . '.SizeID')
            ->leftjoin($this->sellerproduct->getTable(), $this->product->getTable() . '.ProductID', '=', $this->sellerproduct->getTable() . '.ProductID')
            ->leftjoin($this->ProductColor->getTable(), $this->product->getTable() . '.ProductID', '=', $this->ProductColor->getTable() . '.ProductID')
            ->leftjoin($this->color->getTable(), $this->ProductColor->getTable() . '.ColorID', '=', $this->color->getTable() . '.ColorID')
            ->leftjoin($this->productImage->getTable(), $this->ProductColor->getTable() . '.ProductColorID', '=', $this->productImage->getTable() . '.ProductColorID')
            ->where($this->favorit->getTable() . '.UserID', '=', $id)
            ->where($this->product->getTable() . '.Updated', '=', 1)
            //            ->where($this->cart->getTable() . '.UserID', '=', $id)
            ->groupby($this->product->getTable() . '.ProductID')
            ->get();
        return Response()->json($output);
    }

    public function getWishlistbyTokenID($tokenid)
    {
        $output = $this->favorit

            ->select(
                $this->favorit->getTable().'.*',
                $this->product->getTable().'.*',
                $this->ProductPrice->getTable().'.*',
                $this->ProductPrice->getTable().'.ProductPriceDesc as ProductPrice',
                $this->brand->getTable().'.*',
                $this->category->getTable().'.*',
                $this->size->getTable().'.*',
                $this->ProductColor->getTable().'.*',
                $this->color->getTable().'.*',
                $this->productImage->getTable().'.*',
//                $this->hotoffer->getTable().'.*',
                $this->sellerproduct->getTable().'.*'
            )

            ->leftjoin($this->product->getTable(), $this->favorit->getTable() . '.ProductID', '=', $this->product->getTable() . '.ProductID')

            ->leftjoin($this->cart->getTable(), function ($join) use ($tokenid) {

                $join->on(DB::raw("" . $this->cart->getTable() . '.UserID' . ""), DB::raw('='), DB::raw("'" . $tokenid . "'"))
                    ->on($this->cart->getTable() . '.ProductID', '=', $this->favorit->getTable() . '.ProductID');

            })

            ->leftjoin($this->ProductPrice->getTable(), $this->product->getTable() . '.ProductID', '=', $this->ProductPrice->getTable() . '.ProductID')
            ->leftjoin($this->brand->getTable(), $this->product->getTable() . '.BrandID', '=', $this->brand->getTable() . '.BrandID')
            ->leftjoin($this->category->getTable(), $this->product->getTable() . '.CategoryID', '=', $this->category->getTable() . '.CategoryID')
            ->leftjoin($this->size->getTable(), $this->product->getTable() . '.SizeID', '=', $this->size->getTable() . '.SizeID')
            ->leftjoin($this->sellerproduct->getTable(), $this->product->getTable() . '.ProductID', '=', $this->sellerproduct->getTable() . '.ProductID')
            ->leftjoin($this->ProductColor->getTable(), $this->product->getTable() . '.ProductID', '=', $this->ProductColor->getTable() . '.ProductID')
            ->leftjoin($this->color->getTable(), $this->ProductColor->getTable() . '.ColorID', '=', $this->color->getTable() . '.ColorID')
            ->leftjoin($this->productImage->getTable(), $this->ProductColor->getTable() . '.ProductColorID', '=', $this->productImage->getTable() . '.ProductColorID')
            ->where($this->favorit->getTable() . '.TokenID', '=', $tokenid)
            ->where($this->product->getTable() . '.Updated', '=', 1)
//            ->where($this->product->getTable() . '.Pending', '=', 2)
            ->where($this->product->getTable() . '.Pending', '=', 1)
            ->groupby($this->product->getTable() . '.ProductID')
            ->get();
        return Response()->json($output);

    }

    public function destroy($id)
    {

        $output = $this->favorit->where($this->favorit->getTable() . '.FavoritID', '=', $id)->delete();
        $output = ['stats' => 'done'];
        return Response()->json($output);

    }

    public function RemoveFavourit()
    {
        $input = Request()->all();
        $ProductID = $input['ProductID'];
        if (Request()->has('UserID')) {
            $UserID = $input['UserID'];
            $this->favorit
                ->where($this->favorit->getTable() . '.UserID', '=', $UserID)
                ->where($this->favorit->getTable() . '.ProductID', '=', $ProductID)
                ->delete();
            $output = ['state' => '202'];
        } else if (Request()->has('TokenID')) {
            $TokenID = $input['TokenID'];
            $this->favorit
                ->where($this->favorit->getTable() . '.UserID', '=', $TokenID)
                ->where($this->favorit->getTable() . '.ProductID', '=', $ProductID)
                ->delete();
            $output = ['state' => '202'];
        }
        return Response()->json($output);
    }

}
