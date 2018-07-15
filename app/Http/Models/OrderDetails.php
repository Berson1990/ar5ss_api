<?php
namespace App\Http\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: mahmoud
 * Date: 6/16/2017
 * Time: 9:54 AM
 */
class OrderDetails extends Model
{
    protected $primaryKey = 'OrderDetailsID';
    protected $fillable = [
        'OrderID',
        'PriceID',
        'Rate',
        'SellaerID',
        'StoreID',
        'ProductID',
        'Shiping',
        'ItemsQTY',
        'SupplierID'
    ];
    protected $table = 'tblorderdetails';
    public function ProductPrice(){
        return $this->hasMany('App\Http\Models\ProductPrice','ProductID');
    }

}