<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: mahmoud
 * Date: 6/16/2017
 * Time: 10:33 AM
 */
class Users extends Model
{

    protected $primaryKey = 'UserID';
    protected $fillable = [
        'Name',
        'Email',
        'Password',
        'Mobile',
        'UseType',
        'DivceType',
        'Token',
        'LocationID',
        'ActivateCode',
        'Image',
        'Adress',
        'Notification',
        'IsActive',
        'NumberOfEntryTimes',
        'Percentage'
    ];
    protected $table = 'tblusers';

    public function Location(){
        return $this->hasMany('App\Http\Models\Location','UserID');
    }
    public function Seller()
    {
        return $this->hasMany('App\Http\Models\Seller' ,'UserID');
    }

}