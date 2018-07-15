<?php
/**
 * Created by PhpStorm.
 * User: Alex4Prog
 * Date: 26/07/2017
 * Time: 12:31 م
 */

namespace App\Http\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
class PropertyValue extends Model
{
    protected $table = 'tblpropertyvalue';
    protected $fillable = ['ProductID', 'ProductPropertyID', 'value','valuee'];
    protected $primaryKey = 'PropertyValueID';



    public function getValueAttribute($value)
    {
        if (App::getLocale() == 'en')
            $value = $this->vaulee;
        return $value;
    }
}