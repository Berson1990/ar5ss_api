<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Http\Models\Cart;
use App\Http\Models\Order;
use App\Http\Models\OrderDetails;
use App\Http\Models\Brand;
use App\Http\Models\Category;
use App\Http\Models\Color;
use App\Http\Models\Product;
use App\Http\Models\ProductColor;
use App\Http\Models\ProductImage;
use App\Http\Models\ProductPrice;
use App\Http\Models\Size;
use App\Http\Models\GroupShow;
use App\Http\Models\PropertyValue;
use App\Http\Models\SellerProduct;
use App\Http\Models\Users;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use DB;
use Image;

class AdminProductController extends Controller
{
    public function __construct()
    {
        $this->product = new Product();
        $this->category = new Category();
        $this->brand = new Brand();
        $this->size = new Size();
        $this->productprice = new ProductPrice();
        $this->productcolor = new ProductColor();
        $this->productImage = new ProductImage();
        $this->color = new Color();
        $this->groupshow = new GroupShow();
        $this->propertyvalue = new PropertyValue();
        $this->cart = new Cart();
        $this->order = new Order();
        $this->orderDetaisl = new OrderDetails();
        $this->sellerproduct = new SellerProduct();
        $this->users = new Users();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $output = $this->product
            ->select(
                $this->product->getTable() . '.*',
                $this->users->getTable() . '.*',
                $this->productprice->getTable() . '.*',
                $this->size->getTable() . '.*',
                $this->category->getTable() . '.*',
                $this->brand->getTable() . '.*'
            )
            ->leftjoin($this->users->getTable(), $this->product->getTable() . '.UserID', '=', $this->users->getTable() . '.UserID')
            ->leftjoin($this->productprice->getTable(), $this->product->getTable() . '.ProductID', '=', $this->productprice->getTable() . '.ProductID')
//            ->leftjoin($this->productcolor->getTable(), $this->product->getTable() . '.ProductID', '=', $this->productcolor->getTable() . '.ProductID')
//            ->leftjoin($this->color->getTable(), $this->productcolor->getTable() . '.ColorID', '=', $this->color->getTable() . '.ColorID')
//            ->leftjoin($this->productImage->getTable(), $this->productcolor->getTable() . '.ProductColorID', '=', $this->productImage->getTable() . '.ProductColorID')
            ->leftjoin($this->size->getTable(), $this->product->getTable() . '.SizeID', '=', $this->size->getTable() . '.SizeID')
            ->leftjoin($this->category->getTable(), $this->product->getTable() . '.CategoryID', '=', $this->category->getTable() . '.CategoryID')
            ->leftjoin($this->brand->getTable(), $this->product->getTable() . '.BrandID', '=', $this->brand->getTable() . '.BrandID')
//            ->where($this->product->getTable() . '.Pending', '=', 1)
            ->where($this->product->getTable() . '.UserID', '=', $id)
            ->groupby($this->product->getTable() . '.ProductID')
            ->orderby($this->product->getTable() . '.ProductID', 'DESC')
            ->get();
        return Response()->json($output);
    }

    public function AllProduct()
    {
        $output = $this->product
            ->select(
                $this->product->getTable() . '.*',
                $this->users->getTable() . '.*',
                $this->productprice->getTable() . '.*',
                $this->size->getTable() . '.*',
                $this->category->getTable() . '.*',
                $this->brand->getTable() . '.*'
            )
            ->leftjoin($this->users->getTable(), $this->product->getTable() . '.UserID', '=', $this->users->getTable() . '.UserID')
            ->leftjoin($this->productprice->getTable(), $this->product->getTable() . '.ProductID', '=', $this->productprice->getTable() . '.ProductID')
//            ->leftjoin($this->productcolor->getTable(), $this->product->getTable() . '.ProductID', '=', $this->productcolor->getTable() . '.ProductID')
//            ->leftjoin($this->color->getTable(), $this->productcolor->getTable() . '.ColorID', '=', $this->color->getTable() . '.ColorID')
//            ->leftjoin($this->productImage->getTable(), $this->productcolor->getTable() . '.ProductColorID', '=', $this->productImage->getTable() . '.ProductColorID')
            ->leftjoin($this->size->getTable(), $this->product->getTable() . '.SizeID', '=', $this->size->getTable() . '.SizeID')
            ->leftjoin($this->category->getTable(), $this->product->getTable() . '.CategoryID', '=', $this->category->getTable() . '.CategoryID')
            ->leftjoin($this->brand->getTable(), $this->product->getTable() . '.BrandID', '=', $this->brand->getTable() . '.BrandID')
            ->where($this->product->getTable() . '.Pending', '=', 1)
            ->groupby($this->product->getTable() . '.ProductID')
            ->orderby($this->product->getTable() . '.ProductID', 'DESC')
            ->get();
        return Response()->json($output);
    }

