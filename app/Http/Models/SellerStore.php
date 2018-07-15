<?php
/**
 * Created by PhpStorm.
 * User: Alex4Prog
 * Date: 11/07/2017
 * Time: 01:25 م
 */

namespace App\Http\Models;


use Illuminate\Database\Eloquent\Model;

class SellerStore extends Model
{
    protected $fillable = ['SellerID','Type','Long','Lat',];
    protected $primaryKey = 'StoreID';
    protected $table  = 'tblsellerstore';

}