<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Http\Models\Brand;
use App\Http\Models\Category;
use App\Http\Models\Product;
use App\Http\Models\Order;
use App\Http\Models\OrderDetails;
use App\Http\Models\ProductColor;
use App\Http\Models\Size;
use App\Http\Models\ProductPrice;
use App\Http\Models\Users;
use App\Http\Models\Seller;
use App\Http\Models\SellerProduct;
use App\Http\Models\Payment;
use App\Http\Models\Ar5sspercent;
Use DB;
use Carbon\Carbon;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;


class Ar5ssReportController extends Controller
{
    public function __construct()
    {
        $this->brand = new Brand();
        $this->category = new Category();
        $this->product = new Product();
        $this->order = new Order();
        $this->orderDetails = new OrderDetails();
        $this->productcolor = new ProductColor();
        $this->productPrice = new ProductPrice();
        $this->size = new Size();
        $this->users = new Users();
        $this->seller = new Seller();
        $this->sellerProduct = new SellerProduct();
        $this->payment = new Payment();
        $this->ar5sspercent = new Ar5sspercent();

    }

    public function SelesReportAbstract()
    {

        $input = Request()->all();
        $FromDate = $input['FromDate'];
        $ToDate = $input['ToDate'];
//        $BrandID = $input['BrandID'];
//        $CategoryID = $input['CategoryID'];
//        $ProductID = $input['ProductID'];
        $OrderState = $input['OrderState'];
        $FinanceState = $input['FinanceState'];
        $UserID = $input['UserID'];

//        $output = $this->order
//            ->with('OrderDetails')
//            ->select(
//                $this->users->getTable() . '.*',
//                $this->product->getTable() . '.*',
//                $this->productPrice->getTable() . '.ProductPriceDesc',
//                $this->brand->getTable() . '.*',
//                $this->category->getTable() . '.*',
//                $this->productcolor->getTable() . '.*',
//                $this->size->getTable() . '.*',
//                $this->seller->getTable() . '.*',
//                $this->sellerProduct->getTable() . '.*',
//                $this->order->getTable() . '.*',
//                $this->orderDetails->getTable() . '.*',
//                DB::raw('Suppliers.Name as SupplierName'),
//                DB::raw('SUM( "' . $this->productPrice->getTable() . '.ProductPriceDesc' . '" )as TotalCost'),
//                DB::raw('Count( "' . $this->orderDetails->getTable() . '.ProductID' . '" )as NumberOfProduct'),
//                DB::raw('Count(*)as NumberOfOperation')

//            )
//            ->join($this->orderDetails->getTable(), $this->order->getTable() . '.OrderID', '=', $this->orderDetails->getTable() . '.OrderID');
//            ->leftjoin($this->users->getTable(), $this->order->getTable() . '.UserID', '=', $this->users->getTable() . '.UserID')
//            ->leftjoin($this->product->getTable(), $this->orderDetails->getTable() . '.ProductID', '=', $this->product->getTable() . '.ProductID')
//            ->leftjoin($this->sellerProduct->getTable(), $this->product->getTable() . '.ProductID', '=', $this->sellerProduct->getTable() . '.ProductID')
//            ->leftjoin($this->seller->getTable(), $this->sellerProduct->getTable() . '.SupplierID', '=', $this->seller->getTable() . '.UserID')
//            ->leftjoin('tblusers  as Suppliers', $this->seller->getTable() . '.UserID', '=', 'Suppliers.UserID');
//            ->leftjoin($this->productPrice->getTable(), $this->orderDetails->getTable() . '.ProductID', '=', $this->productPrice->getTable() . '.ProductID');
//            ->leftjoin($this->size->getTable(), $this->product->getTable() . '.SizeID', '=', $this->size->getTable() . '.SizeID')
//            ->leftjoin($this->brand->getTable(), $this->product->getTable() . '.BrandID', '=', $this->product->getTable() . '.BrandID')
//            ->leftjoin($this->category->getTable(), $this->product->getTable() . '.CategoryID', '=', $this->category->getTable() . '.CategoryID')
//            ->leftjoin($this->productcolor->getTable(), $this->product->getTable() . '.ProductID', '=', $this->productcolor->getTable() . '.ProductID');

//        if ($UserID > 0) {
//            $output = $output->where($this->order->getTable() . '.UserID', '=', $UserID);
//        }
//
//        if (Request()->has('SupplierID')) {
//            $output = $output->where($this->orderDetails->getTable() . '.SupplierID', '=',  $input['SupplierID']);
//        }

//        $output = $output
//            ->where($this->order->getTable() . '.OrderState', '=', $OrderState)
//            ->where($this->order->getTable() . '.FinanceState', '=', $FinanceState)
//            ->where($this->order->getTable() . '.created_at', '>=', $FromDate)
//            ->where($this->order->getTable() . '.created_at', '<=', $ToDate)
////            ->groupby($this->orderDetails->getTable() . '.ProductID')
//            ->groupby($this->order->getTable() . '.OrderID');
//        $output = $output->get();
//        if (Request()->has('PaymentID')) {
//            $output = DB::Select('select count(*) as NumberOfOperation from tblorder where OrderState = ' . $OrderState . '  and tblorder.PaymentID = ' . $input["PaymentID"] . '');
////            $output = DB::Select('select count(*) as NumberOfOperation from tblorder where OrderState = ' . $OrderState . '  and tblorder.PaymentID = ' . $input["PaymentID"] . '');
//        } else {
//            $output = DB::Select('select count(*) as NumberOfOperation from tblorder where OrderState = ' . $OrderState . '');
//        }
//        $output = DB::Select('select count(*) as NumberOfOperation from tblorder where OrderState = ' . $OrderState . '');
        $output = DB::Select('select count(tblorderdetails.OrderDetailsID) as NumberOfOperation from tblorder
left JOIN tblorderdetails on tblorderdetails.OrderID = tblorder.OrderID
where OrderState = ' . $OrderState . '');

        return Response()->json($output);
    }

    public function SallesReport()
    {
        $input = Request()->all();
        $FromDate = $input['FromDate'];
        $ToDate = $input['ToDate'];
        $BrandID = $input['BrandID'];
        $CategoryID = $input['CategoryID'];
        $ProductID = $input['ProductID'];
        $OrderState = $input['OrderState'];
        $UserID = $input['UserID'];

        $output = $this->order
            ->select(
                DB::raw(' Count( "' . $this->orderDetails->getTable() . '.OrderID' . ' ") as NumberOfOperation'),
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
                DB::raw('Suppliers.Name as SupplierName')

//                DB::raw('Count("' . $this->orderDetails->getTable() . '.ProductID' . '" )as NumberOfProduct'),
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
            ->leftjoin($this->brand->getTable(), $this->product->getTable() . '.BrandID', '=', $this->brand->getTable() . '.BrandID')
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

        $output = $output->where($this->order->getTable() . '.OrderState', '=', $OrderState)
            ->where($this->product->getTable() . '.created_at', '>=', $FromDate)
            ->where($this->product->getTable() . '.created_at', '<=', $ToDate)
            ->groupby($this->product->getTable() . '.ProductID')
            ->groupby($this->orderDetails->getTable() . '.OrderDetailsID')
            ->orderBy($this->order->getTable() . '.created_at', 'DESC');
        $output = $output->get();

        return Response()->json($output);


    }


    public function UsersReport()
    {
        $input = Request()->all();
        $FromDate = $input['FromDate'];
        $ToDate = $input['ToDate'];
        $output = $this->users
            ->select(
                $this->users->getTable() . '.*',
                $this->orderDetails->getTable() . '.*',
                $this->productPrice->getTable() . '.*',
                DB::raw('Count("' . $this->order->getTable() . '.OrderID' . '") as Purchases'),
                DB::raw('sum(ItemsQTY) as ItemsQTY'),
                DB::raw('sum(' . $this->productPrice->getTable() . '.ProductPriceDesc' . ')   as TotalAmount')
            )
            ->leftJoin($this->order->getTable(), $this->users->getTable() . '.UserID', '=', $this->order->getTable() . '.UserID')
            ->leftJoin($this->orderDetails->getTable(), $this->order->getTable() . '.OrderID', '=', $this->orderDetails->getTable() . '.OrderID')
            ->leftJoin($this->product->getTable(), $this->orderDetails->getTable() . '.ProductID', '=', $this->product->getTable() . '.ProductID')
            ->leftJoin($this->productPrice->getTable(), $this->product->getTable() . '.ProductID', '=', $this->productPrice->getTable() . '.ProductID')
            ->where($this->users->getTable() . '.created_at', '>=', $FromDate)
            ->where($this->users->getTable() . '.created_at', '<=', $ToDate)
            ->where($this->users->getTable() . '.UseType', '=', 2)
            ->where($this->order->getTable() . '.OrderState', '=', 3)
            ->groupBy($this->users->getTable() . '.UserID')
//            ->groupBy($this->orderDetails->getTable() . '.ProductID')
            ->get();
        return Response()->json($output);
    }

    public function SalesOnDashbord()
    {
        $output = DB::Select('SELECT MONTHNAME(created_at) as Labels ,COUNT(OrderID) as ORDERS from tblorder GROUP BY MONTHNAME(created_at) ORDER BY created_at DESC');
        return Response()->json($output);

    }

    public function SalesByDayOnDashbord()
    {
        $output = DB::Select('select DATE_FORMAT(created_at, \'%e %b %Y\') AS Labels , COUNT(OrderID) as ORDERS from tblorder
GROUP BY DATE_FORMAT(created_at, \'%e %b %Y\')
order by created_at DESC');
        return Response()->json($output);

    }

    public function FilterSalesOnDashbord()
    {

        $input = Request()->all();
        $FromDate = $input['FromDate'];
        $ToDate = $input['ToDate'];
        $output = DB::Select('SELECT MONTHNAME(created_at) as Labels ,COUNT(OrderID) as ORDERS from tblorder 
where tblorder.created_at >="' . $FromDate . '"
and tblorder.created_at <="' . $ToDate . '"
GROUP BY MONTHNAME(created_at) ORDER BY created_at DESC');

        return Response()->json($output);

    }

    public function FilterSalesbyDayOnDashbord()
    {

        $input = Request()->all();
        $FromDate = $input['FromDate'];
        $ToDate = $input['ToDate'];
        $output = DB::Select('select DATE_FORMAT(created_at, \'%e %b %Y\') AS Labels , COUNT(OrderID) as ORDERS from tblorder 
where tblorder.created_at >="' . $FromDate . '"
and tblorder.created_at <="' . $ToDate . '"
GROUP BY DATE_FORMAT(created_at, \'%e %b %Y\')
order by created_at DESC');

        return Response()->json($output);

    }


    public function SalesByBrand()
    {
        $output = $this->order
            ->select(DB::raw('MONTHNAME(' . $this->order->getTable() . '.created_at' . ') as Labels ,COUNT(' . $this->order->getTable() . '.OrderID' . ') as ORDERS'))
            ->leftjoin($this->orderDetails->getTable(), $this->order->getTable() . '.OrderID', '=', $this->orderDetails->getTable() . '.OrderID')
            ->leftjoin($this->product->getTable(), $this->orderDetails->getTable() . '.ProductID', '=', $this->product->getTable() . '.ProductID')
            ->leftjoin($this->brand->getTable(), $this->product->getTable() . '.BrandID', '=', $this->brand->getTable() . '.BrandID')
            ->groupby(DB::raw('MONTHNAME(' . $this->order->getTable() . '.created_at' . ')'))
            ->orderby($this->order->getTable() . '.created_at', 'DESC')
            ->get();
        return Response()->json($output);
    }

    public function FiltterSalesByBrand()
    {

        $input = Request()->all();
        $FromDate = $input['FromDate'];
        $ToDate = $input['ToDate'];
        $BrandID = $input['BrandID'];
        $output = $this->order
            ->select(DB::raw('MONTHNAME(' . $this->order->getTable() . '.created_at' . ') as Labels ,COUNT(' . $this->order->getTable() . '.OrderID' . ') as ORDERS'))
            ->leftjoin($this->orderDetails->getTable(), $this->order->getTable() . '.OrderID', '=', $this->orderDetails->getTable() . '.OrderID')
            ->leftjoin($this->product->getTable(), $this->orderDetails->getTable() . '.ProductID', '=', $this->product->getTable() . '.ProductID')
            ->leftjoin($this->brand->getTable(), $this->product->getTable() . '.BrandID', '=', $this->brand->getTable() . '.BrandID')
            ->where($this->order->getTable() . '.created_at', '>=', $FromDate)
            ->where($this->order->getTable() . '.created_at', '<=', $ToDate);
        if ($BrandID > 0) {
            $output = $output->where($this->product->getTable() . '.BrandID', '=', $BrandID);
        }

        $output = $output->groupby(DB::raw('MONTHNAME(' . $this->order->getTable() . '.created_at' . ')'))
            ->orderby($this->order->getTable() . '.created_at', 'DESC')
            ->get();
        return Response()->json($output);
    }

    public function SalesByCategory()
    {
        $output = $this->order
            ->select(DB::raw('MONTHNAME(' . $this->order->getTable() . '.created_at' . ') as Labels ,COUNT(' . $this->order->getTable() . '.OrderID' . ') as ORDERS'))
            ->leftjoin($this->orderDetails->getTable(), $this->order->getTable() . '.OrderID', '=', $this->orderDetails->getTable() . '.OrderID')
            ->leftjoin($this->product->getTable(), $this->orderDetails->getTable() . '.ProductID', '=', $this->product->getTable() . '.ProductID')
            ->leftjoin($this->category->getTable(), $this->product->getTable() . '.CategoryID', '=', $this->category->getTable() . '.CategoryID')
            ->groupby(DB::raw('MONTHNAME(' . $this->order->getTable() . '.created_at' . ')'))
            ->orderby($this->order->getTable() . '.created_at')
            ->orderBy($this->order->getTable() . '.created_at', 'DESC')
            ->get();
        return Response()->json($output);
    }

    public function FiltterSalesByCategory()
    {

        $input = Request()->all();
        $FromDate = $input['FromDate'];
        $ToDate = $input['ToDate'];
        $CategoryID = $input['CategoryID'];
        $output = $this->order
            ->select(DB::raw('MONTHNAME(' . $this->order->getTable() . '.created_at' . ') as Labels ,COUNT(' . $this->order->getTable() . '.OrderID' . ') as ORDERS'))
            ->leftjoin($this->orderDetails->getTable(), $this->order->getTable() . '.OrderID', '=', $this->orderDetails->getTable() . '.OrderID')
            ->leftjoin($this->product->getTable(), $this->orderDetails->getTable() . '.ProductID', '=', $this->product->getTable() . '.ProductID')
            ->leftjoin($this->category->getTable(), $this->product->getTable() . '.CategoryID', '=', $this->category->getTable() . '.CategoryID')
            ->where($this->order->getTable() . '.created_at', '>=', $FromDate)
            ->where($this->order->getTable() . '.created_at', '<=', $ToDate);
        if ($CategoryID > 0) {
            $output = $output->where($this->product->getTable() . '.CategoryID', '=', $CategoryID);
        }

        $output = $output->groupby(DB::raw('MONTHNAME(' . $this->order->getTable() . '.created_at' . ')'))
            ->orderby($this->order->getTable() . '.created_at')
            ->get();
        return Response()->json($output);
    }

    public function SalesByProduct()
    {
        $output = $this->order
            ->select(DB::raw('MONTHNAME(' . $this->order->getTable() . '.created_at' . ') as Labels ,COUNT(' . $this->order->getTable() . '.OrderID' . ') as ORDERS'))
            ->leftjoin($this->orderDetails->getTable(), $this->order->getTable() . '.OrderID', '=', $this->orderDetails->getTable() . '.OrderID')
            ->leftjoin($this->product->getTable(), $this->orderDetails->getTable() . '.ProductID', '=', $this->product->getTable() . '.ProductID')
            ->groupby(DB::raw('MONTHNAME(' . $this->order->getTable() . '.created_at' . ')'))
            ->orderby($this->order->getTable() . '.created_at', 'DESC')
            ->get();
        return Response()->json($output);
    }

    public function FiltterSalesByProduct()
    {

        $input = Request()->all();
        $FromDate = $input['FromDate'];
        $ToDate = $input['ToDate'];
        $ProductID = $input['ProductID'];
        $output = $this->order
            ->select(DB::raw('MONTHNAME(' . $this->order->getTable() . '.created_at' . ') as Labels ,COUNT(' . $this->order->getTable() . '.OrderID' . ') as ORDERS'))
            ->leftjoin($this->orderDetails->getTable(), $this->order->getTable() . '.OrderID', '=', $this->orderDetails->getTable() . '.OrderID')
            ->leftjoin($this->product->getTable(), $this->orderDetails->getTable() . '.ProductID', '=', $this->product->getTable() . '.ProductID')
            ->leftjoin($this->category->getTable(), $this->product->getTable() . '.CategoryID', '=', $this->category->getTable() . '.CategoryID')
            ->where($this->order->getTable() . '.created_at', '>=', $FromDate)
            ->where($this->order->getTable() . '.created_at', '<=', $ToDate);
        if ($ProductID > 0) {
            $output = $output->where($this->product->getTable() . '.ProductID', '=', $ProductID);
        }

        $output = $output->groupby(DB::raw('MONTHNAME(' . $this->order->getTable() . '.created_at' . ')'))
            ->orderby($this->order->getTable() . '.created_at', 'DESC')
            ->get();
        return Response()->json($output);
    }

    public function OrderCash()
    {
        $input = Request()->all();

        $FromDate = $input['FromDate'];
        $ToDate = $input["ToDate"];
        $SupplierID = $input['SupplierID'];
        if ($SupplierID > 0) {

            $output = DB::SELECT('select tblorder.* ,tblusers.* ,tblorderdetails.*, tblproductprice.ProductPriceDesc,tblproductprice.ProductPriceDesc as ProductPrice 
,count(tblorder.OrderID) as NumberOfOperation, count(tblorderdetails.ProductID) as NumberOfProduct from `tblorder` 
left join `tblorderdetails` on `tblorder`.`OrderID` = `tblorderdetails`.`OrderID`
 left join `tblpayment` on `tblorder`.`PaymentID` = `tblorder`.`PaymentID`
  left join `tblproductprice` on `tblorderdetails`.`ProductID` = `tblproductprice`.`ProductID`
   left join `tblusers` on `tblorderdetails`.`SupplierID` = `tblusers`.`UserID`
    where `tblorder`.`PaymentID` = 5 
    and `tblorder`.`OrderState` = 3
      and tblorder.created_at >= "' . $FromDate . '"
   and tblorder.created_at <= "' . $ToDate . '"
   and tblorderdetails.SupplierID = "' . $SupplierID . '"
     group by `tblorderdetails`.`OrderDetailsID`
      order by `tblorder`.`OrderID` DESC');
            return $output;
        } else {
            $output = DB::SELECT('select tblorder.* ,tblusers.* ,tblorderdetails.*,tblproductprice.ProductPriceDesc,tblproductprice.ProductPriceDesc as ProductPrice
, count(tblorder.OrderID) as NumberOfOperation, count(tblorderdetails.ProductID) as NumberOfProduct from `tblorder` 
left join `tblorderdetails` on `tblorder`.`OrderID` = `tblorderdetails`.`OrderID`
 left join `tblpayment` on `tblorder`.`PaymentID` = `tblorder`.`PaymentID`
  left join `tblproductprice` on `tblorderdetails`.`ProductID` = `tblproductprice`.`ProductID`
   left join `tblusers` on `tblorderdetails`.`SupplierID` = `tblusers`.`UserID`
    where `tblorder`.`PaymentID` = 5 and 
    `tblorder`.`OrderState` = 3
      and tblorder.created_at >="' . $FromDate . '"
   and tblorder.created_at <="' . $ToDate . '"
      group by `tblorderdetails`.`OrderDetailsID`
      order by `tblorder`.`OrderID` DESC');
            return $output;
        }
//            = $this->order
//            ->leftJoin($this->orderDetails->getTable(), $this->order->getTable() . '.OrderID', '=', $this->orderDetails->getTable() . '.OrderID')
//            ->leftJoin($this->payment->getTable(), $this->order->getTable() . '.PaymentID', '=', $this->order->getTable() . '.PaymentID')
//            ->leftJoin($this->productPrice->getTable(), $this->orderDetails->getTable() . '.ProductID', '=', $this->productPrice->getTable() . '.ProductID')
//            ->leftJoin($this->users->getTable(), $this->orderDetails->getTable() . '.SupplierID', '=', $this->users->getTable() . '.UserID')
//            ->where($this->order->getTable() . '.PaymentID', '=', 5)
//            ->where($this->order->getTable() . '.OrderState', '=', 3)
//            ->groupBy($this->order->getTable().'.OrderID')
//            ->orderby($this->order->getTable() . '.OrderID', 'DESC')
//            ->get();

//        return Response()->json($output);

    }

    public function EpaymentOrder()
    {
        $input = Request()->all();
        $ar5sspercent = $this->ar5sspercent->all();
        $FromDate = $input['FromDate'];
        $ToDate = $input["ToDate"];
        $SupplierID = $input['SupplierID'];
        if ($SupplierID > 0) {

            $output = DB::SELECT('select tblorder.* ,tblusers.* ,tblproductprice.ProductPriceDesc,sum(tblproductprice.ProductPriceDesc) as ProductPrice ,
count(tblorder.OrderID) as NumberOfOperation, count(tblorderdetails.ProductID) as NumberOfProduct from `tblorder` 
 join `tblorderdetails` on `tblorder`.`OrderID` = `tblorderdetails`.`OrderID`
  join `tblpayment` on `tblorder`.`PaymentID` = `tblorder`.`PaymentID`
   join `tblproductprice` on `tblorderdetails`.`ProductID` = `tblproductprice`.`ProductID`
    join `tblusers` on `tblorderdetails`.`SupplierID` = `tblusers`.`UserID`
    where `tblorder`.`PaymentID` != 5 
    and `tblorder`.`OrderState` = 3
    and tblorder.created_at >="' . $FromDate . '"
   and tblorder.created_at <="' . $ToDate . '"
      and tblorderdetails.SupplierID = "' . $SupplierID . '"
      group by `tblorderdetails`.`OrderDetailsID`
      order by `tblorder`.`OrderID` DESC');
            return ["output" => $output, "ar5sspercent" => $ar5sspercent];
        } else {

            $output = DB::SELECT('select tblorder.* ,tblusers.* ,tblproductprice.ProductPriceDesc,sum(tblproductprice.ProductPriceDesc) as ProductPrice ,
count(tblorder.OrderID) as NumberOfOperation, count(tblorderdetails.ProductID) as NumberOfProduct from `tblorder` 
 join `tblorderdetails` on `tblorder`.`OrderID` = `tblorderdetails`.`OrderID`
  join `tblpayment` on `tblorder`.`PaymentID` = `tblorder`.`PaymentID`
   join `tblproductprice` on `tblorderdetails`.`ProductID` = `tblproductprice`.`ProductID`
    join `tblusers` on `tblorderdetails`.`SupplierID` = `tblusers`.`UserID`
    where `tblorder`.`PaymentID` != 5 
    and `tblorder`.`OrderState` = 3
    and tblorder.created_at >="' . $FromDate . '"
   and tblorder.created_at <="' . $ToDate . '"

   group by `tblorderdetails`.`OrderDetailsID`
      order by `tblorder`.`OrderID` DESC');
            return ["output" => $output, "ar5sspercent" => $ar5sspercent];
        }

//        return Response()->json($output);

    }

    public
    function updatePresentage()
    {
        $input = Request()->all();


        $this->ar5sspercent->find(2)->update($input);
        return Response()->json($this->ar5sspercent->all());
    }

    public
    function getAr5sspresnt()
    {
        return Response()->json($this->ar5sspercent->all());
    }

    public
    function getorderforSupplers($id)
    {

        $output = DB::SELECT("select tblorder.* ,tblusers.* ,tblorderdetails.*,sum(tblproductprice.ProductPriceDesc) as ProductPrice from `tblorder` 
left join `tblorderdetails` on `tblorder`.`OrderID` = `tblorderdetails`.`OrderID`
 left join `tblpayment` on `tblorder`.`PaymentID` = `tblorder`.`PaymentID`
  left join `tblproductprice` on `tblorderdetails`.`ProductID` = `tblproductprice`.`ProductID`
   left join `tblusers` on `tblorderdetails`.`SupplierID` = `tblusers`.`UserID`
    where `tblorder`.`PaymentID` = 5 
    and `tblorder`.`OrderState` = 3
    and `tblorderdetails`.`SupplierID` = " . $id . "
     group by `tblorder`.`OrderID` order by `tblorder`.`OrderID` DESC");

        $ar5sspercent = $this->ar5sspercent->all();
        return Response()->json(["Suppliers" => $output, "ar5sspercent" => $ar5sspercent]);


    }

    public
    function FilltergetorderforSupplers($id)
    {
        $input = Request()->all();

        $FromDate = $input['FromDate'];
        $ToDate = $input["ToDate"];
//        return $FromDate;
//        $FromDate = Carbon::createFromFormat('Y-m-d', $FromDate)->ToDateString();
//        $ToDate = Carbon::createFromFormat('Y-m-d', $ToDate)->ToDateString();

        if ($input['OrderID'] > 0) {
            $output = DB::SELECT("select tblorder.* ,tblusers.* ,tblorderdetails.*,sum(tblproductprice.ProductPriceDesc) as ProductPrice from `tblorder` 
left join `tblorderdetails` on `tblorder`.`OrderID` = `tblorderdetails`.`OrderID`
 left join `tblpayment` on `tblorder`.`PaymentID` = `tblorder`.`PaymentID`
  left join `tblproductprice` on `tblorderdetails`.`ProductID` = `tblproductprice`.`ProductID`
   left join `tblusers` on `tblorderdetails`.`SupplierID` = `tblusers`.`UserID`
    where `tblorder`.`PaymentID` = 5 
    and `tblorder`.`OrderState` = 3
    and `tblorderdetails`.`SupplierID` = " . $id . "
    and  `tblorder`.`created_at` >= " . $FromDate . "
    and `tblorder`.`created_at` <= " . $ToDate . "
    and `tblorder`.`OrderID` = " . $input["OrderID"] . "
     group by `tblorder`.`OrderID`
      order by `tblorder`.`OrderID` DESC");
        } else {


            $output = $this->order
                ->select(
                    $this->order->getTable() . '.*',
                    $this->orderDetails->getTable() . '.*',
                    $this->users->getTable() . '.*',
                    DB::raw('sum(tblproductprice.ProductPriceDesc) as ProductPrice')
                )
                ->leftJoin($this->orderDetails->getTable(), $this->order->getTable() . '.OrderID', '=', $this->orderDetails->getTable() . '.OrderID')
                ->leftJoin($this->payment->getTable(), $this->order->getTable() . '.PaymentID', '=', $this->order->getTable() . '.PaymentID')
                ->leftJoin($this->productPrice->getTable(), $this->orderDetails->getTable() . '.ProductID', '=', $this->productPrice->getTable() . '.ProductID')
                ->leftJoin($this->users->getTable(), $this->orderDetails->getTable() . '.SupplierID', '=', $this->users->getTable() . '.UserID')
                ->where($this->order->getTable() . '.PaymentID', '=', 5)
                ->where($this->order->getTable() . '.OrderState', '=', 3)
                ->where($this->orderDetails->getTable() . '.SupplierID', '=', $id)
                ->where($this->order->getTable() . '.created_at', '>=', $FromDate)
                ->where($this->order->getTable() . '.created_at', '<=', $ToDate)
                ->groupBy($this->order->getTable() . '.OrderID')
                ->orderby($this->order->getTable() . '.OrderID', 'DESC')
                ->get();


        }


        $ar5sspercent = $this->ar5sspercent->all();
        return Response()->json([
            "Suppliers" => $output,
            "ar5sspercent" => $ar5sspercent
        ]);

//        return Response()->json($output);

    }

    public
    function getorderforSupplersEpayment($id)
    {
        $output = DB::SELECT("select tblorder.* ,tblusers.* ,tblorderdetails.*,sum(tblproductprice.ProductPriceDesc) as ProductPrice from `tblorder` 
left join `tblorderdetails` on `tblorder`.`OrderID` = `tblorderdetails`.`OrderID`
 left join `tblpayment` on `tblorder`.`PaymentID` = `tblorder`.`PaymentID`
  left join `tblproductprice` on `tblorderdetails`.`ProductID` = `tblproductprice`.`ProductID`
   left join `tblusers` on `tblorderdetails`.`SupplierID` = `tblusers`.`UserID`
    where `tblorder`.`PaymentID` != 5 
    and `tblorder`.`OrderState` = 3
    and `tblorderdetails`.`SupplierID` = " . $id . "
     group by `tblorder`.`OrderID` 
     order by `tblorder`.`OrderID` DESC");

        $ar5sspercent = $this->ar5sspercent->all();
        return Response()->json(["Suppliers" => $output, "ar5sspercent" => $ar5sspercent]);


    }

    public
    function FilltergetorderforSupplersEpayment($id)
    {


        $input = Request()->all();

        $FromDate = $input['FromDate'];
        $ToDate = $input["ToDate"];

        if ($input['OrderID'] > 0) {
            $output = DB::SELECT("select tblorder.* ,tblusers.* ,tblorderdetails.*,sum(tblproductprice.ProductPriceDesc) as ProductPrice from `tblorder` 
left join `tblorderdetails` on `tblorder`.`OrderID` = `tblorderdetails`.`OrderID`
 left join `tblpayment` on `tblorder`.`PaymentID` = `tblorder`.`PaymentID`
  left join `tblproductprice` on `tblorderdetails`.`ProductID` = `tblproductprice`.`ProductID`
   left join `tblusers` on `tblorderdetails`.`SupplierID` = `tblusers`.`UserID`
    where `tblorder`.`PaymentID` != 5 
    and `tblorder`.`OrderState` = 3
    and `tblorderdetails`.`SupplierID` = " . $id . "
    and  `tblorder`.`created_at` >= " . $FromDate . "
    and `tblorder`.`created_at` <= " . $ToDate . "
    and `tblorder`.`OrderID` = " . $input["OrderID"] . "
     group by `tblorder`.`OrderID`
      order by `tblorder`.`OrderID` DESC");
        } else {


            $output = $this->order
                ->select(
                    $this->order->getTable() . '.*',
                    $this->orderDetails->getTable() . '.*',
                    $this->users->getTable() . '.*',
                    DB::raw('sum(tblproductprice.ProductPriceDesc) as ProductPrice')
                )
                ->leftJoin($this->orderDetails->getTable(), $this->order->getTable() . '.OrderID', '=', $this->orderDetails->getTable() . '.OrderID')
                ->leftJoin($this->payment->getTable(), $this->order->getTable() . '.PaymentID', '=', $this->order->getTable() . '.PaymentID')
                ->leftJoin($this->productPrice->getTable(), $this->orderDetails->getTable() . '.ProductID', '=', $this->productPrice->getTable() . '.ProductID')
                ->leftJoin($this->users->getTable(), $this->orderDetails->getTable() . '.SupplierID', '=', $this->users->getTable() . '.UserID')
                ->where($this->order->getTable() . '.PaymentID', '!=', 5)
                ->where($this->order->getTable() . '.OrderState', '=', 3)
                ->where($this->orderDetails->getTable() . '.SupplierID', '=', $id)
                ->where($this->order->getTable() . '.created_at', '>=', $FromDate)
                ->where($this->order->getTable() . '.created_at', '<=', $ToDate)
                ->groupBy($this->order->getTable() . '.OrderID')
                ->orderby($this->order->getTable() . '.OrderID', 'DESC')
                ->get();


        }


        $ar5sspercent = $this->ar5sspercent->all();
        return Response()->json([
            "Suppliers" => $output,
            "ar5sspercent" => $ar5sspercent
        ]);

    }


    public function Budgeting()
    {


        $input = Request()->all();
        $FromDate = $input['FromDate'];
        $ToDate = $input['ToDate'];
        $SupplierID = $input['SupplierID'];
        if ($SupplierID > 0) {
            $output = DB::Select('SELECT YEAR(tblorder.created_at) as Year, tblusers.*,tblorder.*, tblproductprice.ProductPriceDesc, MONTHNAME(tblorder.created_at) as Monthes  
,sum(CASE When tblorder.PaymentID = 5  THEN  (tblproductprice.ProductPriceDesc * tblorderdetails.ItemsQTY ) ELSE 0 END) as CashPayment
 ,sum(CASE When tblorder.PaymentID != 5 THEN (tblproductprice.ProductPriceDesc * tblorderdetails.ItemsQTY) ELSE 0 END ) as CrditPayment 
 ,count(tblorder.OrderID) as NumberOfOperation, count(tblorderdetails.ProductID) as NumberOfProduct from tblorder 
left join `tblorderdetails` on `tblorder`.`OrderID` = `tblorderdetails`.`OrderID`
left join `tblproductprice` on `tblorderdetails`.`ProductID` = `tblproductprice`.`ProductID`
left join `tblusers` on `tblorderdetails`.`SupplierID` = `tblusers`.`UserID`
where tblorder.created_at >="' . $FromDate . '"
and tblorder.created_at <="' . $ToDate . '"
and tblorderdetails.SupplierID = "' . $SupplierID . '"
and tblorder.OrderState = 3
and tblorder.FinanceState != 2
GROUP BY MONTHNAME(tblorder.created_at),tblorderdetails.SupplierID 
ORDER BY tblorder.created_at ASC');
        } else {
            $output = DB::Select('SELECT YEAR(tblorder.created_at) as Year,tblusers.* ,  tblorder.*, tblproductprice.ProductPriceDesc, MONTHNAME(tblorder.created_at) as Monthes  
,sum(CASE When tblorder.PaymentID = 5  THEN  (tblproductprice.ProductPriceDesc * tblorderdetails.ItemsQTY ) ELSE 0 END) as CashPayment 
,sum(CASE When tblorder.PaymentID != 5 then (tblproductprice.ProductPriceDesc * tblorderdetails.ItemsQTY ) ELSE 0 END ) as  CrditPayment  
,count(tblorder.OrderID) as NumberOfOperation, count(tblorderdetails.ProductID) as NumberOfProduct from tblorder 
left join `tblorderdetails` on `tblorder`.`OrderID` = `tblorderdetails`.`OrderID`
left join `tblproductprice` on `tblorderdetails`.`ProductID` = `tblproductprice`.`ProductID`
left join `tblusers` on `tblorderdetails`.`SupplierID` = `tblusers`.`UserID`
where tblorder.created_at >="' . $FromDate . '"
and tblorder.created_at <="' . $ToDate . '"
and tblorder.OrderState = 3
and tblorder.FinanceState != 2
GROUP BY MONTHNAME(tblorder.created_at),tblorderdetails.SupplierID 
ORDER BY tblorder.created_at ASC');

        }

        $NumberOfProduct = DB::Select('Select Count(*) as NumberOfProduct from tblorderdetails 
join tblorder on tblorderdetails.OrderID = tblorder.OrderID 
where tblorder.OrderState = 3
and tblorder.FinanceState != 2 ');


        return Response()->json([
            "output" => $output,
            "NumberOfProduct" => $NumberOfProduct
        ]);

    }

    public function Clims()
    {


        $input = Request()->all();
        $FromDate = $input['FromDate'];
        $ToDate = $input['ToDate'];
        $SupplierID = $input['SupplierID'];
        if ($SupplierID > 0) {
            $output = DB::Select('SELECT YEAR(tblorder.created_at) as Year, tblusers.*,tblorder.*, tblproductprice.ProductPriceDesc, MONTHNAME(tblorder.created_at) as Monthes  
,sum(CASE When tblorder.PaymentID = 5  THEN  (tblproductprice.ProductPriceDesc * tblorderdetails.ItemsQTY ) ELSE 0 END) as CashPayment 
,sum(CASE When tblorder.PaymentID != 5 THEN (tblproductprice.ProductPriceDesc * tblorderdetails.ItemsQTY ) ELSE 0 END ) as CrditPayment 
 ,count(tblorder.OrderID) as NumberOfOperation, count(tblorderdetails.ProductID) as NumberOfProduct from tblorder 
left join `tblorderdetails` on `tblorder`.`OrderID` = `tblorderdetails`.`OrderID`
left join `tblproductprice` on `tblorderdetails`.`ProductID` = `tblproductprice`.`ProductID`
left join `tblusers` on `tblorderdetails`.`SupplierID` = `tblusers`.`UserID`
where tblorder.created_at >="' . $FromDate . '"
and tblorder.created_at <="' . $ToDate . '"
and tblorderdetails.SupplierID = "' . $SupplierID . '"
and tblorder.OrderState = 3
and tblorder.FinanceState = 0
GROUP BY MONTHNAME(tblorder.created_at),tblorderdetails.SupplierID 
ORDER BY tblorder.created_at DESC');
        } else {
            $output = DB::Select('SELECT YEAR(tblorder.created_at) as Year,tblusers.* ,  tblorder.*, tblproductprice.ProductPriceDesc, MONTHNAME(tblorder.created_at) as Monthes 
 ,sum(CASE When tblorder.PaymentID = 5  THEN  (tblproductprice.ProductPriceDesc * tblorderdetails.ItemsQTY ) ELSE 0 END) as CashPayment
  ,sum(CASE When tblorder.PaymentID != 5 THEN (tblproductprice.ProductPriceDesc * tblorderdetails.ItemsQTY ) ELSE 0 END ) as  CrditPayment  
,count(tblorder.OrderID) as NumberOfOperation, count(tblorderdetails.ProductID) as NumberOfProduct from tblorder 
left join `tblorderdetails` on `tblorder`.`OrderID` = `tblorderdetails`.`OrderID`
left join `tblproductprice` on `tblorderdetails`.`ProductID` = `tblproductprice`.`ProductID`
left join `tblusers` on `tblorderdetails`.`SupplierID` = `tblusers`.`UserID`
where tblorder.created_at >="' . $FromDate . '"
and tblorder.created_at <="' . $ToDate . '"
and tblorder.OrderState = 3
and tblorder.FinanceState = 0
GROUP BY MONTHNAME(tblorder.created_at),tblorderdetails.SupplierID 
ORDER BY tblorder.created_at DESC');

        }

        $NumberOfProduct = DB::Select('Select Count(tblorderdetails.ProductID) as NumberOfProduct from tblorderdetails 
join tblorder on tblorderdetails.OrderID = tblorder.OrderID 
where tblorder.OrderState = 3
and tblorder.FinanceState != 1
and tblorder.FinanceState != 2
 ');

        $NumberOfOperation = DB::Select('Select Count(tblorder.OrderID) as NumberOfOperation from tblorderdetails 
join tblorder on tblorderdetails.OrderID = tblorder.OrderID 
where tblorder.OrderState = 3
and tblorder.FinanceState != 1
and tblorder.FinanceState != 2
 ');

        $NumberOfOperation = count($NumberOfOperation);

        return Response()->json([
            "output" => $output,
            "NumberOfProduct" => $NumberOfProduct,
            "NumberOfOperation" => $NumberOfOperation
        ]);


    }


    public function ExcutedOrders()
    {


        $input = Request()->all();
        $FromDate = $input['FromDate'];
        $ToDate = $input['ToDate'];
        $SupplierID = $input['SupplierID'];
        if ($SupplierID > 0) {
            $output = DB::Select('SELECT tblusers.*,tblorder.*, tblproductprice.ProductPriceDesc, MONTHNAME(tblorder.created_at) as Monthes 
,sum(CASE When tblorder.PaymentID = 5  THEN  (tblproductprice.ProductPriceDesc * tblorderdetails.ItemsQTY ) ELSE 0 END) as CashPayment
  ,sum(CASE When tblorder.PaymentID != 5 THEN (tblproductprice.ProductPriceDesc * tblorderdetails.ItemsQTY ) ELSE 0 END ) as  CrditPayment  
 ,count(tblorder.OrderID) as NumberOfOperation, count(tblorderdetails.ProductID) as NumberOfProduct from tblorder 
left join `tblorderdetails` on `tblorder`.`OrderID` = `tblorderdetails`.`OrderID`
left join `tblproductprice` on `tblorderdetails`.`ProductID` = `tblproductprice`.`ProductID`
left join `tblusers` on `tblorderdetails`.`SupplierID` = `tblusers`.`UserID`
where tblorder.created_at >="' . $FromDate . '"
and tblorder.created_at <="' . $ToDate . '"
and tblorderdetails.SupplierID = "' . $SupplierID . '"
and tblorder.OrderState = 3
and tblorder.FinanceState = 2
GROUP BY MONTHNAME(tblorder.created_at),tblorderdetails.SupplierID 
ORDER BY tblorder.created_at DESC');
        } else {
            $output = DB::Select('SELECT tblusers.* ,  tblorder.*, tblproductprice.ProductPriceDesc, MONTHNAME(tblorder.created_at) as Monthes 
,sum(CASE When tblorder.PaymentID = 5  THEN  (tblproductprice.ProductPriceDesc * tblorderdetails.ItemsQTY ) ELSE 0 END) as CashPayment
  ,sum(CASE When tblorder.PaymentID != 5 THEN (tblproductprice.ProductPriceDesc * tblorderdetails.ItemsQTY ) ELSE 0 END ) as  CrditPayment   
,count(tblorder.OrderID) as NumberOfOperation, count(tblorderdetails.ProductID) as NumberOfProduct from tblorder 
left join `tblorderdetails` on `tblorder`.`OrderID` = `tblorderdetails`.`OrderID`
left join `tblproductprice` on `tblorderdetails`.`ProductID` = `tblproductprice`.`ProductID`
left join `tblusers` on `tblorderdetails`.`SupplierID` = `tblusers`.`UserID`
where tblorder.created_at >="' . $FromDate . '"
and tblorder.created_at <="' . $ToDate . '"
and tblorder.OrderState = 3
and tblorder.FinanceState = 2
GROUP BY MONTHNAME(tblorder.created_at),tblorderdetails.SupplierID 
ORDER BY tblorder.created_at DESC');

        }


        $NumberOfProduct = DB::Select('Select Count(*) as NumberOfProduct from tblorderdetails 
join tblorder on tblorderdetails.OrderID = tblorder.OrderID 
where tblorder.OrderState = 3
and tblorder.FinanceState = 2 ');

        $NumberOfOperation = DB::Select('select count(*) as NumberOfOperation from tblorder where OrderState = 3 and tblorder.FinanceState = 2');

        return Response()->json([
            "output" => $output,
            "NumberOfProduct" => $NumberOfProduct,
            "NumberOfOperation" => $NumberOfOperation
        ]);


    }

    public function GetActualAccount()
    {


        $input = Request()->all();
        $FromDate = $input['FromDate'];
        $ToDate = $input['ToDate'];

        $output = DB::Select('SELECT tblusers.* ,  tblorder.*, tblproductprice.ProductPriceDesc, MONTHNAME(tblorder.created_at) as Monthes  
,sum(CASE When tblorder.PaymentID = 5  THEN  (tblproductprice.ProductPriceDesc * tblorderdetails.ItemsQTY ) ELSE 0 END) as CashPayment
  ,sum(CASE When tblorder.PaymentID != 5 THEN (tblproductprice.ProductPriceDesc * tblorderdetails.ItemsQTY ) ELSE 0 END ) as  CrditPayment   
,count(tblorder.OrderID) as NumberOfOperation, count(tblorderdetails.ProductID) as NumberOfProduct from tblorder 
left join `tblorderdetails` on `tblorder`.`OrderID` = `tblorderdetails`.`OrderID`
left join `tblproductprice` on `tblorderdetails`.`ProductID` = `tblproductprice`.`ProductID`
left join `tblusers` on `tblorderdetails`.`SupplierID` = `tblusers`.`UserID`
where tblorder.created_at >="' . $FromDate . '"
and tblorder.created_at <="' . $ToDate . '"
and tblorder.OrderState = 3
and tblorder.FinanceState = 1
GROUP BY MONTHNAME(tblorder.created_at),tblorderdetails.SupplierID 
ORDER BY tblorder.created_at DESC');


        $NumberOfProduct = DB::Select('Select Count(*) as NumberOfProduct from tblorderdetails 
join tblorder on tblorderdetails.OrderID = tblorder.OrderID 
where tblorder.OrderState = 3
and tblorder.FinanceState = 1 ');


        $NumberOfOperation = DB::Select('select count(*) as NumberOfOperation from tblorder where OrderState = 3 and tblorder.FinanceState = 1');

        return Response()->json([
            "output" => $output,
            "NumberOfProduct" => $NumberOfProduct,
            "NumberOfOperation" => $NumberOfOperation
        ]);

    }

    public function ChangeFincialOrderState($id, $state)
    {
        $this->order->find($id)->update([
            "FinanceState" => $state
        ]);
    }

    public function BudgtingSuppleris()

    {
        $input = Request()->all();
        $FromDate = $input['FromDate'];
        $ToDate = $input['ToDate'];
        $id = $input['SupplierID'];

        $output = DB::Select('SELECT tblusers.*,tblorder.*, tblproductprice.ProductPriceDesc, MONTHNAME(tblorder.created_at) as Monthes  
,sum(CASE When tblorder.PaymentID = 5  THEN  (tblproductprice.ProductPriceDesc * tblorderdetails.ItemsQTY)   ELSE 0 END) as CashPayment 
,sum(CASE When tblorder.PaymentID != 5 THEN (tblproductprice.ProductPriceDesc *tblorderdetails.ItemsQTY ) ELSE 0 END ) as CrditPayment from tblorder 
 join `tblorderdetails` on `tblorder`.`OrderID` = `tblorderdetails`.`OrderID`
 join `tblproductprice` on `tblorderdetails`.`ProductID` = `tblproductprice`.`ProductID`
 join `tblusers` on `tblorderdetails`.`SupplierID` = `tblusers`.`UserID`
where tblorder.created_at >="' . $FromDate . '" 
and tblorder.created_at <="' . $ToDate . '"
and tblorderdetails.SupplierID = "' . $id . '"
and tblorder.OrderState = 3
and tblorder.FinanceState !=2
GROUP BY MONTHNAME(tblorder.created_at)     
ORDER BY tblorder.created_at DESC');

        $NumberOFProduct = DB::select('select Count(tblorderdetails.ProductID) as NumberOfProduct from tblorderdetails
join tblorder on tblorderdetails.OrderID = tblorder.OrderID
and tblorderdetails.SupplierID = "' . $id . '" 
and tblorder.OrderState = 3
and tblorder.FinanceState !=2');

        return Response()->json([
            "output" => $output,
            "NumberOFProduct" => $NumberOFProduct
        ]);
    }

    public function GetBudGetingSupplier()
    {
        $input = Request()->all();
        $FromDate = $input['FromDate'];
        $ToDate = $input['ToDate'];
        $id = $input['SupplierID'];


        $output = DB::Select('SELECT tblproduct.*, tblusers.*,tblorder.*,tblorderdetails.*, tblproductprice.ProductPriceDesc,
 MONTHNAME(tblorder.created_at) as Monthes 
 ,sum(CASE When tblorder.PaymentID = 5  THEN  tblproductprice.ProductPriceDesc ELSE 0 END) as CashPayment  ,sum(CASE When tblorder.PaymentID != 5 then tblproductprice.ProductPriceDesc ELSE 0 END ) as CrditPayment 
 ,count(tblorder.OrderID) as NumberOfOperation, count(tblorderdetails.ProductID) as NumberOfProduct from tblorder 
left join `tblorderdetails` on `tblorder`.`OrderID` = `tblorderdetails`.`OrderID`
left join `tblpayment` on `tblorder`.`PaymentID` = `tblorder`.`PaymentID`
left join `tblproduct` on `tblorderdetails`.`ProductID` = `tblproduct`.`ProductID`
left join `tblproductprice` on `tblorderdetails`.`ProductID` = `tblproductprice`.`ProductID`
left join `tblusers` on `tblorderdetails`.`SupplierID` = `tblusers`.`UserID`
where tblorder.created_at >="' . $FromDate . '" 
and tblorder.created_at <="' . $ToDate . '"
and tblorderdetails.SupplierID = "' . $id . '"
and tblorder.OrderState = 3
and tblorder.FinanceState != 0 
GROUP BY tblorderdetails.OrderDetailsID 
ORDER BY tblorder.created_at DESC');
        return $output;


    }

    public function abstractSupplier()
    {

//        $output = $this->orderDetails->select(
//            DB::raw('Count("OrderID") as NumberOfOperation'),
//            DB::raw('count(tblorderdetails.ProductID) as NumberOfProduct'),
//            DB::raw('SUM(tblproductprice.ProductPriceDESC ) * ItemsQTY as Total ')
//        )
//            ->leftJoin($this->order->getTable(), $this->orderDetails->getTable() . '.OrderID', '=', $this->order->getTable() . '.OrderID')
////            ->leftJoin($this->product->getTable(), $this->orderDetails->getTable() . '.ProductID', '=', $this->product->getTable() . '.ProductID')
//            ->leftJoin($this->productPrice->getTable(), $this->orderDetails->getTable() . '.ProductID', '=', $this->productPrice->getTable() . '.ProductID')
//            ->where($this->orderDetails->getTable() . '.created_at', '>=', $FromDate)
//            ->where($this->orderDetails->getTable() . '.created_at', '<=', $ToDate)
//            ->where($this->orderDetails->getTable() . '.SupplierID', '=', $id)
//            ->where($this->order->getTable() . '.OrderState', '=', 3)
//            ->where($this->order->getTable() . '.FinanceState', '!=', 0)
//            ->get();


        $input = Request()->all();
        $FromDate = $input['FromDate'];
        $ToDate = $input['ToDate'];
        $id = $input['SupplierID'];
        $OrderState = $input['OrderState'];
        $output = DB::select('select  count(tblorderdetails.OrderID) as NumberOfOperation from tblorderdetails 
        join tblorder on tblorderdetails.OrderID = tblorder.OrderID
        where tblorder.created_at >= "' . $FromDate . '"
        and  tblorder.created_at <= "' . $ToDate . '"
        and  tblorder.OrderState = "' . $OrderState . '"
        and  tblorder.FinanceState != "0"
        and  tblorderdetails.SupplierID = "' . $id . '"
        group by tblorderdetails.OrderID
  ');
        $output = Count($output);
        return $output;
    }

    public function abstractSupplier2()
    {
        $input = Request()->all();
        $FromDate = $input['FromDate'];
        $ToDate = $input['ToDate'];
        $id = $input['SupplierID'];
        $OrderState = $input['OrderState'];
        $output = DB::select('select  count(tblorderdetails.OrderID) as NumberOfOperation from tblorderdetails 
        join tblorder on tblorderdetails.OrderID = tblorder.OrderID
        where tblorder.created_at >= "' . $FromDate . '"
        and  tblorder.created_at <= "' . $ToDate . '"
        and  tblorder.OrderState = "' . $OrderState . '"
        and  tblorderdetails.SupplierID = "' . $id . '"
        group by tblorderdetails.OrderID
  ');
        $output = Count($output);

//            $this->order->select(
//            DB::raw('Count(*) as NumberOfOperation')
//        )
//            ->Join($this->orderDetails->getTable(), $this->order->getTable() . '.OrderID', '=', $this->orderDetails->getTable() . '.OrderID')
//            ->where($this->order->getTable() . '.created_at', '>=', $FromDate)
//            ->where($this->order->getTable() . '.created_at', '<=', $ToDate)
//            ->where($this->orderDetails->getTable() . '.SupplierID', '=', $id)
//            ->where($this->order->getTable() . '.OrderState', '=', $OrderState)
//            ->groupBy($this->order->getTable().'.OrderID')
//            ->orderBy($this->order->getTable().'.OrderID')
//            ->get();


        return $output;
    }

    public function abstractSupplier3()
    {
        $input = Request()->all();
        $FromDate = $input['FromDate'];
        $ToDate = $input['ToDate'];
        $id = $input['SupplierID'];
        $OrderState = $input['OrderState'];
//        $output = $this->orderDetails->select(
//            DB::raw('Count("OrderID") as NumberOfOperation'),
//            DB::raw('count(tblorderdetails.ProductID) as NumberOfProduct'),
//            DB::raw('SUM(tblproductprice.ProductPriceDESC ) * ItemsQTY as Total ')
//        )
//            ->Join($this->order->getTable(), $this->orderDetails->getTable() . '.OrderID', '=', $this->order->getTable() . '.OrderID')
////            ->leftJoin($this->product->getTable(), $this->orderDetails->getTable() . '.ProductID', '=', $this->product->getTable() . '.ProductID')
//            ->Join($this->productPrice->getTable(), $this->orderDetails->getTable() . '.ProductID', '=', $this->productPrice->getTable() . '.ProductID')
//            ->where($this->order->getTable().'.created_at' ,'<'  ,'DATE_SUB(CURDATE(), INTERVAL 7 DAY')
//            ->where($this->orderDetails->getTable() . '.created_at', '>=', $FromDate)
//            ->where($this->orderDetails->getTable() . '.created_at', '<=', $ToDate)
//            ->where($this->orderDetails->getTable() . '.SupplierID', '=', $id)
//            ->where($this->order->getTable() . '.OrderState', '=',$OrderState)
//            ->get();


        $output = DB::SELECT("select   DISTINCT count(OrderDetailsID) as NumberOfOperation from `tblorder`
left join tblorderdetails on  tblorderdetails.OrderID = tblorder.OrderID
where tblorder.created_at < DATE_SUB(CURDATE(), INTERVAL 7 DAY)
 and tblorderdetails.SupplierID = " . $id . "
 and OrderState = 2
 -- group by tblorderdetails.OrderID");
//        $output = Count($output);
        return $output;
    }

}
