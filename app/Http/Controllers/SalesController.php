<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Client;
use App\SalesReport;
use Auth;

class SalesController extends Controller
{
    //
    public function add(Request $request){
        
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
        //$salesReports = SalesReport::where('client_id',$request->id)->where('year',$request->year)->where('month',$request->month)->get();
         
        unset($form['_token']);
        
        /*
        if($salesReports = null){
            $SalesReport->fill($form);
            $SalesReport->save();
        }else{
            $salesReports->fill($form);
            $SalesReports->save();
        }
        */
        
        unset($form['_token']);
        
        $SalesReport->fill($form);
        $SalesReport->save();
        
        return redirect('clients/list');
        
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
    
    public function result(Request $request){
        
        //$salesReports = SalesReport::where('client_id',$request->id);
        $client = Client::find($request->id);
        $salesReports = $client -> salesReports;
        
        $year_ = date('Y');
        $years = array();
        for($i=$year_ ;$i >= 2018;$i--){
            array_push($years,$i);
        }
        
        $year = 0;
        $month = 0;
        if ($request->year != null && $request-> month != null){
            $year = $request ->year;
            $month = $request ->month;
            
            $salesReport = $salesReports->where('year',$year)->where('month',$month)->first();
            
            if(empty($salesReport)){
               abort(404); 
            
            }else{
                $tape_group_count = $salesReport->tapeM_count + $salesReport->tapeL_count;
                $pants_group_count = $salesReport->pantsM_count + $salesReport->pantsL_count;
                $pad_group_count = $salesReport->pad300_count + $salesReport->pad400_count + $salesReport->pad600_count + $salesReport->pad800_count + $salesReport->pad1000_count + $salesReport->pad1200_count;
            
                return view('sales.result',['salesReport'=>$salesReport,'tape_group_count'=>$tape_group_count,'pants_group_count'=>$pants_group_count,'pad_group_count'=>$pad_group_count,'years'=>$years]);
            }    
                
        }else{
            $salesReport = $salesReports->sortByDesc('year')->sortByDesc('month')->first();
            
            if(empty($salesReport)){
                abort(404);
                
            }else{
                $tape_group_count = $salesReport->tapeM_count + $salesReport->tapeL_count;
                $pants_group_count = $salesReport->pantsM_count + $salesReport->pantsL_count;
                $pad_group_count = $salesReport->pad300_count + $salesReport->pad400_count + $salesReport->pad600_count + $salesReport->pad800_count + $salesReport->pad1000_count + $salesReport->pad1200_count;
                
                return view('sales.result',['salesReport'=>$salesReport,'tape_group_count'=>$tape_group_count,'pants_group_count'=>$pants_group_count,'pad_group_count'=>$pad_group_count,'years'=>$years]);
            }
        }
        
    }
    
}
