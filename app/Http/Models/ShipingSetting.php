<?php
/**
 * Created by PhpStorm.
 * User: Alex4Prog
 * Date: 11/07/2017
 * Time: 01:29 م
 */

namespace App\Http\Models;


use Illuminate\Database\Eloquent\Model;

class ShipingSetting extends Model
{
    protected $primaryKey = 'ShipingSettingID';
    protected $table = 'tblshipingsetting';
    protected $fillable = [
        'SellerID',
        'Price',
        'Distance'

    ];
}