<?php

namespace App\Http\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: mahmoud
 * Date: 6/16/2017
 * Time: 10:30 AM
 */
class ShpingPrice extends Model
{
    protected $fillable = [
        'SellerID',
        'ShipingPrice'
    ];
    protected $table = 'tblshipingprice';
    protected $primaryKey = 'ShpingID';
}