<?php
/**
 * Created by PhpStorm.
 * User: mahmoud
 * Date: 09/10/2017
 * Time: 01:27 م
 */

namespace App\Http\Models;


use Illuminate\Database\Eloquent\Model;

class GuestCity extends Model
{
    protected $primaryKey = 'ID';
    protected $table = 'tblguestcity';
    protected $fillable = ['TokenID','CityID'];

}