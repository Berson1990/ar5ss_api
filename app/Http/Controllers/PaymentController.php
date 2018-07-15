<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use App\Http\Models\Payment;

class PaymentController extends Controller
{
    public function getPayment(){
        return Response()->json(Payment::all());
    }
}
