<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: mahmoud
 * Date: 6/16/2017
 * Time: 9:51 AM
 */
class Order extends Model
{
    protected $table = 'tblorder';
    protected $fillable = [
        'UserID',
        'OrderState',
        'PaymentID',
        'LocationID',
        'RefusedReason',
        'FinanceState',
        'CityName'
    ];
    protected $primaryKey = 'OrderID';

    public function OrderDetails()
    {
        return $this->hasMany('App\Http\Models\OrderDetails', 'OrderID');
    }

}