<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Client;
use App\SalesReport;
use Auth;
use App\TapeAutaPlan;
use App\TapeInnerPlan;
use App\PantsAutaPlan;
use App\PantsInnerPlan;

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
        //$salesReports = SalesReport::where('client_id',$request->id)->where('year',$request->year)->where('month',$request->month)->first();
         
        unset($form['_token']);
        
        /*
        if($salesReports != null){
            $SalesReports->fill($form);
            $SalesReports->save();
        }else{
            $salesReport->fill($form);
            $SalesReport->save();
        }
        
        return redirect('clients/list');
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
        
        $salesReports = SalesReport::where('client_id',$request->id)->get();
        
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
            $user_name = $salesReport -> client -> user_name;
        
            if(empty($salesReport)){
               abort(404); 
            
            }else{
                
                //品群ごと枚数
                $tape_group_count = $salesReport->tapeM_count + $salesReport->tapeL_count;
                $pants_group_count = $salesReport->pantsM_count + $salesReport->pantsL_count;
                $pad_group_count = $salesReport->pad300_count + $salesReport->pad400_count + $salesReport->pad600_count + $salesReport->pad800_count + $salesReport->pad1000_count + $salesReport->pad1200_count;
                
                //パッドごと枚数
                $pad300_count = $salesReport->pad300_count;
                $pad400_count = $salesReport->pad400_count;
                $pad600_count = $salesReport->pad600_count;
                $pad800_count = $salesReport->pad800_count;
                $pad1000_count = $salesReport->pad1000_count;
                $pad1200_count = $salesReport->pad1200_count;
                
                //品群ごと金額
                $tape_group_price = $salesReport->tapeM_count * $salesReport->tapeM_price + $salesReport->tapeL_count * $salesReport->tapeL_price;
                $pants_group_price = $salesReport->pantsM_count * $salesReport->pantsM_price + $salesReport->pantsL_count * $salesReport->pantsL_price;
                $pad_group_price = $salesReport->pad300_count * $salesReport->pad300_price + $salesReport->pad400_count * $salesReport->pad400_price 
                                   + $salesReport->pad600_count * $salesReport->pad600_price + $salesReport->pad800_count * $salesReport->pad800_price 
                                   + $salesReport->pad1000_count * $salesReport->pad1000_price + $salesReport->pad1200_count * $salesReport->pad1200_price;
                
                //パッドごと金額
                $pad300_price = $salesReport->pad300_price;
                $pad400_price = $salesReport->pad400_price;
                $pad600_price = $salesReport->pad600_price;
                $pad800_price = $salesReport->pad800_price;
                $pad1000_price = $salesReport->pad1000_price;
                $pad1200_price = $salesReport->pad1200_price;
                
                return view('sales.result',['salesReport'=>$salesReport,'tape_group_count'=>$tape_group_count,'pants_group_count'=>$pants_group_count,'pad_group_count'=>$pad_group_count,'tape_group_price'=>$tape_group_price,
                            'pants_group_price'=>$pants_group_price,'pad_group_price'=>$pad_group_price,'years'=>$years,'pad300_count'=>$pad300_count,'pad400_count' => $pad400_count,'pad600_count' =>$pad600_count,
                            'pad800_count' =>$pad800_count,'pad1000_count' =>$pad1000_count,'pad1200_count' =>$pad1200_count,'pad300_price'=>$pad300_price,'pad400_price' => $pad400_price,'pad600_price' =>$pad600_price,
                            'pad800_price' =>$pad800_price,'pad1000_price' =>$pad1000_price,'pad1200_price' =>$pad1200_price,'user_name'=>$user_name]);
            
                
            }    
                
        }else{
            $salesReport = $salesReports->sortByDesc('year')->sortByDesc('month')->first();
            $user_name = $salesReport -> client -> user_name;

            
            if(empty($salesReport)){
                abort(404);
                
            }else{
                //品群ごと枚数
                $tape_group_count = $salesReport->tapeM_count + $salesReport->tapeL_count;
                $pants_group_count = $salesReport->pantsM_count + $salesReport->pantsL_count;
                $pad_group_count = $salesReport->pad300_count + $salesReport->pad400_count + $salesReport->pad600_count + $salesReport->pad800_count + $salesReport->pad1000_count + $salesReport->pad1200_count;
                
                //パッドごと枚数
                $pad300_count = $salesReport->pad300_count;
                $pad400_count = $salesReport->pad400_count;
                $pad600_count = $salesReport->pad600_count;
                $pad800_count = $salesReport->pad800_count;
                $pad1000_count = $salesReport->pad1000_count;
                $pad1200_count = $salesReport->pad1200_count;
                
                //品群ごと金額
                $tape_group_price = $salesReport->tapeM_count * $salesReport->tapeM_price + $salesReport->tapeL_count * $salesReport->tapeL_price;
                $pants_group_price = $salesReport->pantsM_count * $salesReport->pantsM_price + $salesReport->pantsL_count * $salesReport->pantsL_price;
                $pad_group_price = $salesReport->pad300_count * $salesReport->pad300_price + $salesReport->pad400_count * $salesReport->pad400_price 
                                   + $salesReport->pad600_count * $salesReport->pad600_price + $salesReport->pad800_count * $salesReport->pad800_price 
                                   + $salesReport->pad1000_count * $salesReport->pad1000_price + $salesReport->pad1200_count * $salesReport->pad1200_price;
                
                //パッドごと金額
                $pad300_price = $salesReport->pad300_price;
                $pad400_price = $salesReport->pad400_price;
                $pad600_price = $salesReport->pad600_price;
                $pad800_price = $salesReport->pad800_price;
                $pad1000_price = $salesReport->pad1000_price;
                $pad1200_price = $salesReport->pad1200_price;
                
                return view('sales.result',['salesReport'=>$salesReport,'tape_group_count'=>$tape_group_count,'pants_group_count'=>$pants_group_count,'pad_group_count'=>$pad_group_count,'tape_group_price'=>$tape_group_price,
                            'pants_group_price'=>$pants_group_price,'pad_group_price'=>$pad_group_price,'years'=>$years,'pad300_count'=>$pad300_count,'pad400_count' => $pad400_count,'pad600_count' =>$pad600_count,
                            'pad800_count' =>$pad800_count,'pad1000_count' =>$pad1000_count,'pad1200_count' =>$pad1200_count,'pad300_price'=>$pad300_price,'pad400_price' => $pad400_price,'pad600_price' =>$pad600_price,
                            'pad800_price' =>$pad800_price,'pad1000_price' =>$pad1000_price,'pad1200_price' =>$pad1200_price,'user_name'=>$user_name]);
                
            }
        }
    }
    
    
    public function plan(Request $request){
        
        $times = array();
        for($i = 1 ; $i <=24 ; $i++){
            array_push($times,$i);
        }
        
        $client = Client::find($request->id);
        if (empty($client)){
            abort(404);
        }
        
        //もしDBに登録データがあったら、目標設定ページで内容を表示できるようにする
        
        
        return view('sales.plan',['times'=> $times,'client'=>$client]);
    }
    
    public function planSave(Request $request){
        
        $tapeAutaPlan1 = new TapeAutaPlan;
        $tapeAutaPlan2 = new TapeAutaPlan;
        $tapeAutaPlan3 = new TapeAutaPlan;
        $tapeAutaPlan4 = new TapeAutaPlan;
        $tapeAutaPlan5 = new TapeAutaPlan;
        
        $tapeInnerPlan1 = new TapeInnerPlan;
        $tapeInnerPlan2 = new TapeInnerPlan;
        $tapeInnerPlan3 = new TapeInnerPlan;
        $tapeInnerPlan4 = new TapeInnerPlan;
        $tapeInnerPlan5 = new TapeInnerPlan;
        
        $pantsAutaPlan1 = new PantsAutaPlan;
        $pantsAutaPlan2 = new PantsAutaPlan;
        $pantsAutaPlan3 = new PantsAutaPlan;
        $pantsAutaPlan4 = new PantsAutaPlan;
        $pantsAutaPlan5 = new PantsAutaPlan;
        
        $pantsInnerPlan1 = new PantsInnerPlan;
        $pantsInnerPlan2 = new PantsInnerPlan;
        $pantsInnerPlan3 = new PantsInnerPlan;
        $pantsInnerPlan4 = new PantsInnerPlan;
        $pantsInnerPlan5 = new PantsInnerPlan;
        
        if( $request-> t_tape_exchange_time1 != null && $request -> t_tape_item1 != null){
        $tapeAutaPlan1 -> client_id = $request -> client_id;
        $tapeAutaPlan1 -> t_tape_exchange_time = $request -> t_tape_exchange_time1;
        $tapeAutaPlan1 -> t_tape_item = $request -> t_tape_item1;
        $tapeAutaPlan1 ->save();
        }
        if( $request-> t_tape_exchange_time2 != null && $request -> t_tape_item2 != null){
        $tapeAutaPlan2 -> client_id = $request -> client_id;
        $tapeAutaPlan2 -> t_tape_exchange_time = $request -> t_tape_exchange_time2;
        $tapeAutaPlan2 -> t_tape_item = $request -> t_tape_item2;
        $tapeAutaPlan2 ->save();
        }
        if( $request-> t_tape_exchange_time3 != null && $request -> t_tape_item3 != null){
        $tapeAutaPlan3 -> client_id = $request -> client_id;
        $tapeAutaPlan3 -> t_tape_exchange_time = $request -> t_tape_exchange_time3;
        $tapeAutaPlan3 -> t_tape_item = $request -> t_tape_item3;
        $tapeAutaPlan3 ->save();
        }
        if( $request-> t_tape_exchange_time4 != null && $request -> t_tape_item4 != null){
        $tapeAutaPlan4 -> client_id = $request -> client_id;
        $tapeAutaPlan4 -> t_tape_exchange_time = $request -> t_tape_exchange_time4;
        $tapeAutaPlan4 -> t_tape_item = $request -> t_tape_item4;
        $tapeAutaPlan4 ->save();
        }
        if( $request-> t_tape_exchange_time5 != null && $request -> t_tape_item5 != null){
        $tapeAutaPlan5 -> client_id = $request -> client_id;
        $tapeAutaPlan5 -> t_tape_exchange_time = $request -> t_tape_exchange_time5;
        $tapeAutaPlan5 -> t_tape_item = $request -> t_tape_item5;
        $tapeAutaPlan5 ->save();
        }
        
        if( $request-> t_pad_exchange_time1 != null && $request -> t_pad_item1 != null){
        $tapeInnerPlan1 -> client_id = $request -> client_id;
        $tapeInnerPlan1 -> t_pad_exchange_time = $request -> t_pad_exchange_time1;
        $tapeInnerPlan1 -> t_pad_item = $request -> t_pad_item1;
        $tapeInnerPlan1 ->save();
        }
        if( $request-> t_pad_exchange_time2 != null && $request -> t_pad_item2 != null){
        $tapeInnerPlan2 -> client_id = $request -> client_id;
        $tapeInnerPlan2 -> t_pad_exchange_time = $request -> t_pad_exchange_time2;
        $tapeInnerPlan2 -> t_pad_item = $request -> t_pad_item2;
        $tapeInnerPlan2 ->save();
        }
        if( $request-> t_pad_exchange_time3 != null && $request -> t_pad_item3 != null){
        $tapeInnerPlan3 -> client_id = $request -> client_id;
        $tapeInnerPlan3 -> t_pad_exchange_time = $request -> t_pad_exchange_time3;
        $tapeInnerPlan3 -> t_pad_item = $request -> t_pad_item3;
        $tapeInnerPlan3 ->save();
        }
        if( $request-> t_pad_exchange_time4 != null && $request -> t_pad_item4 != null){
        $tapeInnerPlan4 -> client_id = $request -> client_id;
        $tapeInnerPlan4 -> t_pad_exchange_time = $request -> t_pad_exchange_time4;
        $tapeInnerPlan4 -> t_pad_item = $request -> t_pad_item4;
        $tapeInnerPlan4 ->save();
        }
        if( $request-> t_pad_exchange_time5 != null && $request -> t_pad_item5 != null){
        $tapeInnerPlan5 -> client_id = $request -> client_id;
        $tapeInnerPlan5 -> t_pad_exchange_time = $request -> t_pad_exchange_time5;
        $tapeInnerPlan5 -> t_pad_item = $request -> t_pad_item5;
        $tapeInnerPlan5 ->save();
        }
       
       
        if( $request-> p_pants_exchange_time1 != null && $request -> t_pants_item1!= null){
        $pantsAutaPlan1 -> client_id = $request -> client_id;
        $pantsAutaPlan1 -> p_pants_exchange_time = $request -> p_pants_exchange_time1;
        $pantsAutaPlan1 -> p_pants_item = $request -> p_pants_item1;
        $pantsAutaPlan1 ->save();
        }
        if( $request-> p_pants_exchange_time2 != null && $request -> t_pants_item2 != null){
        $pantsAutaPlan2 -> client_id = $request -> client_id;
        $pantsAutaPlan2 -> p_pants_exchange_time = $request -> p_pants_exchange_time2;
        $pantsAutaPlan2 -> p_pants_item = $request -> p_pants_item2;
        $pantsAutaPlan2 ->save();
        }
        if( $request-> p_pants_exchange_time3 != null && $request -> t_pants_item3 != null){
        $pantsAutaPlan3 -> client_id = $request -> client_id;
        $pantsAutaPlan3 -> p_pants_exchange_time = $request -> p_pants_exchange_time3;
        $pantsAutaPlan3 -> p_pants_item = $request -> p_pants_item3;
        $pantsAutaPlan3 ->save();
        }
        if( $request-> p_pants_exchange_time4 != null && $request -> t_pants_item4 != null){
        $pantsAutaPlan4 -> client_id = $request -> client_id;
        $pantsAutaPlan4 -> p_pants_exchange_time = $request -> p_pants_exchange_time4;
        $pantsAutaPlan4 -> p_pants_item = $request -> p_pants_item4;
        $pantsAutaPlan4 ->save();
        }
        if( $request-> p_pants_exchange_time5 != null && $request -> t_pants_item5 != null){
        $pantsAutaPlan5 -> client_id = $request -> client_id;
        $pantsAutaPlan5 -> p_pants_exchange_time = $request -> p_pants_exchange_time5;
        $pantsAutaPlan5 -> p_pants_item = $request -> p_pants_item5;
        $pantsAutaPlan5 ->save();
        }
        
        
        if( $request-> p_pad_exchange_time1 != null && $request -> t_pad_item1 != null){
        $pantsInnerPlan1 -> client_id = $request -> client_id;
        $pantsInnerPlan1 -> p_pad_exchange_time = $request -> p_pad_exchange_time1;
        $pantsInnerPlan1 -> p_pad_item = $request -> p_pad_item1;
        $pantsInnerPlan1 ->save();
        }
        if( $request-> p_pad_exchange_time2 != null && $request -> t_pad_item2 != null){
        $pantsInnerPlan2 -> client_id = $request -> client_id;
        $pantsInnerPlan2 -> p_pad_exchange_time = $request -> p_pad_exchange_time2;
        $pantsInnerPlan2 -> p_pad_item = $request -> p_pad_item2;
        $pantsInnerPlan2 ->save();
        }
        if( $request-> p_pad_exchange_time3 != null && $request -> t_pad_item3 != null){
        $pantsInnerPlan3 -> client_id = $request -> client_id;
        $pantsInnerPlan3 -> p_pad_exchange_time = $request -> p_pad_exchange_time3;
        $pantsInnerPlan3 -> p_pad_item = $request -> p_pad_item3;
        $pantsInnerPlan3 ->save();
        }
        if( $request-> p_pad_exchange_time4 != null && $request -> t_pad_item4 != null){
        $pantsInnerPlan4 -> client_id = $request -> client_id;
        $pantsInnerPlan4 -> p_pad_exchange_time = $request -> p_pad_exchange_time4;
        $pantsInnerPlan4 -> p_pad_item = $request -> p_pad_item4;
        $pantsInnerPlan4 ->save();
        }
        if( $request-> p_pad_exchange_time5 != null && $request -> t_pad_item5 != null){
        $pantsInnerPlan5 -> client_id = $request -> client_id;
        $pantsInnerPlan5 -> p_pad_exchange_time = $request -> p_pad_exchange_time5;
        $pantsInnerPlan5 -> p_pad_item = $request -> p_pad_item5;
        $pantsInnerPlan5 ->save();
        }
        
        return redirect('clients/list');
        
        
    }
    
}
