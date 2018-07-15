<?php

namespace App\Http\Models;
use Illuminate\Database\Eloquent\Model;
/**
 * Created by PhpStorm.
 * User: mahmoud
 * Date: 6/16/2017
 * Time: 9:49 AM
 */
class Offer extends Model
{
    protected $fillable = [

        'ProductID',
        'OfferPrice'
    ];
    protected $primaryKey = 'OfferID';
    protected $table = 'tbloffer';
}