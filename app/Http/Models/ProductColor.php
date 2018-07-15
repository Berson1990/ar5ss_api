<?php

namespace App\Http\Models;
use Illuminate\Database\Eloquent\Model;
/**
 * Created by PhpStorm.
 * User: mahmoud
 * Date: 6/16/2017
 * Time: 10:18 AM
 */
class ProductColor extends Model
{
    protected $fillable = ['ColorID','ProductID'];
    protected $primaryKey = 'ProductColorID';
    protected $table = 'tblproductcolor';

    public function ProductImage(){
        return $this->hasMany('App\Http\Models\ProductImage','ProductColorID');
    }
    public function ColorsOfProduct(){
        return $this->belongsTo('App\Http\Models\Color','ColorID');
    }
}