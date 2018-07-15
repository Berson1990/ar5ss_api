<?php

namespace App\Http\Models;
use \Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: mahmoud
 * Date: 6/16/2017
 * Time: 9:10 AM
 */
class Brand extends Model
{
    protected $table = 'tblbrand';
    protected $primaryKey = 'BrandID';
    protected $fillable = [
        'BarndName',
        'BarndNameE',
        'Logo'
    ];
}