<?php

namespace App\Http\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: mahmoud
 * Date: 6/16/2017
 * Time: 10:28 AM
 */
class SellerProduct extends Model
{
    protected $primaryKey = 'SellerPrductID';
    protected $table = 'tblsellerproduct';
    protected $fillable = [
        'SellerID',
        'ProductID',
        'ProductQTY',
        'Shiping',
        'ShipingState',
        'SupplierID',
        'StoreID',
        'Date',
        'allow',
        'NproductQTY'
    ];

}