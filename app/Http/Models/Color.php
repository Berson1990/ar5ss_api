<?php

namespace App\Http\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

/**
 * Created by PhpStorm.
 * User: mahmoud
 * Date: 6/16/2017
 * Time: 9:20 AM
 */
class Color extends Model
{
    protected $table = 'tblcolor';
    protected $fillable = [
        'color_name',
        'color_nameen',
        'ColorHexa',
    ];
    protected $primaryKey = 'ColorID';


    public function getColornameenAttribute($value)
    {
        if (App::getLocale() == 'en')
            $value = $this->color_nameen;
        return $value;
    }


}