<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;


/**
 * Created by PhpStorm.
 * User: mahmoud
 * Date: 6/16/2017
 * Time: 9:16 AM
 */
class Category extends Model
{
    protected $fillable = [
        'categroy_name',
        'categroy_nameen',
        'CategoryType',
        'CategoryImage',
        'Hide'
    ];
    protected $primaryKey = 'CategoryID';
    protected $table = 'tblcategory';

    public function getCategroyNameAttribute($value)
    {
        if (App::getLocale() == 'en')
            $value = $this->categroy_nameen;
        return $value;
    }

}