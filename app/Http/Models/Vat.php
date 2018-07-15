<?php
/**
 * Created by PhpStorm.
 * User: mahmoud
 * Date: 28/01/2018
 * Time: 04:07 م
 */

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;


class Vat extends Model
{
    protected $table = 'tblvat';
    protected $primaryKey = 'id';
    protected $fillable = ['value'];

}