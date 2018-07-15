<?php

namespace App\Http\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
/**
 * Created by PhpStorm.
 * User: mahmoud
 * Date: 6/16/2017
 * Time: 10:32 AM
 */
class Size extends  Model
{
    protected $fillable = [
        'size',
        'sizeen',
        'CategoryID'
    ];
    protected $table = 'tblsize';
    protected $primaryKey = 'SizeID';


    public function getSizAttribute($value)
    {
        if (App::getLocale() == 'en')
            $value = $this->sizeen;
        return $value;
    }

}