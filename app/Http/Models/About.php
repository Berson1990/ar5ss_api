<?php

namespace App\Http\Models;
use \Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

/**
 * Created by PhpStorm.
 * User: mahmoud
 * Date: 6/16/2017
 * Time: 9:06 AM
 */
class About extends Model
{
    protected $table = 'tblabout';
    protected $primaryKey = 'AboutID';
    protected $fillable = [
         'about'
        , 'abouten'
        , 'policy'
        , 'policyen'
    ];
    public function getAboutAttribute($value)
    {
        if (App::getLocale() == 'en')
            $value = $this->abouten;
        return $value;
    }
    public function getPolicyAttribute($value)
    {
        if (App::getLocale() == 'en')
            $value = $this->policyen;
        return $value;
    }

}