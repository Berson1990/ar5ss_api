<?php
/**
 * Created by PhpStorm.
 * User: mahmoud
 * Date: 28/09/2017
 * Time: 01:57 م
 */

namespace App\Http\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class City extends Model
{

    protected $table    ='tblcity';
    protected $fillable  = ['city_name','city_nameen'];
    protected $primaryKey = 'CityID';


    public function getCitynameAttribute($value)
    {
        if (App::getLocale() == 'en')
            $value = $this->citynameen;
        return $value;
    }

}