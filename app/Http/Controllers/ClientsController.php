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
        $form = $request ->all();
        unset($form['_token']);
        
        if($request->id != null){
            $client = Client::find($request->id);
            $client->fill($form);
            $client->save();
            
            return redirect('clients/list');
            
        }else{
            $client->fill($form);
            $client->save();
            
            return redirect('clients/list');
            
        }
       
        
        $client->fill($form);
        $client->save();
        
        return redirect('clients/list');
        
    }
    
    public function update(Request $request){
        
        $client = Client::find($request->id)->get();
        $form = $request->all();
        
        unset($form['_token']);
        
        $client->fill($form);
        $client->save();
        
        return redirect('clients/create');
        
    }
    
}
