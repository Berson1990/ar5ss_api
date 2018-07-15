<?php

namespace App\Http\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: mahmoud
 * Date: 6/16/2017
 * Time: 9:31 AM
 */
class Favorit extends Model
{
    protected $primaryKey = 'FavoritID';
    protected $table = 'tblfaovorit';
    protected $fillable = [
        'UserID',
        'ProductID',
        'TokenID'
    ];
}