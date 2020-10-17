<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['facility_type','user_name','number_of_bed'];
    
    public function salesReports(){
        return $this->hasMany('App\SalesReport');
    }
}
