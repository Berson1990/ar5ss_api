<?php

namespace App\Http\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: mahmoud
 * Date: 6/16/2017
 * Time: 9:43 AM
 */
class HotOffer extends Model
{
    protected $fillable = [
        'FromDate',
        'ToDate'
    ];
    protected $table = 'tblhotoffer';
    protected $primaryKey = 'HotOfferId';



}