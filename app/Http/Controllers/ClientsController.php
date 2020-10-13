<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use Auth;

class ClientsController extends Controller
{
    //
    public function add(){
        
        return view('clients.create');
    }
    
    public function create(Request $request){
        
        $client = new Client;
        $form = $request->all();
        
        unset($form['_token']);
        
        $client->fill($form);
        $client->save();
        
        return redirect('clients/create');
        
    }
    
}
