<?php
/**
 * Created by PhpStorm.
 * User: Alex4Prog
 * Date: 13/07/2017
 * Time: 09:12 ص
 */

namespace App\Http\Models;


use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $fillable = ['Rate','ProductID','UserID'];
    protected $primaryKey ='RateID';
    protected $table = 'tblrate';

}