    private function updateNproductQTY($id)
    {
        $getProductInfo = $this->sellerproduct
            ->where('SupplierID', $id)
            ->where('Date', '!=', '0000-00-00')
            ->get();

        foreach ($getProductInfo as $productinfo) {
            $ProductID1 = $productinfo->ProductID;
            $Date = $productinfo->Date;
            $ProductQty = $productinfo->ProductQTY;

            $getProductQTYAfterBuy = $this->orderDetaisl
                ->select(
                    DB::raw('sum(ItemsQTY) as ItemsQTY'),
                    $this->orderDetaisl->getTable() . '.ProductID'
                )
                ->Join($this->order->getTable(), $this->orderDetaisl->getTable() . '.OrderID', '=', $this->order->getTable() . '.OrderID')
                ->where('SupplierID', $id)
                ->where('ProductID', $ProductID1)
                ->where($this->order->getTable() . '.OrderState', '=', 2)
                ->where($this->orderDetaisl->getTable() . '.created_at', '>=', $Date)
                ->groupBy('ProductID')
                ->get();
//            echo $getProductQTYAfterBuy;

            if (count($getProductQTYAfterBuy) > 0) {

                foreach ($getProductQTYAfterBuy as $PQB) {

                    $ItemsQTY = $PQB->ItemsQty;
                    $ProductID = $PQB->ProductID;
                    $NproductQTY = ($ProductQty - $ItemsQTY);

                    $this->sellerproduct->where('ProductID', $ProductID)->update(['NproductQTY' => $NproductQTY]);
                }
            } else {

                $this->sellerproduct->where('ProductID', $ProductID)->update(['NproductQTY' => $ProductQty]);

            }

        }
    }

