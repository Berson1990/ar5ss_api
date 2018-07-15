<?php
/**
 * Created by PhpStorm.
 * User: mahmoud
 * Date: 08/10/2017
 * Time: 04:20 م
 */

namespace App\Http\Models;


use Illuminate\Database\Eloquent\Model;

class SuppliersSupport extends Model
{
    protected $fillable = ['SupplierID','CityID',];
    protected $table = 'tblsupplierssupport';
    protected $primaryKey = 'ID';

}