<?php

namespace App\Http\Controllers;

use App\Http\Models\Brand;
use App\Http\Models\Cart;
use App\Http\Models\Category;
use App\Http\Models\Color;
use App\Http\Models\Order;
use App\Http\Models\OrderDetails;
use App\Http\Models\Payment;
use App\Http\Models\Product;
use App\Http\Models\ProductColor;
use App\Http\Models\ProductImage;
use App\Http\Models\ProductPrice;
use App\Http\Models\Seller;
use App\Http\Models\SellerProduct;
use App\Http\Models\ShpingPrice;
use App\Http\Models\Size;
use App\Http\Models\Users;
use DB;
use Mail;
use Illuminate\Support\Facades\App;
use Vinkla\Pusher\Facades\Pusher;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->order = new Order();
        $this->orderdetails = new OrderDetails();
        $this->cart = new Cart();
        $this->product = new Product();
        $this->productprice = new ProductPrice();
        $this->seller = new Seller();
        $this->sellerproduct = new SellerProduct();
        $this->shpingprice = new ShpingPrice();
        $this->productcolor = new ProductColor();
        $this->color = new Color();
        $this->productimage = new ProductImage();
        $this->brand = new Brand();
        $this->category = new Category();
        $this->size = new Size();
        $this->payment = new Payment();
        $this->users = new Users();

    }

    public function createNewOrder()
    {
        $input = Request()->all();
        global $orderdetails;
        $output = $this->order->create($input);
        $OrderID = $output['OrderID'];
        $UserID = $input['UserID'];

        $output = $this->cart
            ->leftjoin($this->product->getTable(), $this->cart->getTable() . '.ProductID', '=', $this->product->getTable() . '.ProductID')
            ->leftjoin($this->productprice->getTable(), $this->product->getTable() . '.ProductID', '=', $this->productprice->getTable() . '.ProductID')
            ->leftjoin($this->sellerproduct->getTable(), $this->product->getTable() . '.ProductID', '=', $this->sellerproduct->getTable() . '.ProductID')
            ->where($this->cart->getTable() . '.UserID', '=', $UserID)
            ->where($this->cart->getTable() . '.CartState', '=', 1)
            ->groupby($this->cart->getTable() . '.CartID')
            ->get();

        $SupplierID = $output[0]['SupplierID'];
        for ($i = 0; $i < Count($output); $i++) {
            $orderdetails = $output[$i];
            $input['OrderDetails'][$i]['OrderID'] = $OrderID;
            $sellerID = $output[$i]['SellerID'];
            $this->orderdetails->create([
                'OrderID' => $OrderID,
                "PriceID" => $output[$i]['ProductPriceID'],
                "SellaerID" => $output[$i]['SellerID'],
                "StoreID" => $output[$i]['StoreID'],
                "SupplierID" => $output[$i]['SupplierID'],
                "ProductID" => $output[$i]['ProductID'],
                "Shiping" => $output[$i]['Shiping'],
                "ItemsQTY" => $output[$i]['QTY']
            ]);
            $this->cart->where($this->cart->getTable() . '.UserID', '=', $UserID)->update([
                'CartState' => 2
            ]);


        }

//        $chanle = $this->users->select($this->users->getTable() . '.chanle')
//            ->leftjoin($this->seller->getTable(), $this->users->getTable() . '.UserID', '=', $this->seller->getTable() . '.UserID')
//            ->where($this->seller->getTable().'.SellerID','=',$sellerID)
//            ->get();


//
//        foreach ($chanle as $chanle) {
//            $chanle = $chanle->chanle;
//        }

//        $pusher = App::make('pusher');
//        $pusher = new Pusher();
//        $pusher->trigger($chanle,
//            'send-event',
//            array('text' => 'you Have anew Order',)
//        );


//        send message

        $getClintMail = $this->users->where($this->users->getTable() . '.UserID', '=', $UserID)->get();
        $getSupplierMail = $this->users->where($this->users->getTable() . '.UserID', '=', $SupplierID)->get();
        $ClintMail = $getClintMail['0']['Email'];
        $SupplierMail = $getSupplierMail['0']['Email'];


//       $rep =  $this->sendmail($ClintMail,$SupplierMail,'لديك طلب جديد',$output);


        $requestJson = $input;
        $postlength = array(
            "ClintMail" => $ClintMail,
            "SupplierMail" => $SupplierMail,
            "message" => 'لديك طلب جديد',
            "orderdetails" => $output
        );


        $url = "http://zadalsharq.com/ar5ss/public/api/testmail";

        $ch = curl_init($url);
        # Setup request to send json via POST.
        $payload = json_encode($postlength);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        # Return response instead of printing.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        # Send request.
        $result = curl_exec($ch);

        curl_close($ch);

//        return Response()->json($result);

        $output = ['state' => '202'];
        return Response()->json($output);
    }


