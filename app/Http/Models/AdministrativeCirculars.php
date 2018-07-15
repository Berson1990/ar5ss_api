<?php
/**
 * Created by PhpStorm.
 * User: mahmoud
 * Date: 10/10/2017
 * Time: 01:44 م
 */

namespace App\Http\Models;


use Illuminate\Database\Eloquent\Model;

class AdministrativeCirculars extends Model
{
    protected $table = 'tbladministrativecirculars';
    protected  $fillable = ['Post'];
    protected  $primaryKey = 'ID';


}