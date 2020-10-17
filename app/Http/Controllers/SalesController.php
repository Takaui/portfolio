<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Client;
use Auth;

class SalesController extends Controller
{
    //
    public function add(){
        
        $client = Client::find($request->id);

        $year = date('Y');
        $years = array();
        for($i=$year ;$i >= 2018;$i--){
            array_push($years,$i);
        }
        return view('sales.create',[ 'years' => $years,'client'=>$client]);
    }
    
    public function create(Request $request){
        
        $SalesReport = new SalesReport;
        $form = $request->all();
        
        unset($form['_token']);
        
        $SalesReport->fill($form);
        $SalesReport->save();
        
        return redirect('sales/report/create');
        
    }
    
    public function edit(Request $request){
         $clients = Client::find($request->id);
        if (empty($clients)){
            abort(404);
        }
        return view('sales.create',['clients' =>$client]);
    }
    
    public function list(Request $request){
        $client = Client::all();
        return view('clients.list',['clients' => $client]);
    }
}
