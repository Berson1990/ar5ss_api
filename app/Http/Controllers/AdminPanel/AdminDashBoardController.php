<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Auth\ResetPasswordController;
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
Use DB;
use GuzzleHttp\Psr7\Request;

class AdminDashBoardController extends Controller
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

    }

    public function LastRecored()
    {
        $ClintNumber = $this->users->select(DB::raw('Count("' . $this->users->getTable() . '.UserID' . '")as ClintNumber'))
            ->where($this->users->getTable() . '.UseType', '=', 2)->get();

        $AtiveUsers = $this->users->select(DB::raw('Count("' . $this->users->getTable() . '.UserID' . '")as IsActiveUsers'))
            ->where($this->users->getTable() . '.IsActive', '=', 1)
            ->where($this->users->getTable() . '.UseType', '=', 2)
            ->get();

        $InAtiveUsers = $this->users->select(DB::raw('Count("' . $this->users->getTable() . '.UserID' . '")as InActiveUsers'))
            ->where($this->users->getTable() . '.IsActive', '=', 0)
            ->where($this->users->getTable() . '.UseType', '=', 2)
            ->get();
        $employee = $this->users->select(DB::raw('Count("' . $this->users->getTable() . '.UserID' . '")as Employee'))
            ->where($this->users->getTable() . '.UseType', '=', 1)
            ->get();

        $PendingPrdouct = $this->product->select(DB::raw('Count("' . $this->product->getTable() . '.ProductID' . '")as PendingProduct'))
            ->where($this->product->getTable() . '.Pending', '=', 2)->get();

        $PrdouctAprive = $this->product->select(DB::raw('Count("' . $this->product->getTable() . '.ProductID' . '")as AproveProduct'))
            ->where($this->product->getTable() . '.Pending', '=', 1)->get();

        $PendingOrders = $this->order
            ->select(DB::raw('Count("' . $this->orderDetails->getTable() . '.OrderDetailsID' . '")as OrderPending') )
            ->select(DB::raw('Count("' . $this->orderDetails->getTable() . '.OrderDetailsID' . '")as OrderPending') )
            ->leftJoin($this->orderDetails->getTable(), $this->order->getTable() . '.OrderID', '=', $this->orderDetails->getTable() . '.OrderID')
            ->where($this->order->getTable() . '.OrderState', '=', 2)->get();

        $NumberOFOrders = $this->orderDetails
            ->select(DB::raw('Count("' . $this->orderDetails->getTable() . '.OrderDetailsID' . '")as NumberOFOrders'))
            ->leftJoin($this->order->getTable(), $this->orderDetails->getTable() . '.OrderID', '=', $this->order->getTable() . '.OrderID')
            ->where($this->order->getTable() . '.OrderState', '=', 3)
            
            ->get();
//        $NumberOFOrders = count($NumberOFOrders);

        return Response()->json([
            'ClintNumber' => $ClintNumber,
            'ActiveUsers' => $AtiveUsers,
            'InActiveUsers' => $InAtiveUsers,
            'Employee' => $employee,
            'PendingPrdouct' => $PendingPrdouct,
            'ProductAprove' => $PrdouctAprive,
            'PendingOrders' => $PendingOrders,
            'NumberOFOrders' => $NumberOFOrders

        ]);

    }
}
