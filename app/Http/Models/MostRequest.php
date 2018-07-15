<?php
/**
 * Created by PhpStorm.
 * User: mahmoud
 * Date: 07/09/2017
 * Time: 09:18 ص
 */

namespace App\Http\Models;


use Illuminate\Database\Eloquent\Model;

class MostRequest extends Model
{
    protected $fillable = ['PeriodofTime','RequestNumber','PeriodofTimeRecentlyAdd'];
    protected $primaryKey = 'ID';
    protected $table = 'tblmostrequest';

}