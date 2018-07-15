<?php

namespace App\Http\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: mahmoud
 * Date: 6/16/2017
 * Time: 9:46 AM
 */
class Location extends Model
{
    protected $fillable = [
        'UserID',
        'latitude',
        'longitude',
        'Place',
        'Defualt'
    ];
    protected $table = 'tbllocation';
    protected $primaryKey = 'LocationID';

}