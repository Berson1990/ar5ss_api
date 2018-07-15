<?php
/**
 * Created by PhpStorm.
 * User: Alex4Prog
 * Date: 26/07/2017
 * Time: 12:28 م
 */

namespace App\Http\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
class ProductProperty extends Model
{
    protected $table = 'tblproductproperty';
    protected $fillable = ['property_name','property_namee', 'CategoryID'];
    protected $primaryKey = 'ProductPropertyID';

    public function ProductValue(){
        return $this->hasMany('App\Http\Models\PropertyValue','ProductPropertyID');
    }
    public function getPropertyNameAttribute($value)
    {
        if (App::getLocale() == 'en')
            $value = $this->property_namee;
        return $value;
    }
}