    public function MyProduct($id)
    {

//        $this->updateNproductQTY($id);
//        return;
//        return $ProductID;


        /*this part to update*/
        $getProductInfo = $this->sellerproduct
            ->where('SupplierID', $id)
            ->where('Date', '!=', '0000-00-00')
            ->get();

        foreach ($getProductInfo as $productinfo) {
            $ProductID1 = $productinfo->ProductID;
            $Date = $productinfo->Date;
            $ProductQty = $productinfo->ProductQTY;

            $getProductQTYAfterBuy = $this->orderDetaisl
                ->select(
                    DB::raw('sum(ItemsQTY) as ItemsQTY'),
                    $this->orderDetaisl->getTable() . '.ProductID'
                )
                ->Join($this->order->getTable(), $this->orderDetaisl->getTable() . '.OrderID', '=', $this->order->getTable() . '.OrderID')
                ->where('SupplierID', $id)
                ->where('ProductID', $ProductID1)
//                ->where($this->order->getTable() . '.OrderState', '=', 2)
                ->whereIn($this->order->getTable() . '.OrderState', array(2,3))
                ->where($this->orderDetaisl->getTable() . '.created_at', '>=', $Date)
                ->groupBy('ProductID')
                ->get();


            if (count($getProductQTYAfterBuy) > 0) {
//                echo $getProductQTYAfterBuy.'1-if';
                foreach ($getProductQTYAfterBuy as $PQB) {

                    $ItemsQTY = $PQB->ItemsQTY;
                    $ProductID = $PQB->ProductID;
                    $NproductQTY = ($ProductQty - $ItemsQTY);
//                    echo $getProductQTYAfterBuy.''.$ProductQty.'-'.$ItemsQTY.'='.$NproductQTY;
                    $this->sellerproduct->where('ProductID', $ProductID)->update(['NproductQTY' => $NproductQTY]);
                }
            } else {
//                echo $getProductQTYAfterBuy.'2-if';
                $this->sellerproduct->where('ProductID', $ProductID1)->update(['NproductQTY' => $ProductQty]);

            }

        }
        /*end*/

        DB::select('UPDATE `tblproduct` SET `Updated` = "0" WHERE   tblproduct.updated_at < DATE_SUB(CURDATE(), INTERVAL 31 DAY)');
        global $final;
        $final = 0;
        $output = $this->product
            ->select(
                $this->product->getTable() . '.*',
                $this->users->getTable() . '.*',
                $this->productprice->getTable() . '.*',
                $this->sellerproduct->getTable() . '.*',
                $this->size->getTable() . '.*',
                $this->category->getTable() . '.*',
                $this->brand->getTable() . '.*'
            )
            ->leftjoin($this->users->getTable(), $this->product->getTable() . '.UserID', '=', $this->users->getTable() . '.UserID')
            ->leftjoin($this->productprice->getTable(), $this->product->getTable() . '.ProductID', '=', $this->productprice->getTable() . '.ProductID')
            ->leftjoin($this->sellerproduct->getTable(), $this->product->getTable() . '.ProductID', '=', $this->sellerproduct->getTable() . '.ProductID')
            ->leftjoin($this->size->getTable(), $this->product->getTable() . '.SizeID', '=', $this->size->getTable() . '.SizeID')
            ->leftjoin($this->category->getTable(), $this->product->getTable() . '.CategoryID', '=', $this->category->getTable() . '.CategoryID')
            ->leftjoin($this->brand->getTable(), $this->product->getTable() . '.BrandID', '=', $this->brand->getTable() . '.BrandID')
            ->where($this->product->getTable() . '.Pending', '=', 1)
            ->where($this->product->getTable() . '.UserID', '=', $id)
            ->groupby($this->product->getTable() . '.ProductID')
            ->orderby($this->product->getTable() . '.ProductID', 'DESC')
            ->get();

        return Response()->json($output);
    }

    public function ProductUnderUpdate($id)
    {
        $output = $this->product
            ->select(
                $this->product->getTable() . '.*',

                $this->users->getTable() . '.*',
                $this->productprice->getTable() . '.*',
                $this->size->getTable() . '.*',
                $this->category->getTable() . '.*',
                $this->brand->getTable() . '.*'
            )
            ->leftjoin($this->users->getTable(), $this->product->getTable() . '.UserID', '=', $this->users->getTable() . '.UserID')
            ->join($this->productprice->getTable(), $this->product->getTable() . '.ProductID', '=', $this->productprice->getTable() . '.ProductID')
//            ->leftjoin($this->productcolor->getTable(), $this->product->getTable() . '.ProductID', '=', $this->productcolor->getTable() . '.ProductID')
//            ->leftjoin($this->color->getTable(), $this->productcolor->getTable() . '.ColorID', '=', $this->color->getTable() . '.ColorID')
//            ->leftjoin($this->productImage->getTable(), $this->productcolor->getTable() . '.ProductColorID', '=', $this->productImage->getTable() . '.ProductColorID')
            ->join($this->size->getTable(), $this->product->getTable() . '.SizeID', '=', $this->size->getTable() . '.SizeID')
            ->join($this->category->getTable(), $this->product->getTable() . '.CategoryID', '=', $this->category->getTable() . '.CategoryID')
            ->join($this->brand->getTable(), $this->product->getTable() . '.BrandID', '=', $this->brand->getTable() . '.BrandID')
            ->where($this->product->getTable() . '.Pending', '=', 1)
            ->where($this->product->getTable() . '.UserID', '=', $id)
            ->groupby($this->product->getTable() . '.ProductID')
            ->orderby($this->product->getTable() . '.ProductID', 'DESC')
            ->get();
        return Response()->json($output);
    }

