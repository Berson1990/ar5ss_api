<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Http\Models\Users;
use App\Http\Models\Seller;
use App\Http\Models\SellerStore;
use App\Http\Models\Location;
use App\Http\Models\Brand;
use App\Http\Models\Category;
use App\Http\Models\Product;
use App\Http\Models\Order;
use App\Http\Models\OrderDetails;
use App\Http\Models\ProductColor;
use App\Http\Models\Size;
use App\Http\Models\ProductPrice;
use App\Http\Models\SellerProduct;
use App\Http\Models\City;
use App\Http\Models\AdministrativeCirculars;

use DB;
use GuzzleHttp\Psr7\Request;


class SuppliersController extends Controller
{
    public function __construct()
    {
        $this->users = new Users();
        $this->location = new Location();
        $this->brand = new Brand();
        $this->category = new Category();
        $this->product = new Product();
        $this->order = new Order();
        $this->orderDetails = new OrderDetails();
        $this->productcolor = new ProductColor();
        $this->productPrice = new ProductPrice();
        $this->size = new Size();
        $this->sellerProduct = new SellerProduct();
        $this->sellerStore = new SellerStore();
        $this->seller = new Seller();
        $this->city = new City();
        $this->administrativecirculars = new AdministrativeCirculars();
    }


    public function getSuppliers()
    {
//        $output = $this->users
//            ->select(
//                $this->users->getTable() . '.*',
//                DB::raw('count(" tblsellerproduct.ProductID " )as ProductNumber')
////                DB::raw('count("' . $this->order->getTable() . '.OrderID' . '")as OrderClosed')
//            )
//            ->leftJoin($this->sellerProduct->getTable(), $this->users->getTable() . '.UserID', $this->sellerProduct->getTable() . '.SupplierID')
////            ->leftJoin($this->orderDetails->getTable(), $this->users->getTable() . '.UserID', $this->orderDetails->getTable() . '.SupplierID')
////            ->leftJoin($this->order->getTable(), $this->orderDetails->getTable() . '.OrderID', $this->order->getTable() . '.OrderID')
//            ->where($this->users->getTable() . '.UseType', '=', 3)
//            ->where($this->users->getTable() . '.UseType', '!=', 1)
////            ->where($this->order->getTable() . '.OrderState', '=', 2)
//            ->groupBy($this->users->getTable() . '.UserID')
////            ->groupBy($this->sellerProduct->getTable() . '.SellerID')
////            ->groupBy($this->order->getTable() . '.UserID')
//            ->get();
        $output = DB::SELECT("Select   DISTINCT  count(tblorder.OrderID) as OrderClosed  ,tblusers.*  from tblusers
left outer join  tblorderdetails on tblusers.UserID =  tblorderdetails.SupplierID 
left outer join tblorder on tblorderdetails.OrderID = tblorder.OrderID  and tblorder.OrderState = 3
where tblusers.UseType = 3
and tblusers.UseType != 1
group by tblusers.UserID
ORDER  BY  tblusers.created_at DESC
");
        return Response()->json($output);
    }

//    public function getOrderColsed($id){
//        $output  = $this->order->leftJoin($this->orderDetails->getTable(),$this->order->getTable().'.OrderID','=',$this->orderDetails->getTable().'.OrdderID')
//        ->leftJoin($this->seller->getTable(),$this->orderDetails->getTable().'.SellaerID', '=',$this->seller->getTable().'.SellerID')
//        ->;
//
//    }


    public function getNumberofProduct($id)
    {

        $output = $this->sellerProduct
            ->select(DB::raw("count('tblsellerproduct.ProductID')as ProductNumber  "),
                $this->seller->getTable() . '.*'
            )
            ->leftJoin($this->sellerStore->getTable(), $this->sellerProduct->getTable() . '.StoreID', '=', $this->sellerStore->getTable() . '.StoreID')
            ->leftJoin($this->seller->getTable(), $this->sellerStore->getTable() . '.SellerID', '=', $this->seller->getTable() . '.SellerId')
            ->where($this->sellerProduct->getTable() . '.SupplierID', '=', $id)
            ->groupBy($this->sellerProduct->getTable() . '.StoreID')
            ->get();
        return Response()->json($output);
    }

    public function createNewSuppliers()
    {

        $input = Request()->all();
        $Email = $input['Email'];
        $check = $this->users->where('Email', '=', $Email)->get();
        if (Count($check) > 0) {
            $output = ['Erorr' => 'This Email is Exist'];
        } else {

            $output = $this->users->create($input);
            $UserID = $output->UserID;
            $output = $this->users
                ->select(
                    $this->users->getTable() . '.*',
                    DB::raw('count("' . $this->sellerProduct->getTable() . '.ProductID' . '")as ProductNumber')
                )
                ->leftJoin($this->sellerProduct->getTable(), $this->users->getTable() . '.UserID', $this->sellerProduct->getTable() . '.SupplierID')
                ->where($this->users->getTable() . '.UserID', '=', $UserID)
                ->groupBy($this->users->getTable() . '.UserID')
                ->orderBy($this->users->getTable() . '.created_at', 'DESC')
                ->get();
            return Response()->json($output);


        }
        return Response()->json($output);
    }

    public function updateSuppliers($id)
    {
        $baseurl = 'http://ar5ss.com/ar5ss/public/';
        $input = Request()->all();
        if ($input['Image'] == '') {
            $output = Users::find($id)->update([
                "Name" => $input["Name"],
                "Password" => $input["Password"],
                "Email" => $input["Email"],
                "Mobile" => $input["Mobile"],
                "Percentage" => $input["Percentage"],
            ]);
            $output = Users::where('UserID', '=', $id)->get();
            return Response()->json($output['0']);

        } else {
            $image = $input["Image"];
            $jpg_name = "photo-" . time() . ".jpg";
            $path = public_path("/UserImages/") . $jpg_name;
            $input["Image"] = $baseurl . "UserImages/" . $jpg_name;
            $img = substr($image, strpos($image, ",") + 1);//take string after ,
            $imgdata = base64_decode($img);
            $success = file_put_contents($path, $imgdata);
            $output = Users::find($id)->update($input);
            $output = Users::where('UserID', '=', $id)->get();
            return Response()->json($output['0']);

        }

    }

    public function stopSuppliers($id)
    {
        $input = Request()->all();
        $UserState = $input['UserState'];
        if ($UserState == 1) {
            $UserState = 0;
        } else {
            $UserState = 1;
        }
        $output = $this->users->where($this->users->getTable() . '.UserID', '=', $id)
            ->update(['UserState' => $UserState]);
        $output = $this->users->where($this->users->getTable() . '.UserID', '=', $id)
            ->get();

        return Response()->json($output);
    }

//    public function SgininAdmin()
//    {
//        $input = Request()->all();
////        $input['Password'] = MD5($input['Password']);
//
//        $output = $this->users
//            ->where($this->users->getTable() . '.Email', '=', $input['Email'])
//            ->where($this->users->getTable() . '.Password', '=', $input['Password'])
//            ->where($this->users->getTable() . '.UseType', '=', $input['UseType'])
//            ->get();
//
//
//        if (Count($output) == 0) {
//
//            return ["error" => "invalid Account"];
//        } else {
//
//            $this->users->where($this->users->getTable() . '.Email', '=', $input['Email'])->update([
//                "IsActive" => 1
//            ]);
//            return Response()->json(["output" => $output, "success" => "valid Account"]);
//        }
//    }

    public function SgininAdmin()
    {
        $input = Request()->all();
//        $input['Password'] = MD5($input['Password']);

        $output = $this->users
            ->where($this->users->getTable() . '.Email', '=', $input['Email'])
            ->where($this->users->getTable() . '.Password', '=', $input['Password'])
            ->where($this->users->getTable() . '.UseType', '=', $input['UseType'])
            ->get();


        if (Count($output) == 0) {

            return ["error" => "invalid Account"];
        } else {

            if ($output['0']['UserState'] == 0) {
                return ["error" => "Account Blocked"];
            } else {
                $this->users->where($this->users->getTable() . '.Email', '=', $input['Email'])->update([
                    "IsActive" => 1
                ]);
            }

            return Response()->json(["output" => $output, "success" => "valid Account"]);


        }
    }


    public
    function setAdmin($id, $useState)
    {
        if ($useState == 3) {
            $this->users->where('UserID', '=', $id)->update(['UseType' => 1]);
            return ['state' => 1];
        } else if ($useState == 1) {
            $this->users->where('UserID', '=', $id)->update(['UseType' => 3]);
            return ['state' => 3];
        }


    }

    public
    function GetAdminForPanel()
    {
        $output = $this->users
//            ->where($this->users->getTable() . '.UseType', '=', 3)
            ->Orwhere($this->users->getTable() . '.UseType', '=', 1)
            ->get();
        return Response()->json($output);
    }

    public
    function getUsers()
    {
        $output = $this->users
            ->with('Location')
            ->select(

                $this->users->getTable() . '.*',
//                $this->productPrice->getTable() . '.*',
                DB::raw('Count("' . $this->order->getTable() . '.OrderID' . '") as Purchases')
//                DB::raw('SUM("' . $this->productPrice->getTable() . '.ProductPriceDesc' . '") as TotalAmount')
            )
//            ->sum($this->productPrice->getTable().'.ProductPriceDesc')
            ->leftJoin($this->order->getTable(), $this->users->getTable() . '.UserID', '=', $this->order->getTable() . '.UserID')
            ->leftJoin($this->orderDetails->getTable(), $this->order->getTable() . '.OrderID', '=', $this->orderDetails->getTable() . '.OrderID')
            ->where($this->users->getTable() . '.UseType', '=', 2)
//            ->where($this->order->getTable() . '.OrderState', '=', 2)
            ->groupBy($this->users->getTable() . '.UserID')
            ->get();
        return Response()->json($output);
    }

    public
    function getEmployee()
    {
        $output = $this->users
            ->where($this->users->getTable() . '.UseType', '=', 1)
            ->get();
        return Response()->json($output);

    }

    public
    function DeleteEmployee($id)
    {
        $this->users->where($this->users->getTable() . '.UserID', '=', $id)->delete();
        return ['state' => 202];
    }

    public
    function getClintOrder($id, $orderstate)
    {


        $output = $this->order
            ->select(
                $this->users->getTable() . '.*',
                $this->product->getTable() . '.*',
                $this->productPrice->getTable() . '.*',
                $this->brand->getTable() . '.*',
                $this->category->getTable() . '.*',
                $this->productcolor->getTable() . '.*',
                $this->size->getTable() . '.*',
                $this->seller->getTable() . '.*',
                $this->sellerProduct->getTable() . '.*',
                $this->order->getTable() . '.*',
                $this->orderDetails->getTable() . '.*',
                DB::raw('Suppliers.Name as SupplierName,Suppliers.Mobile As SuppliersNumber')


            )
            ->leftjoin($this->users->getTable(), $this->order->getTable() . '.UserID', '=', $this->users->getTable() . '.UserID')
            ->join($this->orderDetails->getTable(), $this->order->getTable() . '.OrderID', '=', $this->orderDetails->getTable() . '.OrderID')
            ->join($this->product->getTable(), $this->orderDetails->getTable() . '.ProductID', '=', $this->product->getTable() . '.ProductID')
            ->join($this->productPrice->getTable(), $this->product->getTable() . '.ProductID', '=', $this->productPrice->getTable() . '.ProductID')
            ->leftjoin($this->sellerProduct->getTable(), $this->product->getTable() . '.ProductID', '=', $this->sellerProduct->getTable() . '.ProductID')
            ->leftjoin($this->seller->getTable(), $this->sellerProduct->getTable() . '.SupplierID', '=', $this->seller->getTable() . '.UserID')
            ->leftjoin('tblusers  as Suppliers', $this->seller->getTable() . '.UserID', '=', 'Suppliers.UserID')
            ->join($this->size->getTable(), $this->product->getTable() . '.SizeID', '=', $this->size->getTable() . '.SizeID')
            ->join($this->brand->getTable(), $this->product->getTable() . '.BrandID', '=', $this->brand->getTable() . '.BrandID')
            ->join($this->category->getTable(), $this->product->getTable() . '.CategoryID', '=', $this->category->getTable() . '.CategoryID')
            ->join($this->productcolor->getTable(), $this->product->getTable() . '.ProductID', '=', $this->productcolor->getTable() . '.ProductID')
            ->where($this->order->getTable() . '.UserID', '=', $id)
            ->where($this->order->getTable() . '.OrderState', '=', $orderstate)
            ->groupBy($this->orderDetails->getTable() . '.ProductID')
            ->orderBy($this->order->getTable() . '.created_at', 'DESC')
            ->get();
        return Response()->json($output);

    }

    public
    function StoreForEveryProduct($id)
    {
        $output = $this->seller
            ->select(
                $this->product->getTable() . '.*',
                $this->productPrice->getTable() . '.*',
                $this->sellerStore->getTable() . '.*',
                DB::raw('Count("' . $this->sellerProduct->getTable() . '.ProductID' . '")as ProductNumber')
            )
            ->leftjoin($this->sellerStore->getTable(), $this->seller->getTable() . '.SellerID', '=', $this->sellerStore->getTable() . '.SellerID')
            ->leftjoin($this->sellerProduct->getTable(), $this->sellerStore->getTable() . '.StoreID', '=', $this->sellerProduct->getTable() . '.StoreID')
            ->leftjoin($this->product->getTable(), $this->sellerProduct->getTable() . '.ProductID', '=', $this->product->getTable() . '.ProductID')
            ->join($this->productPrice->getTable(), $this->product->getTable() . '.ProductID', '=', $this->productPrice->getTable() . '.ProductID')
            ->where($this->seller->getTable() . '.UserID', '=', $id)
            ->groupby($this->sellerProduct->getTable() . '.ProductID')
            ->get();
        return Response()->json($output);

    }

    public
    function OrdersForSupplers($orderstate, $UserID)
    {

        $output = $this->order
            ->select(
                $this->users->getTable() . '.*',
                $this->location->getTable() . '.*',
                $this->orderDetails->getTable() . '.*',
                $this->product->getTable() . '.*',
                $this->productPrice->getTable() . '.*',
                $this->size->getTable() . '.*',
                $this->brand->getTable() . '.*',
                $this->category->getTable() . '.*',
                $this->productcolor->getTable() . '.*',
                DB::raw('Count(tblorder.OrderID)as NumberOfOperation'),
                DB::raw('Count(tblproduct.ProductID)as NumberOfProduct'),
                DB::raw('Sum(tblproductprice.ProductPriceDesc)as Total'),
                $this->order->getTable() . '.*'
            )
            ->leftjoin($this->users->getTable(), $this->order->getTable() . '.UserID', '=', $this->users->getTable() . '.UserID')
            ->leftjoin($this->location->getTable(), $this->order->getTable() . '.LocationID', '=', $this->location->getTable() . '.LocationID')
            ->leftjoin($this->orderDetails->getTable(), $this->order->getTable() . '.OrderID', '=', $this->orderDetails->getTable() . '.OrderID')
            ->leftjoin($this->product->getTable(), $this->orderDetails->getTable() . '.ProductID', '=', $this->product->getTable() . '.ProductID')
            ->leftjoin($this->productPrice->getTable(), $this->product->getTable() . '.ProductID', '=', $this->productPrice->getTable() . '.ProductID')
            ->leftjoin($this->size->getTable(), $this->product->getTable() . '.SizeID', '=', $this->size->getTable() . '.SizeID')
            ->leftjoin($this->brand->getTable(), $this->product->getTable() . '.BrandID', '=', $this->brand->getTable() . '.BrandID')
            ->leftjoin($this->category->getTable(), $this->product->getTable() . '.CategoryID', '=', $this->category->getTable() . '.CategoryID')
            ->leftjoin($this->productcolor->getTable(), $this->product->getTable() . '.ProductID', '=', $this->productcolor->getTable() . '.ProductID')
//            ->where($this->seller->getTable() . '.SellerId', '=', $id)
            ->where($this->order->getTable() . '.OrderState', '=', $orderstate)
            ->where($this->orderDetails->getTable() . '.SupplierID', '=', $UserID)
            ->groupBy($this->order->getTable() . '.OrderID')
            ->groupBy($this->orderDetails->getTable() . '.ProductID')
            ->orderBy($this->order->getTable() . '.created_at', 'DESC')
            ->get();
        return Response()->json($output);

    }

    public
    function getStores($id)
    {
        $output = $this->seller->where($this->seller->getTable() . '.UserID', '=', $id)->get();
        return Response()->json($output);
    }

    public
    function CloseOrder($id, $orderState)
    {

        $this->order->find($id)->update([
            "OrderState" => $orderState
        ]);

        if ($orderState == 2) {


            $getLastUpdateInSellerProduct = $this->sellerProduct
                ->leftJoin($this->orderDetails->getTable(), $this->sellerProduct->getTable() . '.ProductID', '=', $this->orderDetails->getTable() . '.ProductID')
                ->select(
                    $this->orderDetails->getTable() . '.ProductID',
                    $this->sellerProduct->getTable() . '.Date',
                    $this->sellerProduct->getTable() . '.ProductQTY',
                    $this->sellerProduct->getTable() . '.StoreID'
                )
                ->where($this->orderDetails->getTable() . '.OrderID', '=', $id)
                ->get();

//return $getLastUpdateInSellerProduct;
            foreach ($getLastUpdateInSellerProduct as $ProductDate) {
                $Date = $ProductDate->Date;
                $ProductQTY = $ProductDate->ProductQTY;
                $ProductID = $ProductDate->ProductID;
                $StoreID = $ProductDate->StoreID;
                $checkItemQTY = $this->order
                    ->Join($this->orderDetails->getTable(), $this->order->getTable() . '.OrderID', '=', $this->orderDetails->getTable() . '.OrderID')
                    ->select(DB::raw('Sum(ItemsQTY) as ItemsQTY'), $this->orderDetails->getTable() . '.ProductID')
                    ->where($this->orderDetails->getTable() . '.ProductID', '=', $ProductID)
                    ->where($this->orderDetails->getTable() . '.created_at', '>=', $Date)
                    ->where($this->order->getTable() . '.OrderState', '=', 2)
                    ->get();

//return $checkItemQTY;
                global $ItemsQty;
                foreach ($checkItemQTY as $Items) {
                    $ItemsQty = $Items->ItemsQTY;
                    $productId = $Items->ProductID;

                    $product = $this->sellerProduct
                        ->where($this->sellerProduct->getTable() . '.ProductID', '=', $productId)
                        ->where($this->sellerProduct->getTable() . '.StoreID', '=', $StoreID)
                        ->where($this->sellerProduct->getTable() . '.ProductQTY', '=', $ItemsQty)
                        ->get();

//                    return $product;
                    if (count($product) > 0) {

                        foreach ($product as $productlist) {
                            $product_id = $productlist->ProductID;

                            $this->sellerProduct->where('ProductID', '=', $product_id)->update(['allow' => 0]);
                        }

                    }

                }
            }
        }

    }

    public
    function SupplierCancelOrder($id)
    {
        $input = Request()->all();
        $RefusedReason = $input['RefusedReason'];
        $this->order->find($id)->update([
            "OrderState" => 4,
            "RefusedReason" => $RefusedReason
        ]);
    }

    public
    function RefusedOrder($id)
    {
        $input = Request()->all();
        $RefusedReason = $input['RefusedReason'];
        $this->order->find($id)->update([
            "OrderState" => 5,
            "RefusedReason" => $RefusedReason
        ]);
//        $output = $this->order->leftJoin($this->order)


//        $output = $this->order->where('OrderID', '=', $id)->get();
//        $OrderID = $output[0]['OrderID'];
//        $UserID = $output[0]['UserID'];
//        $User = $this->users->where('UserID', '=', $UserID)->get();
//        $Token = $User[0]['Token'];
//        $this->pushAndroid($OrderID, $Token);


    }

    public
    function LateOrder($UserID)
    {

        $output =
            DB::SELECT("select * , 
count(tblorder.OrderID) as NumberOfOperation , count(tblproduct.ProductID) as NumberOfProduct , sum(tblproductprice.ProductPriceDesc)as ProductPriceDesc
from `tblorder`

inner join `tblorderdetails` on `tblorder`.`OrderID` = `tblorderdetails`.`OrderID` 
inner join `tblproduct` on `tblorderdetails`.`ProductID` = `tblproduct`.`ProductID`
inner join `tblproductprice` on `tblproduct`.`ProductID` = `tblproductprice`.`ProductID`
inner join `tblsize` on `tblproduct`.`SizeID` = `tblsize`.`SizeID` 
inner join `tblbrand` on `tblproduct`.`BrandID` = `tblproduct`.`BrandID` 
inner join `tblcategory` on `tblproduct`.`CategoryID` = `tblcategory`.`CategoryID`
 where tblorder.created_at < DATE_SUB(CURDATE(), INTERVAL 7 DAY)
 and `tblorder`.`OrderState` = 2  
 and `tblorderdetails`.`SupplierID` = " . $UserID . "
 group by `tblorder`.`OrderID`
 Order By tblorder.created_at DESC");

        return Response()->json($output);

    }

    public
    function AdminLateOrder()
    {
        $input = Request()->all();
        $FromDate = $input['FromDate'];
        $ToDate = $input['ToDate'];
        $BrandID = $input['BrandID'];
        $CategoryID = $input['CategoryID'];
        $ProductID = $input['ProductID'];
        $UserID = $input['UserID'];

        $output = $this->order
            ->select(
                $this->users->getTable() . '.*',
                $this->product->getTable() . '.*',
                $this->productPrice->getTable() . '.*',
                $this->brand->getTable() . '.*',
                $this->category->getTable() . '.*',
                $this->productcolor->getTable() . '.*',
                $this->size->getTable() . '.*',
                $this->seller->getTable() . '.*',
                $this->sellerProduct->getTable() . '.*',
                $this->order->getTable() . '.*',
                $this->orderDetails->getTable() . '.*',
//                DB::raw('Suppliers.Name as SupplierName'),
                DB::raw('Count( "' . $this->order->getTable() . '.OrderID' . ' ") as NumberOfOperation')
//                DB::raw('Count("' . $this->orderDetails->getTable() . '.ProductID' . '" )as NumberOfProduct')
//                DB::raw('SUM( "' . $this->productPrice->getTable() . '.ProductPriceDesc' . '" )as TotalCost')

            )
            ->leftjoin($this->orderDetails->getTable(), $this->order->getTable() . '.OrderID', '=', $this->orderDetails->getTable() . '.OrderID')
            ->leftjoin($this->users->getTable(), $this->order->getTable() . '.UserID', '=', $this->users->getTable() . '.UserID')
            ->leftjoin($this->product->getTable(), $this->orderDetails->getTable() . '.ProductID', '=', $this->product->getTable() . '.ProductID')
            ->leftjoin($this->sellerProduct->getTable(), $this->product->getTable() . '.ProductID', '=', $this->sellerProduct->getTable() . '.ProductID')
            ->leftjoin($this->seller->getTable(), $this->sellerProduct->getTable() . '.SupplierID', '=', $this->seller->getTable() . '.UserID')
            ->leftjoin('tblusers  as Suppliers', $this->seller->getTable() . '.UserID', '=', 'Suppliers.UserID')
            ->leftjoin($this->productPrice->getTable(), $this->product->getTable() . '.ProductID', '=', $this->productPrice->getTable() . '.ProductID')
            ->leftjoin($this->size->getTable(), $this->product->getTable() . '.SizeID', '=', $this->size->getTable() . '.SizeID')
            ->leftjoin($this->brand->getTable(), $this->product->getTable() . '.BrandID', '=', $this->product->getTable() . '.BrandID')
            ->leftjoin($this->category->getTable(), $this->product->getTable() . '.CategoryID', '=', $this->category->getTable() . '.CategoryID')
            ->leftjoin($this->productcolor->getTable(), $this->product->getTable() . '.ProductID', '=', $this->productcolor->getTable() . '.ProductID');
        if ($BrandID > 0) {
            $output = $output->where($this->product->getTable() . '.BrandID', '=', $BrandID);
        }
        if ($CategoryID > 0) {
            $output = $output->where($this->product->getTable() . '.CategoryID', '=', $CategoryID);
        }
        if ($ProductID > 0) {
            $output = $output->where($this->product->getTable() . '.ProductID', '=', $ProductID);
        }
        if ($UserID > 0) {
            $output = $output->where($this->order->getTable() . '.UserID', '=', $UserID);
        }

        $output =
            $output->where($this->order->getTable() . '.OrderState', '=', 2)
//                ->where($this->order->getTable() . '.OrderState', '!=', 3)
//                ->where($this->order->getTable() . '.OrderState', '!=', 5)
                ->where($this->product->getTable() . '.created_at', '>=', $FromDate)
                ->where($this->product->getTable() . '.created_at', '<=', $ToDate)
                ->where(DB::raw('tblorder.created_at < DATE_SUB(CURDATE(), INTERVAL 7 DAY)'))
//                ->groupby($this->product->getTable() . '.ProductID');
                ->groupby($this->order->getTable() . '.OrderID')
                ->orderBy($this->order->getTable() . '.created_at', 'DESC');
        $output = $output->get();

        return Response()->json($output);
    }

    public
    function AbstarctOrderState($id)
    {

//        $NewOrder = $this->order
//            ->select(
//                DB::raw(' (Count("' . $this->order->getTable() . '.OrderID' . '"))as NewOrder')
//            )
//            ->leftJoin($this->orderDetails->getTable(), $this->order->getTable() . '.OrderID', '=', $this->order->getTable() . '.OrderID')
//            ->where($this->order->getTable() . '.OrderState', '=', 1)
//            ->where($this->orderDetails->getTable() . '.SupplierID', '=', $id)
//            ->groupBy($this->orderDetails->getTable() . '.OrderDetailsID')
//            ->get();
//        $NewOrder = $this->order
//            ->leftJoin($this->orderDetails->getTable(), $this->order->getTable() . '.OrderID', '=', $this->order->getTable() . '.OrderID')
//            ->where($this->order->getTable() . '.OrderState', '=', 1)
//            ->where($this->orderDetails->getTable() . '.SupplierID', '=', $id)
////            ->groupBy($this->order->getTable() . '.OrderID')
//            ->count();
//            ->get();
        $NewOrder = DB::Select('select count(OrderDetailsID) as NewOrder  from tblorder 
 join tblorderdetails on tblorder.OrderID = tblorderdetails.OrderID 
 where tblorderdetails.SupplierID = "' . $id . '" 
 and  tblorder.OrderState = 1
 -- group by tblorder.OrderID
');
//        $NewOrder = $NewOrder['NewOrder'];
        $PendingOrder = DB::Select('select count(OrderDetailsID) as  PendingOrder from tblorder 
 join tblorderdetails on tblorder.OrderID = tblorderdetails.OrderID 
 where tblorderdetails.SupplierID = "' . $id . '" 
 and  tblorder.OrderState = 2
 -- group by tblorder.OrderID
');
//            $this->order
//
//            ->Join($this->orderDetails->getTable(), $this->order->getTable() . '.OrderID', '=', $this->order->getTable() . '.OrderID')
//            ->where($this->order->getTable() . '.OrderState', '=', 2)
//            ->where($this->orderDetails->getTable() . '.SupplierID', '=', $id)
//            ->groupBy($this->order->getTable() . '.OrderID')
//            ->get();

//        $ClosedOrder = $this->order
//            ->select(
//                DB::raw('DISTINCT  Count("' . $this->order->getTable() . '.OrderID' . '")as ClosedOrder')
//            )
//            ->leftJoin($this->orderDetails->getTable(), $this->order->getTable() . '.OrderID', '=', $this->order->getTable() . '.OrderID')
//            ->where($this->order->getTable() . '.OrderState', '=', 3)
//            ->where($this->orderDetails->getTable() . '.SupplierID', '=', $id)
//            ->groupBy($this->order->getTable() . '.OrderID')
//            ->get();
        $ClosedOrder = DB::Select(' select count(OrderDetailsID) as ClosedOrder from tblorder 
 join tblorderdetails on tblorder.OrderID = tblorderdetails.OrderID 
 where tblorderdetails.SupplierID = "' . $id . '" 
 and  tblorder.OrderState = 3
 -- group by tblorder.OrderID
 ');

        $CanceldOrder = $this->order
            ->leftJoin($this->orderDetails->getTable(), $this->order->getTable() . '.OrderID', '=', $this->order->getTable() . '.OrderID')
            ->where($this->order->getTable() . '.OrderState', '=', 4)
            ->where($this->orderDetails->getTable() . '.SupplierID', '=', $id)
            ->groupBy($this->order->getTable() . '.OrderID')
            ->get();
        $CanceldOrder = count($CanceldOrder);

        $LateOrder =
            DB::SELECT("select   DISTINCT count(OrderDetailsID) as LateOrder  from `tblorder`
left join tblorderdetails on  tblorderdetails.OrderID = tblorder.OrderID
 where tblorder.created_at < DATE_SUB(CURDATE(), INTERVAL 7 DAY)
 and tblorderdetails.SupplierID = " . $id . "
 and OrderState = 2

 
 -- GROUP BY tblorderdetails.OrderDetailsID");

//        $LateOrder = count($LateOrder);

        return Response()->json([
            "NewOrder" => $NewOrder,
            "PendingOrder" => $PendingOrder,
            "ClosedOrder" => $ClosedOrder,
            "CanceldOrder" => $CanceldOrder,
            "LateOrder" => $LateOrder

        ]);

    }


    public
    function getCity()
    {
        $output = $this->city->all();
        return Response()->json($output);
    }

    public
    function addadministrativecirculars()
    {
        $input = Request()->all();
        $output = $this->administrativecirculars->create($input);
        return Response()->json($output);

    }

    public
    function getaddadministrativecirculars()
    {
        $output = $this->administrativecirculars->all();
        return Response()->json($output);

    }

    public
    function putaddadministrativecirculars($id)
    {
        $input = Request()->all();
        $output = $this->administrativecirculars->find($id)->update($input);
        $output = $this->administrativecirculars->where('ID', '=', $id)->get();
        return Response()->json($output);

    }

    public
    function deleteaddadministrativecirculars($id)
    {
        $output = $this->administrativecirculars->find($id)->delete();
        return Response()->json($output);

    }

    public
    function SellerPosition()
    {

        $output = $this->seller
            ->leftJoin($this->sellerStore->getTable(), $this->seller->getTable() . '.SellerID', '=', $this->sellerStore->getTable() . '.SellerID')
            ->get();
        return Response()->json($output);


    }


    function pushAndroid($OrderID, $Token)
    {


        $registrationIds = array($Token);
        $Title = 'لقد تم الغاء طلبك';
        $msg = array
        (
            'OrderID' => $OrderID,
            'title' => $Title,
            'type' => 'rejectOrder',
            'subtitle' => '',
            'tickerText' => '',
            'vibrate' => 1,
            'sound' => 1,
        );

        $fields = array
        (
            'registration_ids' => $registrationIds,
            'data' => $msg,
        );

        $headers = array
        ( //Google API Key
            'Authorization: key=' . 'AAAAL5Jp3ZM:APA91bGNZIjvQQPGji1caPnx9LJqRUoCTjWq6BnH-QOaQTlWQlFFxcNKVO9D3lMuEWb8inbItR6kv524KIeEIAScQ0oVaVGCj9HUihr8GNF4A6obZAPCaaY4f2sbLp9iQensyskDLnta',
            'Content-Type: application/json',
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://android.googleapis.com/gcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);

//         print_r($registrationIds);
//         echo $result;
//         echo ("1");
    }


}