//    public function sendmail($ClintMail, $SupplierMail, $message, $orders)
    public function sendmail()
    {
        global $body;
        global $ordersDetails;

        $from = "info@ar5ss.com";


        $request = Request()->all();

        $from = "info@ar5ss.com";

        $ClintMail = $request['ClintMail'];
        $SupplierMail = $request['SupplierMail'];
        $message = $request['message'];
        $orders = $request['orderdetails'];

//        $ClintMail = $ClintMail;
//        $SupplierMail = $SupplierMail;
//        $message = $message;
//        $orders =$orders;

        for ($i = 0; $i < count($orders); $i++) {
            $ordersDetails .= 'ItemName' . $orders[$i]['product_name'] . ' | ItemPrice ' . ': ' . $orders[$i]['ProductPriceDesc'] . ' | ItemCont' . ': ' . $orders[$i]['QTY'];
            $body = $ordersDetails;
        }

        $ehead = "From: " . $from . "\r\n";
        $to = $ClintMail;
        $to_2 = $ClintMail;
        $subject = $message;
        $to = $ClintMail;
        $to_2 = $SupplierMail;
        $from = $from;

        $headers = "From: " . ($from) . "" . "\r\n";
        $headers .= "Reply-To:" . ($from) . "\r\n";
        $headers .= "Return-Path: The Sender <" . ($from) . ">\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        $headers .= 'X-Mailer: PHP/' . phpversion();
        $headers .= "X-Priority: 3\r\n";

        $result = mail($to, $subject, $body, $headers);
        $result2 = mail($to_2, $subject, $body, $headers);
        echo $result;

//       return $output = ["msg" => "send a new password please check your email"];


    }


    public function OrderHistory($id)
    {
        $input = Request()->all();
        $OrderState = $input['OrderState'];
        $output = $this->order->with(['OrderDetails' => (function ($query) {
            $query
                ->select(
                    $this->product->getTable() . '.*',
                    $this->productcolor->getTable() . '.*',
                    $this->productprice->getTable() . '.*',
                    $this->productprice->getTable() . '.ProductPriceDesc as ProductPrice',
                    $this->brand->getTable() . '.*',
                    $this->category->getTable() . '.*',
                    $this->size->getTable() . '.*',
                    $this->productimage->getTable() . '.*',
                    $this->orderdetails->getTable() . '.*'
                )
                ->leftjoin($this->product->getTable(), $this->orderdetails->getTable() . '.ProductID', '=', $this->product->getTable() . '.ProductID')
                ->leftjoin($this->productprice->getTable(), $this->product->getTable() . '.ProductID', '=', $this->productprice->getTable() . '.ProductID')
                ->leftjoin($this->brand->getTable(), $this->product->getTable() . '.BrandID', '=', $this->brand->getTable() . '.BrandID')
                ->leftjoin($this->category->getTable(), $this->product->getTable() . '.CategoryID', '=', $this->category->getTable() . '.CategoryID')
                ->leftjoin($this->size->getTable(), $this->product->getTable() . '.SizeID', '=', $this->size->getTable() . '.SizeID')
                ->leftjoin($this->productcolor->getTable(), $this->product->getTable() . '.ProductID', '=', $this->productcolor->getTable() . '.ProductID')
                ->leftjoin($this->color->getTable(), $this->productcolor->getTable() . '.ColorID', '=', $this->color->getTable() . '.ColorID')
                ->leftjoin($this->productimage->getTable(), $this->productcolor->getTable() . '.ProductColorID', '=', $this->productimage->getTable() . '.ProductColorID')
                ->groupby($this->orderdetails->getTable() . '.OrderDetailsID');
        })])
            ->select(
                $this->order->getTable() . '.*',
                $this->payment->getTable() . '.*',
                DB::raw("sum(" . $this->productprice->getTable() . ".ProductPriceDesc" . " * tblorderdetails.ItemsQTY ".")as TotalPrice"),
                DB::raw("sum(tblorderdetails.ItemsQTY)as Items"),
                DB::raw("sum(tblorderdetails.Shiping)as TotalShiping"),
                DB::raw("DATE_FORMAT(" . $this->order->getTable() . ".created_at" . ", '%D %M' )AS 'OrderDate'"),
                $this->product->getTable() . '.*',
//                    $this->productprice->getTable().'.*',
                $this->productprice->getTable() . '.ProductPriceDesc as ProductPrice',
                $this->orderdetails->getTable() . '.*'
            )
            ->leftjoin($this->payment->getTable(), $this->order->getTable() . '.PaymentID', '=', $this->payment->getTable() . '.PaymentID')
            ->leftjoin($this->orderdetails->getTable(), $this->order->getTable() . '.OrderID', '=', $this->orderdetails->getTable() . '.OrderId')
            ->leftjoin($this->product->getTable(), $this->orderdetails->getTable() . '.ProductID', '=', $this->product->getTable() . '.ProductID')
            ->leftjoin($this->productprice->getTable(), $this->product->getTable() . '.ProductID', '=', $this->productprice->getTable() . '.ProductID')
            ->where($this->order->getTable() . '.UserID', '=', $id)
            ->where($this->order->getTable() . '.OrderState', '=', $OrderState)
            ->groupby($this->order->getTable() . '.OrderID')
            ->orderby($this->order->getTable() . '.OrderID', 'DESC')
            ->get();

        return Response()->json($output);
    }


}
