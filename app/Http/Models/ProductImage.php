<?php

namespace App\Http\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: mahmoud
 * Date: 6/16/2017
 * Time: 10:23 AM
 */
class ProductImage extends Model
{
protected $primaryKey = 'ImageID';
protected $fillable = ['ProductColorID','Image'];
protected $table ='tblproductimage';
}