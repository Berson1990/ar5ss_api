<?php

namespace App\Http\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: mahmoud
 * Date: 6/16/2017
 * Time: 10:25 AM
 */
class ProductPrice extends Model
{
    protected $primaryKey = 'ProductPriceID';
    protected $fillable = [
        'ProductID',
        'SellerID',
        'ProductPrice',
        'ProductPriceDesc',
        'ar5ss'
    ];
    protected $table = 'tblproductprice';

}