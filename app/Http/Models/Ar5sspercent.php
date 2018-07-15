<?php
/**
 * Created by PhpStorm.
 * User: mahmoud
 * Date: 05/10/2017
 * Time: 12:54 م
 */

namespace App\Http\Models;


use Illuminate\Database\Eloquent\Model;

class Ar5sspercent extends Model
{
    protected $table = 'tblar5sspercent';
    protected $primaryKey = 'ID';
    protected $fillable = ['percent','BankPresnt','Delegate'];

}