<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Profile;
use App\Record;

class ProfileController extends Controller
{
    public function index()
    {
        
        $profile = new Profile;
        $profile_form =  Profile::all();
        if(empty($profile_form)){
            abort(404);
        }
        
        return view('admin.profile.index',['profile_form' => $profile_form]);
    }
    
}




