<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Client;
use App\User;
use Auth;
use DB;

class ClientsController extends Controller
{
    //
    public function add(){
        
        return view('admin.clients.create');
    }
    
    public function add2(Request $request){
        
        $client = Client::find($request -> id);
        
        return view('admin.clients.create2',['client' => $client]);
        
        
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
            
            return redirect('admin/clients/list');
            
        }else{
            $client->fill($form);
            $client->save();
            
            return redirect('admin/clients/list');
            
        }
       
        
        $client->fill($form);
        $client->save();
        
        return redirect('admin/clients/list');
        
    }
    
    public function update(Request $request){
        
        $client = Client::find($request->id);
        
        $form = $request->all();
        
        unset($form['_token']);
        
        $client->fill($form);
        $client->save();
        
        return view('admin.sales.clientTop',['client'=> $client]);
        
    }
    
    public function login(){
        return view('login');
    }
    
    public function usersList(){
        
        $users = User::all();
        
        return view('admin.clients.usersList',['users' => $users]);
    }
    
    public function userDelete(Request $request){
        
        $userId = $request -> id;
        $user = User::find($userId);
        
        DB::transaction( function() use($user){
        $user->delete();
        });
        
        $users = User::all();
        
        return view('admin.clients.usersList',['users' => $users]);
        
    }
    
}
