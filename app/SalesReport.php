<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesReport extends Model
{
    //
    protected $fillable = ['client_id','tapeM-price','tapeM-count','tapeL-price','tapeL-count',
    'pantsM-price','pantsM-count','pantsL-price','pantsL-count','pad300-price','pad300-count',
    'pad400-price','pad400-count','pad600-price','pad600-count','pad800-price','pad800-count',
    'pad1000-price','pad1000-count','pad1200-price','pad1200-count','year','month'];
    
}
