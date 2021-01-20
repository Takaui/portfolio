<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use Auth;
use DB;

class ClientsController extends Controller
{
    //
    public function add(){
        
        return view('clients.create');
    }
    
    public function add2(Request $request){
        
        $client = Client::find($request -> id);
        
        return view('clients.create2',['client' => $client]);
        
        
    }
    public function create(Request $request){
        
        $client = new Client;
        $form = $request ->all();
        unset($form['_token']);
        
        if($request->id != null){
            $client = Client::find($request->id);
            
            //トランザクション処理
            DB::transaction( function (){
            $client->fill($form);
            $client->save();
            
            });
            
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
        
        $client = Client::find($request->id);
        
        $form = $request->all();
        
        unset($form['_token']);
        
        $client->fill($form);
        $client->save();
        
        return view('sales.clientTop',['client'=> $client]);
        
    }
    
    public function login(){
        return view('login');
    }
    
}
