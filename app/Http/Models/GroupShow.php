<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

/**
 * Created by PhpStorm.
 * User: mahmoud
 * Date: 6/16/2017
 * Time: 9:34 AM
 */
class GroupShow extends Model
{
    protected $fillable = [
        'group_name',
        'group_nameen'

    ];
    protected $primaryKey = 'GroupShowID';
    protected $table = 'tblgroupshow';

    public function Product()
    {
        return $this->hasMany('App\Http\Models\Product', 'GroupShowID');
    }

    public function ProductPaginateRelateion()
    {
        return $this->hasOne('App\Http\Models\Product', 'GroupShowID')->select('*')->paginate(3);
    }

    public function getGroupNameAttribute($value)
    {
        if (App::getLocale() == 'en')
            $value = $this->group_nameen;
        return $value;
    }


}