    public function ProductAddAdd($id)
    {
        $output = $this->product
            ->select(
                $this->product->getTable() . '.*',

                $this->users->getTable() . '.*',
                $this->productprice->getTable() . '.*',
                $this->size->getTable() . '.*',
                $this->category->getTable() . '.*',
                $this->brand->getTable() . '.*'
            )
            ->leftjoin($this->users->getTable(), $this->product->getTable() . '.UserID', '=', $this->users->getTable() . '.UserID')
            ->leftjoin($this->productprice->getTable(), $this->product->getTable() . '.ProductID', '=', $this->productprice->getTable() . '.ProductID')
//            ->leftjoin($this->productcolor->getTable(), $this->product->getTable() . '.ProductID', '=', $this->productcolor->getTable() . '.ProductID')
//            ->leftjoin($this->color->getTable(), $this->productcolor->getTable() . '.ColorID', '=', $this->color->getTable() . '.ColorID')
//            ->leftjoin($this->productImage->getTable(), $this->productcolor->getTable() . '.ProductColorID', '=', $this->productImage->getTable() . '.ProductColorID')
            ->leftjoin($this->size->getTable(), $this->product->getTable() . '.SizeID', '=', $this->size->getTable() . '.SizeID')
            ->leftjoin($this->category->getTable(), $this->product->getTable() . '.CategoryID', '=', $this->category->getTable() . '.CategoryID')
            ->leftjoin($this->brand->getTable(), $this->product->getTable() . '.BrandID', '=', $this->brand->getTable() . '.BrandID')
            ->where($this->product->getTable() . '.Pending', '=', 2)
            ->where($this->product->getTable() . '.UserID', '=', $id)
            ->groupby($this->product->getTable() . '.ProductID')
            ->orderby($this->product->getTable() . '.ProductID', 'DESC')
            ->get();
        return Response()->json($output);
    }


    public function getProductForSeller($id)
    {
        $output = $this->product
            ->where($this->product->getTable() . '.Pending', '=', 1)
            ->where($this->product->getTable() . '.UserID', '=', $id)
            ->groupby($this->product->getTable() . '.ProductID')
            ->orderby($this->product->getTable() . '.ProductID', 'DESC')
            ->get();
        return Response()->json($output);
    }

    public function getProduct()
    {
        $output = $this->product
            ->select(
                $this->product->getTable() . '.*',

                $this->users->getTable() . '.*',
                $this->productprice->getTable() . '.*',
                $this->size->getTable() . '.*',
                $this->category->getTable() . '.*',
                $this->brand->getTable() . '.*'
            )
            ->leftjoin($this->users->getTable(), $this->product->getTable() . '.UserID', '=', $this->users->getTable() . '.UserID')
            ->leftjoin($this->productprice->getTable(), $this->product->getTable() . '.ProductID', '=', $this->productprice->getTable() . '.ProductID')
            ->join($this->sellerproduct->getTable(), $this->product->getTable() . '.ProductID', '=', $this->sellerproduct->getTable() . '.ProductID')
//            ->leftjoin($this->productcolor->getTable(), $this->product->getTable() . '.ProductID', '=', $this->productcolor->getTable() . '.ProductID')
//            ->leftjoin($this->color->getTable(), $this->productcolor->getTable() . '.ColorID', '=', $this->color->getTable() . '.ColorID')
//            ->leftjoin($this->productImage->getTable(), $this->productcolor->getTable() . '.ProductColorID', '=', $this->productImage->getTable() . '.ProductColorID')
            ->leftjoin($this->size->getTable(), $this->product->getTable() . '.SizeID', '=', $this->size->getTable() . '.SizeID')
            ->leftjoin($this->category->getTable(), $this->product->getTable() . '.CategoryID', '=', $this->category->getTable() . '.CategoryID')
            ->leftjoin($this->brand->getTable(), $this->product->getTable() . '.BrandID', '=', $this->brand->getTable() . '.BrandID')
            ->where($this->product->getTable() . '.Pending', '=', 1)
            ->groupby($this->product->getTable() . '.ProductID')
            ->orderby($this->product->getTable() . '.ProductID', 'DESC')
            ->get();
        return Response()->json($output);
    }

