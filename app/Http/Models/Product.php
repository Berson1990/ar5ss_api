<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

/**
 * Created by PhpStorm.
 * User: mahmoud
 * Date: 6/16/2017
 * Time: 9:59 AM
 */
class Product extends Model
{
    protected $fillable = [
        'product_name',
        'product_nameen',
        'BrandID',
        'SizeID',
        'HotOfferID',
        'CategoryID',
        'Rate',
        'GroupShowID',
        'BarCode',
        'SeialNumbers',
        'Pending',
        'ProductState',
        'UserID',
        'Updated'
    ];
    protected $primaryKey = 'ProductID';
    protected $table = 'tblproduct';

    public function ProductPrice()
    {
        return $this->hasMany('App\Http\Models\ProductPrice','ProductID');
    }
    public function ProductColor()
    {
        return $this->hasMany('App\Http\Models\ProductColor','ProductID');
    }
    public function GroupShow()
    {
        return $this->belongsTo('App\Http\Models\GroupShow','GroupShowID');
    }
    public function HotoFFer()
    {
        return $this->belongsTo('App\Http\Models\HotOffer','HotOfferID');
    }

    public function getProductNameAttribute($value)
    {
        if (App::getLocale() == 'en')
            $value = $this->product_nameen;
        return $value;
    }


}