<?php

namespace App\Http\Models;
use Illuminate\Database\Eloquent\Model;
/**
 * Created by PhpStorm.
 * User: mahmoud
 * Date: 6/16/2017
 * Time: 10:21 AM
 */
class ProductDetails extends Model
{
    protected $fillable =['ProductID','Shping'];
    protected $table = 'tblproductdetails';
    protected $primaryKey = 'ProductDetailsID';

}