    public function getPendingProduct()
    {
        $output = $this->product
            ->select(
                $this->product->getTable() . '.*',

                $this->users->getTable() . '.*',
                $this->productprice->getTable() . '.*',
                $this->size->getTable() . '.*',
                $this->category->getTable() . '.*',
                $this->brand->getTable() . '.*'
            )
            ->leftjoin($this->users->getTable(), $this->product->getTable() . '.UserID', '=', $this->users->getTable() . '.UserID')
            ->leftjoin($this->productprice->getTable(), $this->product->getTable() . '.ProductID', '=', $this->productprice->getTable() . '.ProductID')
//            ->leftjoin($this->productcolor->getTable(), $this->product->getTable() . '.ProductID', '=', $this->productcolor->getTable() . '.ProductID')
//            ->leftjoin($this->color->getTable(), $this->productcolor->getTable() . '.ColorID', '=', $this->color->getTable() . '.ColorID')
//            ->leftjoin($this->productImage->getTable(), $this->productcolor->getTable() . '.ProductColorID', '=', $this->productImage->getTable() . '.ProductColorID')
            ->leftjoin($this->size->getTable(), $this->product->getTable() . '.SizeID', '=', $this->size->getTable() . '.SizeID')
            ->leftjoin($this->category->getTable(), $this->product->getTable() . '.CategoryID', '=', $this->category->getTable() . '.CategoryID')
            ->leftjoin($this->brand->getTable(), $this->product->getTable() . '.BrandID', '=', $this->brand->getTable() . '.BrandID')
            ->where($this->product->getTable() . '.Pending', '=', 2)
            ->groupby($this->product->getTable() . '.ProductID')
            ->orderby($this->product->getTable() . '.ProductID', 'DESC')
            ->get();
        return Response()->json($output);
    }

