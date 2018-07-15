<?php

namespace App\Http\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: mahmoud
 * Date: 6/16/2017
 * Time: 9:25 AM
 */
class Complain extends Model
{
    protected $primaryKey = 'ComplainId';
    protected $fillable = [

        'UserID',
        'ComplainTypeId',
        'Tittle',
        'Descriotion',
        'IsRead',
        'Sort',
        'Paind'
    ];
    protected $table = 'tblcomplain';
}