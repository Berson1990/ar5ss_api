<?php

namespace App\Http\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

/**
 * Created by PhpStorm.
 * User: mahmoud
 * Date: 6/16/2017
 * Time: 9:23 AM
 */
class CompalinType extends Model
{
    protected $fillable = [
        'type_name',
        'type_nameen'
    ];
    protected $table = 'tblcomplaintype';
    protected $primaryKey = 'ComplainTypeId';

    public function getTypeNameAttribute($value)
    {
        if (App::getLocale() == 'en')
            $value = $this->type_nameen;
        return $value;
    }
}