<?php

namespace App\Http\Models;
use Illuminate\Database\Eloquent\Model;
/**
 * Created by PhpStorm.
 * User: mahmoud
 * Date: 6/16/2017
 * Time: 9:41 AM
 */
class HotAdds extends Model
{
protected $fillable = ['Image','CategoryID','ProductID'];
protected $table = 'tblhotads';
protected  $primaryKey = 'HotAdsID';

}