<?php

namespace App\Http\Models;
use \Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: mahmoud
 * Date: 6/16/2017
 * Time: 9:12 AM
 */
class Cart extends Model
{
    protected $table = 'tblcart';
    protected $primaryKey = 'CartID';
    protected $fillable = [
        'ProductID',
        'UserID',
        'SellerID',
        'TokenID',
        'QTY',
        'Shiping',
        'CartState'//1 open //2 close

    ];
}