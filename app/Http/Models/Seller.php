<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use DB;

/**
 * Created by PhpStorm.
 * User: mahmoud
 * Date: 6/16/2017
 * Time: 10:26 AM
 */
class Seller extends Model
{
    protected $table = 'tblseller';
    protected $fillable = [
        'name',
        'nameen',
        'UserID',
        'Descrption',
        'DescrptionEn',
        'CityID'
    ];

    protected $primaryKey = 'SellerId';

    public function SellerProduct()
    {
   return $this->hasMany('App\Http\Models\OrderDetails','SellaerID');

    }

    public function getNameAttribute($value)
    {
        if (App::getLocale() == 'en')
            $value = $this->product_nameen;
        return $value;
    }

}