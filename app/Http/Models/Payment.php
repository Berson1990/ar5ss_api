<?php

namespace App\Http\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

/**
 * Created by PhpStorm.
 * User: mahmoud
 * Date: 6/16/2017
 * Time: 9:56 AM
 */
class Payment extends Model
{
    protected $fillable = [
        'payment_name',
        'payment_nameen',
        'url'
    ];
    protected $primaryKey = 'PaymentID';
    protected $table = 'tblpayment';

    public function getPaymentNameAttribute($value)
    {
        if (App::getLocale() == 'en')
            $value = $this->payment_nameen;
        return $value;
    }

}