    public function getProductImage($id)
    {
        $outupt = $this->productcolor->with('ProductImage', 'ColorsOfProduct')
            ->where($this->productcolor->getTable() . '.ProductID', '=', $id)
            ->get();
        return Response()->json($outupt);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = Request()->all();
        $output = $this->product->create($input);
        $ProductID = $output['ProductID'];
        if (Request()->has('ProductPrice')) {
            $output = $this->productprice->create([
                "ProductID" => $ProductID,
                "ProductPrice" => $input["ProductPrice"],
                "ProductPriceDesc" => $input['ProductPriceDesc']
            ]);
        } else {
            $output = $this->productprice->create([
                "ProductID" => $ProductID,
                "ProductPriceDesc" => $input['ProductPriceDesc']
            ]);

        }


        $output = $this->product
            ->select(
                $this->product->getTable() . '.*',

                $this->users->getTable() . '.*',
                $this->productprice->getTable() . '.*',
                $this->size->getTable() . '.*',
                $this->category->getTable() . '.*',
                $this->brand->getTable() . '.*'
            )
            ->leftjoin($this->users->getTable(), $this->product->getTable() . '.UserID', '=', $this->users->getTable() . '.UserID')
            ->leftjoin($this->productprice->getTable(), $this->product->getTable() . '.ProductID', '=', $this->productprice->getTable() . '.ProductID')
//            ->leftjoin($this->productcolor->getTable(), $this->product->getTable() . '.ProductID', '=', $this->productcolor->getTable() . '.ProductID')
//            ->leftjoin($this->color->getTable(), $this->productcolor->getTable() . '.ColorID', '=', $this->color->getTable() . '.ColorID')
//            ->leftjoin($this->productImage->getTable(), $this->productcolor->getTable() . '.ProductColorID', '=', $this->productImage->getTable() . '.ProductColorID')
            ->leftjoin($this->size->getTable(), $this->product->getTable() . '.SizeID', '=', $this->size->getTable() . '.SizeID')
            ->leftjoin($this->category->getTable(), $this->product->getTable() . '.CategoryID', '=', $this->category->getTable() . '.CategoryID')
            ->leftjoin($this->brand->getTable(), $this->product->getTable() . '.BrandID', '=', $this->brand->getTable() . '.BrandID')
            ->where($this->product->getTable() . '.ProductID', '=', $ProductID)
            ->groupby($this->product->getTable() . '.ProductID')
            ->get();
        return Response()->json($output);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = Request()->all();
        $this->product->find($id)->update($input);
        $this->productprice->where('ProductID','=',$id)->update([
            "ProductPrice"=>$input['ProductPrice'],
            "ProductPriceDesc"=>$input['ProductPriceDesc']
        ]);

        $output = $this->product
            ->select(
                $this->product->getTable() . '.*',

                $this->users->getTable() . '.*',
                $this->productprice->getTable() . '.*',
                $this->size->getTable() . '.*',
                $this->category->getTable() . '.*',
                $this->brand->getTable() . '.*'
            )
            ->leftjoin($this->users->getTable(), $this->product->getTable() . '.UserID', '=', $this->users->getTable() . '.UserID')
            ->leftjoin($this->productprice->getTable(), $this->product->getTable() . '.ProductID', '=', $this->productprice->getTable() . '.ProductID')
            ->leftjoin($this->productcolor->getTable(), $this->product->getTable() . '.ProductID', '=', $this->productcolor->getTable() . '.ProductID')
            ->leftjoin($this->color->getTable(), $this->productcolor->getTable() . '.ColorID', '=', $this->color->getTable() . '.ColorID')
            ->leftjoin($this->productImage->getTable(), $this->productcolor->getTable() . '.ProductColorID', '=', $this->productImage->getTable() . '.ProductColorID')
            ->leftjoin($this->size->getTable(), $this->product->getTable() . '.SizeID', '=', $this->size->getTable() . '.SizeID')
            ->leftjoin($this->category->getTable(), $this->product->getTable() . '.CategoryID', '=', $this->category->getTable() . '.CategoryID')
            ->leftjoin($this->brand->getTable(), $this->product->getTable() . '.BrandID', '=', $this->brand->getTable() . '.BrandID')
            ->where($this->product->getTable() . '.ProductID', '=', $id)
            ->groupby($this->product->getTable() . '.ProductID')
            ->get();

        return Response()->json($output);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function InsertImage()
    {
        $input = Request()->all();
        $output = $this->productcolor->create($input);
        $baseurl = 'http://ar5ss.com/';
        $realbath = '/var/www/html/ar5ss/public';

        $input['ProductColorID'] = $output['ProductColorID'];

        $image = $input["Image"];
        $jpg_name = "photo-" . time() . ".jpg";
        $path = $realbath . "/ProductIamge/" . $jpg_name;
        $input["Image"] = $baseurl . "ProductIamge/" . $jpg_name;
        $img = substr($image, strpos($image, ",") + 1);//take string after ,
        $imgdata = base64_decode($img);
//        $img = Image::make($imgdata)->resize(384, 370)->save($path, 100);
        $success = file_put_contents($path, $imgdata);

        $output = $this->productImage->create($input);

        $outupt = $this->productcolor->with('ProductImage', 'ColorsOfProduct')
            ->where($this->productcolor->getTable() . '.ProductID', '=', $input['ProductID'])
            ->get();
        return Response()->json($outupt);
    }

    public function DeleteImage($id, $procolorid)
    {
        $outout = $this->productImage->where($this->productImage->getTable() . '.ImageID', '=', $id)->delete();
        $outout = $this->productcolor->where($this->productcolor->getTable() . '.ProductColorID', '=', $procolorid)
            ->delete();
        return Response()->json($outout);

    }

    public function AprooveProduct($id)
    {
        $output = $this->product
            ->where($this->product->getTable() . '.ProductID', '=', $id)
            ->update([
                'Pending' => 1,
                'GroupShowID' => 3
            ]);

    }

    public function RemoveProduct($id)
    {
        $this->product->where($this->product->getTable() . '.ProductID', '=', $id)->delete();
        $this->productprice->where($this->productprice->getTable() . '.ProductID', '=', $id)->delete();
    }


    public function DeleteProduct($id)
    {
        $checkCart = $this->cart->where($this->cart->getTable() . '.ProductID', '=', $id)->where($this->cart->getTable() . '.CartState', '=', 1)->get();
        $checkOrder = $this->order->leftJoin($this->orderDetaisl->getTable(), $this->order->getTable() . '.OrderID', '=', $this->orderDetaisl->getTable() . '.OrderID')->where($this->order->getTable() . '.OrderState', '=', 1)->get();
        $sellerProduct = $this->sellerproduct->where($this->sellerproduct->getTable() . '.ProductID', '=', $id)->get();

        if (count($checkCart) > 0 && count($checkOrder) > 0 && count($sellerProduct) > 0) {

            return ['state' => 203];

        } else {

            $this->product->where($this->product->getTable() . '.ProductID', '=', $id)->delete();
            $this->productprice->where($this->productprice->getTable() . '.ProductID', '=', $id)->delete();
            $this->productcolor->where($this->productcolor->getTable() . '.ProductID', '=', $id)->delete();

            $PtoductColorID = $this->productcolor->where($this->productcolor->getTable() . '.ProductID', '=', $id)->get();
            foreach ($PtoductColorID as $prc) {
                $PtoductColorID = $prc->PtoductColorID;
                $this->productImage->where($this->productImage->getTable() . '.ProductColorID', '=', $PtoductColorID)->delete();

            }

            return ['state' => 202];
        }

    }

    public function UpdatedProductForMonthe($id)
    {
        $this->product->find($id)->update([
            "Updated" => 1
        ]);
        $output = $this->product->where('ProductID', '=', $id)->get();
        return Response()->json($output[0]);
    }

    public function UpdateProductQty($id)
    {
        $input = Request()->all();

        $ProductQTY = $input['ProductQTY'];
        $Date = $input['Date'];

        $this->sellerproduct->where('ProductID', '=', $id)->update([
            "ProductQTY" => $ProductQTY,
            "Date" => $Date,
            "allow" => 1
        ]);
        $output = $this->sellerproduct->where('ProductID', '=', $id)->get();
        return Response()->json($output);
    }

    public function alertproduct($id)
    {
        $output = $this->product
            ->leftjoin($this->sellerproduct->getTable(), $this->product->getTable() . '.ProductID', '=', $this->sellerproduct->getTable() . '.ProductID')
            ->where($this->sellerproduct->getTable() . '.SupplierID', '=', $id)->get();

        return $output;

    }


    public function getFinalProductQTY($prodctID, $StorID, $Date, $UserID)
    {

        $output = $this->orderDetaisl
            ->join($this->product->getTable(), $this->orderDetaisl->getTable() . '.ProductID', '=', $this->product->getTable() . '.ProductID')
            ->Join($this->order->getTable(), $this->orderDetaisl->getTable() . '.OrderID', '=', $this->order->getTable() . '.OrderID')
            ->select(DB::raw('Sum(ItemsQTY) as ItemsQTY'))
            ->where($this->orderDetaisl->getTable() . '.ProductID', '=', $prodctID)
            ->where($this->orderDetaisl->getTable() . '.StoreID', '=', $StorID)
            ->where($this->orderDetaisl->getTable() . '.SupplierID', '=', $UserID)
            ->where($this->orderDetaisl->getTable() . '.created_at', '>=', $Date)
            ->whereIn($this->order->getTable() . '.OrderState', array(2,3))
//            ->where($this->order->getTable() . '.OrderState', '=', 3)
            ->orderBy($this->product->getTable() . '.ProductID', 'DESC')
            ->get();

        return $output;

    }

//    public function getFinalProductlistQTY()
//    {
//        $input = Request()->all();
//
//
//        $output = $this->orderDetaisl
//            ->join($this->product->getTable(), $this->orderDetaisl->getTable() . '.ProductID', '=', $this->product->getTable() . '.ProductID')
//            ->Join($this->order->getTable(), $this->orderDetaisl->getTable() . '.OrderID', '=', $this->order->getTable() . '.OrderID')
//            ->select(DB::raw('Sum(ItemsQTY) as ItemsQTY'))
//            ->whereIn($this->orderDetaisl->getTable() . '.ProductID', $prodctID)
//            ->whereIn($this->orderDetaisl->getTable() . '.StoreID', '=', $StorID)
//            ->whereIn($this->orderDetaisl->getTable() . '.SupplierID', '=', $UserID)
//            ->whereIn($this->orderDetaisl->getTable() . '.created_at', '>=', $Date)
//            ->where($this->order->getTable() . '.OrderState', '=', 3)
//            ->orderBy($this->product->getTable() . '.ProductID', 'DESC')
//            ->get();
//        return $output;
//
//    }
}
