<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SalesController extends Controller
{
    //
    public function add(){
        
        $year = date('Y');
        $years = array();
        for($i=$year ;$i >= 2018;$i--){
            array_push($years,$i);
        }
        return view('sales.create',[ 'years' => $years]);
    }
    
    public function create(Request $request){
        
        $SalesResult = new SalesResult;
        $form = $request->all();
        
        unset($form['_token']);
        
        $SalesResult->fill($form);
        $SalesResult->save();
        
        return redirect('sales/create');
        
    }
    
    public function edit(Request $request){
         $clients = Client::find($request->id);
        if (empty($clients)){
            abort(404);
        }
        return view('sales.create',['clients' =>$client]);
    }
    
    public function addkari(){
        
        return view('clients.list');
    }
    
}