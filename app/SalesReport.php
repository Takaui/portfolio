<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesReport extends Model
{
    //
    protected $fillable = ['client_id','tapeM_price','tapeM_count','tapeL_price','tapeL_count',
    'pantsM_price','pantsM_count','pantsL_price','pantsL_count','pad300_price','pad300_count',
    'pad400_price','pad400_count','pad600_price','pad600_count','pad800_price','pad800_count',
    'pad1000_price','pad1000_count','pad1200_price','pad1200_count','tapem_user_count','tapel_user_count','pantsm_user_count','pantsl_user_count','year','month'];
    
    public function client(){
        return $this->belongsTo('App\Client');
    }
}
