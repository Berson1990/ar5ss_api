<?php
/**
 * Created by PhpStorm.
 * User: mahmoud
 * Date: 28/09/2017
 * Time: 11:42 ص
 */

namespace App\Http\Models;



use Illuminate\Database\Eloquent\Model;

class SliderChangeMode extends  Model
{
    protected $table = 'tblslidermode';
    protected $fillable  = ['Mode'];
    protected $primaryKey ='ID';

}