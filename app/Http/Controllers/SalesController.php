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
use DB;

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
        $client = Client::find($request->client_id);
        
        
        $SalesReport = new SalesReport;
        $form = $request->all();
        
        $SalesReports = SalesReport::where('client_id',$request->client_id)->where('year',$request->year)->where('month',$request->month);
         
        unset($form['_token']);
        
        
        if($SalesReports != null){
            //トランザクション処理
            DB::transaction( function() use($SalesReports,$SalesReport,$form){
            $SalesReports ->delete();
        
            $SalesReport->fill($form);
            $SalesReport->save();
            });
            
        }else{
            DB::transaction( function() use($SalesReports,$SalesReport,$form){
            $SalesReport->fill($form);
            $SalesReport->save();
            });
        }
        
        return view('sales.clientTop',['client' => $client]);
        
    }
        
    
    public function edit(Request $request){
        $clients = Client::find($request->id);
        if (empty($clients)){
            abort(404);
        }
        return view('sales.create',['clients' =>$client]);
    }
    
    public function list(Request $request){
        
        //施設検索あり
        $user_name = $request -> user_name;
        if($user_name != ''){
            $searchClients = Client::where('user_name','like','%'.$user_name.'%')->get();
            $searchClientsCount = $searchClients -> count();
            return view('clients.list',['searchClients' => $searchClients,'searchClientsCount' => $searchClientsCount]);
        
        //施設検索なし（初期アクセス）    
        }else{
            $clients = Client::all();
            return view('clients.list',['clients' => $clients]);
        }
       
    }
    
    public function result(Request $request){
        
        $salesReports = SalesReport::where('client_id',$request->id)->get();
        
        //年月選択用
        $year_ = date('Y');
        $years = array();
        for($i=$year_ ;$i >= 2018;$i--){
            array_push($years,$i);
        }
        
        $times = array();
        for($i = 1 ; $i <=24 ; $i++){
            array_push($times,$i);
        }
        
        //設定目標確認
        $clientId = $request -> id;
        $TapeAutaPlans = TapeAutaPlan::where('client_id',$clientId )->orderby('t_tape_exchange_time')->get();
        $TapeInnerPlans = TapeInnerPlan::where('client_id',$clientId )->orderby('t_pad_exchange_time')->get();
        $PantsAutaPlans = PantsAutaPlan::where('client_id',$clientId )->orderby('p_pants_exchange_time')->get();
        $PantsInnerPlans = PantsInnerPlan::where('client_id',$clientId )->orderby('p_pad_exchange_time')->get();
        
        
        $year = 0;
        $month = 0;
        
            //年月の指定あり
            if ($request->year != null && $request-> month != null){
                $year = $request ->year;
                $month = $request ->month;
                
                $salesReport = $salesReports->where('year',$year)->where('month',$month)->first();
                $client = Client::find($clientId);
                
                //該当DBなし
                if(empty($salesReport)){
                    
                    $selectYear = $year;
                    
                     //年月選択用
                    $year_ = date('Y');
                    $years = array();
                    for($i=$year_ ;$i >= 2018;$i--){
                        array_push($years,$i);
                    }
                    
                    return view('sales.result',['client' => $client,'salesReport' => $salesReport,'years'=>$years,'selectYear' => $selectYear,'month' => $month]);
                
                //該当DBあり
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
                    
                    //実績合計枚数
                    $resultTotalCount = $tape_group_count + $pants_group_count + $pad_group_count;
                    
                    //品群ごと金額
                    $tape_group_price = $salesReport->tapeM_count * $salesReport->tapeM_price + $salesReport->tapeL_count * $salesReport->tapeL_price;
                    $pants_group_price = $salesReport->pantsM_count * $salesReport->pantsM_price + $salesReport->pantsL_count * $salesReport->pantsL_price;
                    $pad_group_price = $salesReport->pad300_count * $salesReport->pad300_price + $salesReport->pad400_count * $salesReport->pad400_price 
                                       + $salesReport->pad600_count * $salesReport->pad600_price + $salesReport->pad800_count * $salesReport->pad800_price 
                                       + $salesReport->pad1000_count * $salesReport->pad1000_price + $salesReport->pad1200_count * $salesReport->pad1200_price;
                    
                    //実績合計金額
                    $resultTotalPrice = $tape_group_price + $pants_group_price + $pad_group_price;
                    
                    //パッドごと金額
                    $pad300_price = $salesReport->pad300_price;
                    $pad400_price = $salesReport->pad400_price;
                    $pad600_price = $salesReport->pad600_price;
                    $pad800_price = $salesReport->pad800_price;
                    $pad1000_price = $salesReport->pad1000_price;
                    $pad1200_price = $salesReport->pad1200_price;
                    
                    
                    //設定目標月間試算
                    //日数（月間）
                    $lastday = date('t',strtotime($salesReport -> month));
                    
                    //枚数
                    //テープ使用者
                     //一人あたりテープ使用枚数
                    $planTapeCount = $TapeAutaPlans->count();
                    //一人あたりテープ使用者パッド使用枚数
                    $planTapeInnerCount = $TapeInnerPlans->count();
                    //テープ使用者人数
                    $tapeUserCount = $salesReport -> tapem_user_count + $salesReport -> tapel_user_count;
                    //テープ使用者トータル使用枚数（月間）
                    $planTapeTotalCount = $planTapeCount * $tapeUserCount * $lastday;
                    
                    //パンツ使用者
                    //一人あたりパンツ使用枚数
                    $planPantsCount = $PantsAutaPlans -> count();
                    //一人あたりパンツ使用者パッド���用枚数
                    $planPantsInnerCount = $PantsInnerPlans ->count();
                    //パンツ使用者人数
                    $pantsUserCount = $salesReport -> pantsm_user_count + $salesReport ->pantsl_user_count;
                    //パンツ使用者トータル使用枚数（月間）
                    $planPantsTotalCount = $planPantsCount * $pantsUserCount *$lastday;
                    
                    //パッド合計
                    $planPadTotalCount = $planTapeInnerCount * $tapeUserCount * $lastday + $planPantsInnerCount * $pantsUserCount *$lastday;
                    
                    //テープ・パンツ・パッド合計使用枚数
                    $planTotalCount = $planTapeTotalCount + $planPantsTotalCount + $planPadTotalCount;
                    
                    //金額
                    //テープ
                    $planTapeMPrice = $planTapeCount * $salesReport -> tapem_user_count * $salesReport->tapeM_price * $lastday;
                    $planTapeLPrice = $planTapeCount * $salesReport -> tapel_user_count * $salesReport ->tapeL_price * $lastday;
                    $planTotalTapePrice = $planTapeMPrice + $planTapeLPrice;
                    
                    //パンツ
                    $planPantsMPrice = $planPantsCount * $salesReport -> pantsm_user_count * $salesReport -> pantsM_price * $lastday;
                    $planPantsLPrice = $planPantsCount * $salesReport -> pantsl_user_count * $salesReport -> pantsL_price * $lastday;
                    $planTotalPantsPrice = $planPantsMPrice + $planPantsLPrice;
                    
                    //テープ使用者パッド金額
                    $planTape300Count = $TapeInnerPlans -> where('t_pad_item','パッド300') ->count();
                    $planTape300Price = $planTape300Count * $tapeUserCount * $salesReport -> pad300_price * $lastday;
                    
                    $planTape400Count = $TapeInnerPlans -> where('t_pad_item','パッド400') -> count();
                    $planTape400Price = $planTape400Count * $tapeUserCount * $salesReport -> pad400_price * $lastday;
                    
                    $planTape600Count = $TapeInnerPlans -> where('t_pad_item','パッド600') -> count();
                    $planTape600Price = $planTape600Count * $tapeUserCount * $salesReport -> pad600_price * $lastday;
                    
                    $planTape800Count = $TapeInnerPlans -> where('t_pad_item','パッド800') ->count();
                    $planTape800Price = $planTape800Count * $tapeUserCount * $salesReport -> pad800_price * $lastday;
                    
                    $planTape1000Count = $TapeInnerPlans -> where('t_pad_item','パッド1000') -> count();
                    $planTape1000Price = $planTape1000Count * $tapeUserCount * $salesReport -> pad1000_price * $lastday;
                    
                    $planTape1200Count = $TapeInnerPlans -> where('t_pad_item','パッド1200') ->count();
                    $planTape1200Price = $planTape1200Count *$tapeUserCount *$salesReport -> pad1200_price *$lastday;
                    
                    //テープ使用者パッド金額合計
                    $planTotalTapePadPrice = $planTape300Price + $planTape400Price + $planTape600Price + $planTape800Price + $planTape1000Price + $planTape1200Price;
                    
                    //パンツ使用者パッド金額
                    $planPants300Count = $PantsInnerPlans -> where('p_pad_item','パッド300') ->count();
                    $planPants300Price = $planPants300Count * $pantsUserCount * $salesReport -> pad300_price * $lastday;
                    
                    $planPants400Count = $PantsAutaPlans -> where('p_pad_item','パッド400') -> count();
                    $planPants400Price = $planPants400Count * $pantsUserCount * $salesReport -> pad400_price * $lastday;
                    
                    $planPants600Count = $PantsInnerPlans -> where('p_pad_item','パッド600') -> count();
                    $planPants600Price = $planPants600Count * $pantsUserCount * $salesReport -> pad600_price * $lastday;
                    
                    $planPants800Count = $PantsInnerPlans -> where('p_pad_item','パッド800') ->count();
                    $planPants800Price = $planPants800Count * $pantsUserCount * $salesReport -> pad800_price * $lastday;
                    
                    $planPants1000Count = $PantsInnerPlans -> where('p_pad_item','パッド1000') -> count();
                    $planPants1000Price = $planPants1000Count * $pantsUserCount * $salesReport -> pad1000_price * $lastday;
                    
                    $planPants1200Count = $PantsInnerPlans -> where('p_pad_item','パッド1200') ->count();
                    $planPants1200Price = $planPants1200Count *$pantsUserCount *$salesReport -> pad1200_price *$lastday;
                    
                    //パンツ使用者パッド金額合計
                    $planTotalPantsPadPrice = $planPants300Price + $planPants400Price + $planPants600Price + $planPants800Price + $planPants1000Price + $planPants1200Price;
                    
                    //テープ・パンツ使用者パッド合計金額
                    $planTotalPadPrice = $planTotalTapePadPrice + $planTotalPantsPadPrice;
                    
                    //テープ・パンツ・パッド合計金額
                    $planTotalPrice = $planTotalTapePrice + $planTotalPantsPrice + $planTotalPadPrice;
                    
                    //年間実績
                    //今年
                    $thisYearResult = SalesReport::where('client_id',$request->id) -> where('year',$year_) ->get();
                    
                    
                    //枚数
                    //1月
                    $thisYearJanuary = $thisYearResult ->where('month','1')->first();
                    if(isset($thisYearJanuary)){
                        $thisYearJanuaryCount = $thisYearJanuary -> tapeM_count + $thisYearJanuary -> tapeL_count + $thisYearJanuary -> pantsM_count + $thisYearJanuary -> pantsL_count +
                                                $thisYearJanuary -> pad300_count + $thisYearJanuary -> pad400_count + $thisYearJanuary -> pad600_count + $thisYearJanuary -> pad800_count + 
                                                $thisYearJanuary -> pad1000_count + $thisYearJanuary -> pad1200_count;
                        
                    }else{
                        $thisYearJanuaryCount = 0;
                    }
                    
                   
                    //2月
                    $thisYearFebruary = $thisYearResult ->where('month','2') ->first();
                    if(isset($thisYearFebruary)){
                        $thisYearFebruaryCount = $thisYearFebruary -> tapeM_count + $thisYearFebruary -> tapeL_count + $thisYearFebruary -> pantsM_count + $thisYearFebruary -> pantsL_count +
                                            $thisYearFebruary -> pad300_count + $thisYearFebruary -> pad400_count + $thisYearFebruary -> pad600_count + $thisYearFebruary -> pad800_count + 
                                            $thisYearFebruary -> pad1000_count + $thisYearFebruary -> pad1200_count;
             
                    }else{
                        $thisYearFebruaryCount = 0;
                    }
                    
                    
                    //3月
                    $thisYearMarch = $thisYearResult ->where('month','3') ->first();
                    if(isset($thisYearMarch)){
                        $thisYearMarchCount = $thisYearMarch -> tapeM_count + $thisYearMarch -> tapeL_count + $thisYearMarch -> pantsM_count + $thisYearMarch -> pantsL_count +
                                            $thisYearMarch -> pad300_count + $thisYearMarch -> pad400_count + $thisYearMarch -> pad600_count + $thisYearMarch -> pad800_count + 
                                            $thisYearMarch-> pad1000_count + $thisYearMarch -> pad1200_count;
             
                    }else{
                        $thisYearMarchCount = 0;
                    }
                    
                    
                    //4月
                    $thisYearApril = $thisYearResult ->where('month','4') ->first();
                    if(isset($thisYearApril)){
                        $thisYearAprilCount = $thisYearApril -> tapeM_count + $thisYearApril -> tapeL_count + $thisYearApril -> pantsM_count + $thisYearApril -> pantsL_count +
                                            $thisYearApril -> pad300_count + $thisYearApril -> pad400_count + $thisYearApril -> pad600_count + $thisYearApril -> pad800_count + 
                                            $thisYearApril-> pad1000_count + $thisYearApril -> pad1200_count;
             
                    }else{
                        $thisYearAprilCount = 0;
                    }
                    
                    //5月
                    $thisYearMay = $thisYearResult ->where('month','5') ->first();
                    if(isset($thisYearMay)){
                        $thisYearMayCount = $thisYearMay -> tapeM_count + $thisYearMay -> tapeL_count + $thisYearMay -> pantsM_count + $thisYearMay -> pantsL_count +
                                            $thisYearMay -> pad300_count + $thisYearMay -> pad400_count + $thisYearMay -> pad600_count + $thisYearMay -> pad800_count + 
                                            $thisYearMay-> pad1000_count + $thisYearMay -> pad1200_count;
             
                    }else{
                        $thisYearMayCount = 0;
                    }
                    
                    //6月
                    $thisYearJune = $thisYearResult ->where('month','6') ->first();
                    if(isset($thisYearJune)){
                        $thisYearJuneCount = $thisYearJune -> tapeM_count + $thisYearJune -> tapeL_count + $thisYearJune -> pantsM_count + $thisYearJune -> pantsL_count +
                                            $thisYearJune -> pad300_count + $thisYearJune -> pad400_count + $thisYearJune -> pad600_count + $thisYearJune -> pad800_count + 
                                            $thisYearJune-> pad1000_count + $thisYearJune -> pad1200_count;
             
                    }else{
                        $thisYearJuneCount = 0;
                    }
                    
                    //7月
                    $thisYearJuly = $thisYearResult ->where('month','7') ->first();
                    if(isset($thisYearJuly)){
                        $thisYearJulyCount = $thisYearJuly -> tapeM_count + $thisYearJuly -> tapeL_count + $thisYearJuly -> pantsM_count + $thisYearJuly -> pantsL_count +
                                            $thisYearJuly -> pad300_count + $thisYearJuly -> pad400_count + $thisYearJuly -> pad600_count + $thisYearJuly -> pad800_count + 
                                            $thisYearJuly-> pad1000_count + $thisYearJuly -> pad1200_count;
             
                    }else{
                        $thisYearJulyCount = 0;
                    }
                    
                    //8月
                    $thisYearAugust = $thisYearResult ->where('month','8') ->first();
                    if(isset($thisYearAugust)){
                        $thisYearAugustCount = $thisYearAugust -> tapeM_count + $thisYearAugust -> tapeL_count + $thisYearAugust -> pantsM_count + $thisYearAugust -> pantsL_count +
                                            $thisYearAugust -> pad300_count + $thisYearAugust -> pad400_count + $thisYearAugust -> pad600_count + $thisYearAugust -> pad800_count + 
                                            $thisYearAugust-> pad1000_count + $thisYearAugust -> pad1200_count;
             
                    }else{
                        $thisYearAugustCount = 0;
                    }
                    
                    //9月
                    $thisYearSeptember = $thisYearResult ->where('month','9') ->first();
                    if(isset($thisYearSeptember)){
                        $thisYearSeptemberCount = $thisYearSeptember -> tapeM_count + $thisYearSeptember -> tapeL_count + $thisYearSeptember -> pantsM_count + $thisYearSeptember -> pantsL_count +
                                            $thisYearSeptember -> pad300_count + $thisYearSeptember -> pad400_count + $thisYearSeptember -> pad600_count + $thisYearSeptember -> pad800_count + 
                                            $thisYearSeptember-> pad1000_count + $thisYearSeptember -> pad1200_count;
             
                    }else{
                        $thisYearSeptemberCount = 0;
                    }
                    
                    //10月
                    $thisYearOctober = $thisYearResult ->where('month','10') ->first();
                    if(isset($thisYearOctober)){
                        $thisYearOctoberCount = $thisYearOctober -> tapeM_count + $thisYearOctober -> tapeL_count + $thisYearOctober -> pantsM_count + $thisYearOctober -> pantsL_count +
                                            $thisYearOctober -> pad300_count + $thisYearOctober -> pad400_count + $thisYearOctober -> pad600_count + $thisYearOctober -> pad800_count + 
                                            $thisYearOctober-> pad1000_count + $thisYearOctober -> pad1200_count;
             
                    }else{
                        $thisYearOctoberCount = 0;
                    }
                    
                    //11月
                    $thisYearNovember = $thisYearResult ->where('month','11') ->first();
                    if(isset($thisYearNovember)){
                        $thisYearNovemberCount = $thisYearNovember -> tapeM_count + $thisYearNovember -> tapeL_count + $thisYearNovember -> pantsM_count + $thisYearNovember -> pantsL_count +
                                            $thisYearNovember -> pad300_count + $thisYearNovember -> pad400_count + $thisYearNovember -> pad600_count + $thisYearNovember -> pad800_count + 
                                            $thisYearNovember -> pad1000_count + $thisYearNovember -> pad1200_count;
             
                    }else{
                        $thisYearNovemberCount = 0;
                    }
                    
                    //12月
                    $thisYearDecember = $thisYearResult ->where('month','12') ->first();
                    if(isset($thisYearDecember)){
                        $thisYearDecemberCount = $thisYearDecember -> tapeM_count + $thisYearDecember -> tapeL_count + $thisYearDecember -> pantsM_count + $thisYearDecember -> pantsL_count +
                                            $thisYearDecember -> pad300_count + $thisYearDecember -> pad400_count + $thisYearDecember -> pad600_count + $thisYearDecember -> pad800_count + 
                                            $thisYearDecember -> pad1000_count + $thisYearDecember -> pad1200_count;
             
                    }else{
                        $thisYearDecemberCount = 0;
                    }
                    
                    //金額
                    //1月
                    $thisYearJanuary = $thisYearResult ->where('month','1')->first();
                    if(isset($thisYearJanuary)){
                        $thisYearJanuaryPrice = $thisYearJanuary -> tapeM_price * $thisYearJanuary -> tapeM_count+ $thisYearJanuary -> tapeL_price * $thisYearJanuary -> tapeL_count + 
                                                $thisYearJanuary -> pantsM_price * $thisYearJanuary -> pantsM_count + $thisYearJanuary -> pantsL_price * $thisYearJanuary -> pantsL_count +
                                                $thisYearJanuary -> pad300_price  * $thisYearJanuary -> pad300_count + $thisYearJanuary -> pad400_price * $thisYearJanuary -> pad400_count+ 
                                                $thisYearJanuary -> pad600_price * $thisYearJanuary -> pad600_count + $thisYearJanuary -> pad800_price * $thisYearJanuary -> pad800_count + 
                                                $thisYearJanuary -> pad1000_price * $thisYearJanuary -> pad1000_count + $thisYearJanuary -> pad1200_price * $thisYearJanuary -> pad1200_count;
                        
                    }else{
                        $thisYearJanuaryPrice = 0;
                    }
                    
                   
                    //2月
                    $thisYearFebruary = $thisYearResult ->where('month','2') ->first();
                    if(isset($thisYearFebruary)){
                        $thisYearFebruaryPrice = $thisYearFebruary -> tapeM_price * $thisYearFebruary -> tapeM_count+ $thisYearFebruary -> tapeL_price * $thisYearFebruary -> tapeL_count + 
                                                $thisYearFebruary -> pantsM_price * $thisYearFebruary -> pantsM_count + $thisYearFebruary -> pantsL_price * $thisYearFebruary -> pantsL_count +
                                                $thisYearFebruary -> pad300_price  * $thisYearFebruary -> pad300_count + $thisYearFebruary -> pad400_price * $thisYearFebruary -> pad400_count+ 
                                                $thisYearFebruary -> pad600_price * $thisYearFebruary -> pad600_count + $thisYearFebruary -> pad800_price * $thisYearv -> pad800_count + 
                                                $thisYearFebruary -> pad1000_price * $thisYearFebruary -> pad1000_count + $thisYearFebruary -> pad1200_price * $thisYearFebruary -> pad1200_count;
             
                    }else{
                        $thisYearFebruaryPrice = 0;
                    }
                    
                    
                    //3月
                    $thisYearMarch = $thisYearResult ->where('month','3') ->first();
                    if(isset($thisYearMarch)){
                        $thisYearMarchPrice =$thisYearMarch -> tapeM_price * $thisYearMarch -> tapeM_count+ $thisYearMarch -> tapeL_price * $thisYearMarch -> tapeL_count + 
                                                $thisYearMarch -> pantsM_price * $thisYearMarch -> pantsM_count + $thisYearMarch -> pantsL_price * $thisYearMarch -> pantsL_count +
                                                $thisYearMarch -> pad300_price  * $thisYearMarch -> pad300_count + $thisYearMarch -> pad400_price * $thisYearMarch -> pad400_count+ 
                                                $thisYearMarch -> pad600_price * $thisYearMarch -> pad600_count + $thisYearMarch -> pad800_price * $thisYearMarch -> pad800_count + 
                                                $thisYearMarch -> pad1000_price * $thisYearMarch -> pad1000_count + $thisYearMarch -> pad1200_price * $thisYearMarch -> pad1200_count;
             
                    }else{
                        $thisYearMarchPrice = 0;
                    }
                    
                    
                    //4月
                    $thisYearApril = $thisYearResult ->where('month','4') ->first();
                    if(isset($thisYearApril)){
                        $thisYearAprilPrice = $thisYearApril -> tapeM_price * $thisYearApril -> tapeM_count+ $thisYearApril -> tapeL_price * $thisYearApril -> tapeL_count + 
                                                $thisYearApril -> pantsM_price * $thisYearApril -> pantsM_count + $thisYearApril -> pantsL_price * $thisYearApril -> pantsL_count +
                                                $thisYearApril -> pad300_price  * $thisYearApril -> pad300_count + $thisYearApril -> pad400_price * $thisYearApril -> pad400_count+ 
                                                $thisYearApril -> pad600_price * $thisYearApril -> pad600_count + $thisYearApril -> pad800_price * $thisYearApril -> pad800_count + 
                                                $thisYearApril -> pad1000_price * $thisYearApril -> pad1000_count + $thisYearApril -> pad1200_price * $thisYearApril -> pad1200_count;
                    }else{
                        $thisYearAprilPrice = 0;
                    }
                    
                    //5月
                    $thisYearMay = $thisYearResult ->where('month','5') ->first();
                    if(isset($thisYearMay)){
                        $thisYearMayPrice = $thisYearMay -> tapeM_price * $thisYearMay -> tapeM_count+ $thisYearMay -> tapeL_price * $thisYearMay -> tapeL_count + 
                                            $thisYearMay -> pantsM_price * $thisYearMay -> pantsM_count + $thisYearMay -> pantsL_price * $thisYearMay -> pantsL_count +
                                            $thisYearMay -> pad300_price  * $thisYearMay -> pad300_count + $thisYearMay -> pad400_price * $thisYearMay -> pad400_count+ 
                                            $thisYearMay -> pad600_price * $thisYearMay -> pad600_count + $thisYearMay -> pad800_price * $thisYearMay -> pad800_count + 
                                            $thisYearMay -> pad1000_price * $thisYearMay -> pad1000_count + $thisYearMay -> pad1200_price * $thisYearMay -> pad1200_count;
             
                    }else{
                        $thisYearMayPrice = 0;
                    }
                    
                    //6月
                    $thisYearJune = $thisYearResult ->where('month','6') ->first();
                    if(isset($thisYearJune)){
                        $thisYearJunePrice = $thisYearJune -> tapeM_price * $thisYearJune -> tapeM_count+ $thisYearJune -> tapeL_price * $thisYearJune -> tapeL_count + 
                                                $thisYearJune -> pantsM_price * $thisYearJune -> pantsM_count + $thisYearJune -> pantsL_price * $thisYearJune -> pantsL_count +
                                                $thisYearJune -> pad300_price  * $thisYearJune -> pad300_count + $thisYearJune -> pad400_price * $thisYearJune -> pad400_count+ 
                                                $thisYearJune -> pad600_price * $thisYearJune -> pad600_count + $thisYearJune -> pad800_price * $thisYearJune -> pad800_count + 
                                                $thisYearJune -> pad1000_price * $thisYearJune -> pad1000_count + $thisYearJune -> pad1200_price * $thisYearJune -> pad1200_count;
             
                    }else{
                        $thisYearJunePrice = 0;
                    }
                    
                    //7月
                    $thisYearJuly = $thisYearResult ->where('month','7') ->first();
                    if(isset($thisYearJuly)){
                        $thisYearJulyPrice = $thisYearJuly -> tapeM_price * $thisYearJuly -> tapeM_count+ $thisYearJuly -> tapeL_price * $thisYearJuly -> tapeL_count + 
                                            $thisYearJuly -> pantsM_price * $thisYearJuly -> pantsM_count + $thisYearJuly -> pantsL_price * $thisYearJuly -> pantsL_count +
                                            $thisYearJuly -> pad300_price  * $thisYearJuly -> pad300_count + $thisYearJuly -> pad400_price * $thisYearJuly -> pad400_count+ 
                                            $thisYearJuly -> pad600_price * $thisYearJuly -> pad600_count + $thisYearJuly -> pad800_price * $thisYearJuly -> pad800_count + 
                                            $thisYearJuly -> pad1000_price * $thisYearJuly -> pad1000_count + $thisYearJuly -> pad1200_price * $thisYearJuly -> pad1200_count;
             
                    }else{
                        $thisYearJulyPrice = 0;
                    }
                    
                    //8月
                    $thisYearAugust = $thisYearResult ->where('month','8') ->first();
                    if(isset($thisYearAugust)){
                        $thisYearAugustPrice = $thisYearAugust -> tapeM_price * $thisYearAugust -> tapeM_count+ $thisYearAugust -> tapeL_price * $thisYearAugust -> tapeL_count + 
                                                $thisYearAugust -> pantsM_price * $thisYearAugust -> pantsM_count + $thisYearAugust -> pantsL_price * $thisYearAugust -> pantsL_count +
                                                $thisYearAugust -> pad300_price  * $thisYearAugust -> pad300_count + $thisYearAugust -> pad400_price * $thisYearAugust -> pad400_count+ 
                                                $thisYearAugust -> pad600_price * $thisYearAugust -> pad600_count + $thisYearAugust -> pad800_price * $thisYearAugust -> pad800_count + 
                                                $thisYearAugust -> pad1000_price * $thisYearAugust -> pad1000_count + $thisYearAugust -> pad1200_price * $thisYearAugust -> pad1200_count;
             
                    }else{
                        $thisYearAugustPrice = 0;
                    }
                    
                    //9月
                    $thisYearSeptember = $thisYearResult ->where('month','9') ->first();
                    if(isset($thisYearSeptember)){
                        $thisYearSeptemberPrice = $thisYearSeptember -> tapeM_price * $thisYearSeptember -> tapeM_count+ $thisYearSeptember -> tapeL_price * $thisYearSeptember -> tapeL_count + 
                                                $thisYearSeptember -> pantsM_price * $thisYearSeptember -> pantsM_count + $thisYearSeptember -> pantsL_price * $thisYearSeptember -> pantsL_count +
                                                $thisYearSeptember -> pad300_price  * $thisYearSeptember -> pad300_count + $thisYearSeptember -> pad400_price * $thisYearSeptember -> pad400_count+ 
                                                $thisYearSeptember -> pad600_price * $thisYearSeptember -> pad600_count + $thisYearSeptember -> pad800_price * $thisYearSeptember -> pad800_count + 
                                                $thisYearSeptember -> pad1000_price * $thisYearSeptember -> pad1000_count + $thisYearSeptember -> pad1200_price * $thisYearSeptember -> pad1200_count;
             
                    }else{
                        $thisYearSeptemberPrice = 0;
                    }
                    
                    //10月
                    $thisYearOctober = $thisYearResult ->where('month','10') ->first();
                    if(isset($thisYearOctober)){
                        $thisYearOctoberPrice = $thisYearOctober -> tapeM_price * $thisYearOctober -> tapeM_count+ $thisYearOctober -> tapeL_price * $thisYearOctober -> tapeL_count + 
                                                $thisYearOctober -> pantsM_price * $thisYearOctober -> pantsM_count + $thisYearOctober -> pantsL_price * $thisYearOctober -> pantsL_count +
                                                $thisYearOctober -> pad300_price  * $thisYearOctober -> pad300_count + $thisYearOctober -> pad400_price * $thisYearOctober -> pad400_count+ 
                                                $thisYearOctober -> pad600_price * $thisYearOctober -> pad600_count + $thisYearOctober -> pad800_price * $thisYearOctober -> pad800_count + 
                                                $thisYearOctober -> pad1000_price * $thisYearOctober -> pad1000_count + $thisYearOctober -> pad1200_price * $thisYearOctober -> pad1200_count;
             
                    }else{
                        $thisYearOctoberPrice = 0;
                    }
                    
                    //11月
                    $thisYearNovember = $thisYearResult ->where('month','11') ->first();
                    if(isset($thisYearNovember)){
                        $thisYearNovemberPrice = $thisYearNovember -> tapeM_price * $thisYearNovember -> tapeM_count+ $thisYearNovember -> tapeL_price * $thisYearNovember -> tapeL_count + 
                                                $thisYearNovember -> pantsM_price * $thisYearNovember -> pantsM_count + $thisYearNovember -> pantsL_price * $thisYearNovember -> pantsL_count +
                                                $thisYearNovember -> pad300_price  * $thisYearNovember -> pad300_count + $thisYearNovember -> pad400_price * $thisYearNovember -> pad400_count+ 
                                                $thisYearNovember -> pad600_price * $thisYearNovember -> pad600_count + $thisYearNovember -> pad800_price * $thisYearNovember -> pad800_count + 
                                                $thisYearNovember -> pad1000_price * $thisYearNovember -> pad1000_count + $thisYearNovember -> pad1200_price * $thisYearNovember -> pad1200_count;
             
                    }else{
                        $thisYearNovemberPrice = 0;
                    }
                    
                    //12月
                    $thisYearDecember = $thisYearResult ->where('month','12') ->first();
                    if(isset($thisYearDecember)){
                        $thisYearDecemberPrice = $thisYearDecember -> tapeM_price * $thisYearDecember -> tapeM_count+ $thisYearDecember -> tapeL_price * $thisYearDecember -> tapeL_count + 
                                                $thisYearDecember -> pantsM_price * $thisYearDecember -> pantsM_count + $thisYearDecember -> pantsL_price * $thisYearDecember -> pantsL_count +
                                                $thisYearDecember -> pad300_price  * $thisYearDecember -> pad300_count + $thisYearDecember -> pad400_price * $thisYearDecember -> pad400_count+ 
                                                $thisYearDecember -> pad600_price * $thisYearDecember -> pad600_count + $thisYearDecember -> pad800_price * $thisYearDecember -> pad800_count + 
                                                $thisYearDecember -> pad1000_price * $thisYearDecember -> pad1000_count + $thisYearDecember -> pad1200_price * $thisYearDecember -> pad1200_count;
             
                    }else{
                        $thisYearDecemberPrice = 0;
                    }
                    
                    
                    //昨年
                    $lastYear = $year_ - 1;
                    $lastYearResult = SalesReport::where('client_id',$request->id) -> where('year',$lastYear) ->get();
                    
                    //枚数
                    //1月
                    $lastYearJanuary = $lastYearResult ->where('month','1')->first();
                    if(isset($lastYearJanuary)){
                        $lastYearJanuaryCount = $lastYearJanuary -> tapeM_count + $lastYearJanuary -> tapeL_count + $lastYearJanuary -> pantsM_count + $lastYearJanuary -> pantsL_count +
                                                $lastYearJanuary -> pad300_count + $lastYearJanuary -> pad400_count + $lastYearJanuary -> pad600_count + $lastYearJanuary -> pad800_count + 
                                                $lastYearJanuary -> pad1000_count + $lastYearJanuary -> pad1200_count;
                        
                    }else{
                        $lastYearJanuaryCount = 0;
                    }
                    
                   
                    //2月
                    $lastYearFebruary = $lastYearResult ->where('month','2') ->first();
                    if(isset($lastYearFebruary)){
                        $lastYearFebruaryCount = $lastYearFebruary -> tapeM_count + $lastYearFebruary -> tapeL_count + $lastYearFebruary -> pantsM_count + $lastYearFebruary -> pantsL_count +
                                            $lastYearFebruary -> pad300_count + $lastYearFebruary -> pad400_count + $lastYearFebruary -> pad600_count + $lastYearFebruary -> pad800_count + 
                                            $lastYearFebruary -> pad1000_count + $lastYearFebruary -> pad1200_count;
             
                    }else{
                        $lastYearFebruaryCount = 0;
                    }
                    
                    
                    //3月
                    $lastYearMarch = $lastYearResult ->where('month','3') ->first();
                    if(isset($lastYearMarch)){
                        $lastYearMarchCount = $lastYearMarch -> tapeM_count + $lastYearMarch -> tapeL_count + $lastYearMarch -> pantsM_count + $lastYearMarch -> pantsL_count +
                                            $lastYearMarch -> pad300_count + $lastYearMarch -> pad400_count + $lastYearMarch -> pad600_count + $lastYearMarch -> pad800_count + 
                                            $lastYearMarch-> pad1000_count + $lastYearMarch -> pad1200_count;
             
                    }else{
                        $lastYearMarchCount = 0;
                    }
                    
                    
                    //4月
                    $lastYearApril = $lastYearResult ->where('month','4') ->first();
                    if(isset($lastYearApril)){
                        $lastYearAprilCount = $lastYearApril -> tapeM_count + $lastYearApril -> tapeL_count + $lastYearApril -> pantsM_count + $lastYearApril -> pantsL_count +
                                            $lastYearApril -> pad300_count + $lastYearApril -> pad400_count + $lastYearApril -> pad600_count + $lastYearApril -> pad800_count + 
                                            $lastYearApril-> pad1000_count + $lastYearApril -> pad1200_count;
             
                    }else{
                        $lastYearAprilCount = 0;
                    }
                    
                    //5月
                    $lastYearMay = $lastYearResult ->where('month','5') ->first();
                    if(isset($lastYearMay)){
                        $lastYearMayCount = $lastYearMay -> tapeM_count + $lastYearMay -> tapeL_count + $lastYearMay -> pantsM_count + $lastYearMay -> pantsL_count +
                                            $lastYearMay -> pad300_count + $lastYearMay -> pad400_count + $lastYearMay -> pad600_count + $lastYearMay -> pad800_count + 
                                            $lastYearMay-> pad1000_count + $lastYearMay -> pad1200_count;
             
                    }else{
                        $lastYearMayCount = 0;
                    }
                    
                    //6月
                    $lastYearJune = $lastYearResult ->where('month','6') ->first();
                    if(isset($lastYearJune)){
                        $lastYearJuneCount = $lastYearJune -> tapeM_count + $lastYearJune -> tapeL_count + $lastYearJune -> pantsM_count + $lastYearJune -> pantsL_count +
                                            $lastYearJune -> pad300_count + $lastYearJune -> pad400_count + $lastYearJune -> pad600_count + $lastYearJune -> pad800_count + 
                                            $lastYearJune-> pad1000_count + $lastYearJune -> pad1200_count;
             
                    }else{
                        $lastYearJuneCount = 0;
                    }
                    
                    //7月
                    $lastYearJuly = $lastYearResult ->where('month','7') ->first();
                    if(isset($lastYearJuly)){
                        $lastYearJulyCount = $lastYearJuly -> tapeM_count + $lastYearJuly -> tapeL_count + $lastYearJuly -> pantsM_count + $lastYearJuly -> pantsL_count +
                                            $lastYearJuly -> pad300_count + $lastYearJuly -> pad400_count + $lastYearJuly -> pad600_count + $lastYearJuly -> pad800_count + 
                                            $lastYearJuly-> pad1000_count + $lastYearJuly -> pad1200_count;
             
                    }else{
                        $lastYearJulyCount = 0;
                    }
                    
                    //8月
                    $lastYearAugust = $lastYearResult ->where('month','8') ->first();
                    if(isset($lastYearAugust)){
                        $lastYearAugustCount = $lastYearAugust -> tapeM_count + $lastYearAugust -> tapeL_count + $lastYearAugust -> pantsM_count + $lastYearAugust -> pantsL_count +
                                            $lastYearAugust -> pad300_count + $lastYearAugust -> pad400_count + $lastYearAugust -> pad600_count + $lastYearAugust -> pad800_count + 
                                            $lastYearAugust-> pad1000_count + $lastYearAugust -> pad1200_count;
             
                    }else{
                        $lastYearAugustCount = 0;
                    }
                    
                    //9月
                    $lastYearSeptember = $lastYearResult ->where('month','9') ->first();
                    if(isset($lastYearSeptember)){
                        $lastYearSeptemberCount = $lastYearSeptember -> tapeM_count + $lastYearSeptember -> tapeL_count + $lastYearSeptember -> pantsM_count + $lastYearSeptember -> pantsL_count +
                                            $lastYearSeptember -> pad300_count + $lastYearSeptember -> pad400_count + $lastYearSeptember -> pad600_count + $lastYearSeptember -> pad800_count + 
                                            $lastYearSeptember-> pad1000_count + $lastYearSeptember -> pad1200_count;
             
                    }else{
                        $lastYearSeptemberCount = 0;
                    }
                    
                    //10月
                    $lastYearOctober = $lastYearResult ->where('month','10') ->first();
                    if(isset($lastYearOctober)){
                        $lastYearOctoberCount = $lastYearOctober -> tapeM_count + $lastYearOctober -> tapeL_count + $lastYearOctober -> pantsM_count + $lastYearOctober -> pantsL_count +
                                            $lastYearOctober -> pad300_count + $lastYearOctober -> pad400_count + $lastYearOctober -> pad600_count + $lastYearOctober -> pad800_count + 
                                            $lastYearOctober-> pad1000_count + $lastYearOctober -> pad1200_count;
             
                    }else{
                        $lastYearOctoberCount = 0;
                    }
                    
                    //11月
                    $lastYearNovember = $lastYearResult ->where('month','11') ->first();
                    if(isset($lastYearNovember)){
                        $lastYearNovemberCount = $lastYearNovember -> tapeM_count + $lastYearNovember -> tapeL_count + $lastYearNovember -> pantsM_count + $lastYearNovember -> pantsL_count +
                                            $lastYearNovember -> pad300_count + $lastYearNovember -> pad400_count + $lastYearNovember -> pad600_count + $lastYearNovember -> pad800_count + 
                                            $lastYearNovember -> pad1000_count + $lastYearNovember -> pad1200_count;
             
                    }else{
                        $lastYearNovemberCount = 0;
                    }
                    
                    //12月
                    $lastYearDecember = $lastYearResult ->where('month','12') ->first();
                    if(isset($lastYearDecember)){
                        $lastYearDecemberCount = $lastYearDecember -> tapeM_count + $lastYearDecember -> tapeL_count + $lastYearDecember -> pantsM_count + $lastYearDecember -> pantsL_count +
                                            $lastYearDecember -> pad300_count + $lastYearDecember -> pad400_count + $lastYearDecember -> pad600_count + $lastYearDecember -> pad800_count + 
                                            $lastYearDecember -> pad1000_count + $lastYearDecember -> pad1200_count;
             
                    }else{
                        $lastYearDecemberCount = 0;
                    }
                    
                    //金額
                    //1月
                    $lastYearJanuary = $lastYearResult ->where('month','1')->first();
                    if(isset($lastYearJanuary)){
                        $lastYearJanuaryPrice = $lastYearJanuary -> tapeM_price * $lastYearJanuary -> tapeM_count+ $lastYearJanuary -> tapeL_price * $lastYearJanuary -> tapeL_count + 
                                                $lastYearJanuary -> pantsM_price * $lastYearJanuary -> pantsM_count + $lastYearJanuary -> pantsL_price * $lastYearJanuary -> pantsL_count +
                                                $lastYearJanuary -> pad300_price  * $lastYearJanuary -> pad300_count + $lastYearJanuary -> pad400_price * $lastYearJanuary -> pad400_count+ 
                                                $lastYearJanuary -> pad600_price * $lastYearJanuary -> pad600_count + $lastYearJanuary -> pad800_price * $lastYearJanuary -> pad800_count + 
                                                $lastYearJanuary -> pad1000_price * $lastYearJanuary -> pad1000_count + $lastYearJanuary -> pad1200_price * $lastYearJanuary -> pad1200_count;
                        
                    }else{
                        $lastYearJanuaryPrice = 0;
                    }
                    
                   
                    //2月
                    $lastYearFebruary = $lastYearResult ->where('month','2') ->first();
                    if(isset($lastYearFebruary)){
                        $lastYearFebruaryPrice = $lastYearFebruary -> tapeM_price * $lastYearFebruary -> tapeM_count+ $lastYearFebruary -> tapeL_price * $lastYearFebruary -> tapeL_count + 
                                                $lastYearFebruary -> pantsM_price * $lastYearFebruary -> pantsM_count + $lastYearFebruary -> pantsL_price * $lastYearFebruary -> pantsL_count +
                                                $lastYearFebruary -> pad300_price  * $lastYearFebruary -> pad300_count + $lastYearFebruary -> pad400_price * $lastYearFebruary -> pad400_count+ 
                                                $lastYearFebruary -> pad600_price * $lastYearFebruary -> pad600_count + $lastYearFebruary -> pad800_price * $lastYearFebruary -> pad800_count + 
                                                $lastYearFebruary -> pad1000_price * $lastYearFebruary -> pad1000_count + $lastYearFebruary -> pad1200_price * $lastYearFebruary -> pad1200_count;
             
                    }else{
                        $lastYearFebruaryPrice = 0;
                    }
                    
                    
                    //3月
                    $lastYearMarch = $lastYearResult ->where('month','3') ->first();
                    if(isset($lastYearMarch)){
                        $lastYearMarchPrice = $lastYearMarch -> tapeM_price * $lastYearMarch -> tapeM_count+ $lastYearMarch -> tapeL_price * $lastYearMarch -> tapeL_count + 
                                                $lastYearMarch -> pantsM_price * $lastYearMarch -> pantsM_count + $lastYearMarch -> pantsL_price * $lastYearMarch -> pantsL_count +
                                                $lastYearMarch -> pad300_price  * $lastYearMarch -> pad300_count + $lastYearMarch -> pad400_price * $lastYearMarch -> pad400_count+ 
                                                $lastYearMarch -> pad600_price * $lastYearMarch -> pad600_count + $lastYearMarch -> pad800_price * $lastYearMarch -> pad800_count + 
                                                $lastYearMarch -> pad1000_price * $lastYearMarch -> pad1000_count + $lastYearMarch -> pad1200_price * $lastYearMarch -> pad1200_count;
             
                    }else{
                        $lastYearMarchPrice = 0;
                    }
                    
                    
                    //4月
                    $lastYearApril = $lastYearResult ->where('month','4') ->first();
                    if(isset($lastYearApril)){
                        $lastYearAprilPrice = $lastYearApril -> tapeM_price * $lastYearApril -> tapeM_count+ $lastYearApril -> tapeL_price * $lastYearApril -> tapeL_count + 
                                                $lastYearApril -> pantsM_price * $lastYearApril -> pantsM_count + $lastYearApril -> pantsL_price * $lastYearApril -> pantsL_count +
                                                $lastYearApril -> pad300_price  * $lastYearApril -> pad300_count + $lastYearApril -> pad400_price * $lastYearApril -> pad400_count+ 
                                                $lastYearApril -> pad600_price * $lastYearApril -> pad600_count + $lastYearApril -> pad800_price * $lastYearApril -> pad800_count + 
                                                $lastYearApril -> pad1000_price * $lastYearApril -> pad1000_count + $lastYearApril -> pad1200_price * $lastYearApril -> pad1200_count;
             
                    }else{
                        $lastYearAprilPrice = 0;
                    }
                    
                    //5月
                    $lastYearMay = $lastYearResult ->where('month','5') ->first();
                    if(isset($lastYearMay)){
                        $lastYearMayPrice = $lastYearMay -> tapeM_price * $lastYearMay -> tapeM_count+ $lastYearMay -> tapeL_price * $lastYearMay -> tapeL_count + 
                                            $lastYearMay -> pantsM_price * $lastYearMay -> pantsM_count + $lastYearMay -> pantsL_price * $lastYearMay -> pantsL_count +
                                            $lastYearMay -> pad300_price  * $lastYearMay -> pad300_count + $lastYearMay -> pad400_price * $lastYearMay -> pad400_count+ 
                                            $lastYearMay -> pad600_price * $lastYearMay -> pad600_count + $lastYearMay -> pad800_price * $lastYearMay -> pad800_count + 
                                            $lastYearMay -> pad1000_price * $lastYearMay -> pad1000_count + $lastYearMay -> pad1200_price * $lastYearMay -> pad1200_count;
             
                    }else{
                        $lastYearMayPrice = 0;
                    }
                    
                    //6月
                    $lastYearJune = $lastYearResult ->where('month','6') ->first();
                    if(isset($lastYearJune)){
                        $lastYearJunePrice =$lastYearJune -> tapeM_price * $lastYearJune -> tapeM_count+ $lastYearJune -> tapeL_price * $lastYearJune -> tapeL_count + 
                                            $lastYearJune -> pantsM_price * $lastYearJune -> pantsM_count + $lastYearJune -> pantsL_price * $lastYearJune -> pantsL_count +
                                            $lastYearJune -> pad300_price  * $lastYearJune -> pad300_count + $lastYearJune -> pad400_price * $lastYearJune -> pad400_count+ 
                                            $lastYearJune -> pad600_price * $lastYearJune -> pad600_count + $lastYearJune -> pad800_price * $lastYearJune -> pad800_count + 
                                            $lastYearJune -> pad1000_price * $lastYearJune -> pad1000_count + $lastYearJune -> pad1200_price * $lastYearJune -> pad1200_count;
                    }else{
                        $lastYearJunePrice = 0;
                    }
                    
                    //7月
                    $lastYearJuly = $lastYearResult ->where('month','7') ->first();
                    if(isset($lastYearJuly)){
                        $lastYearJulyPrice = $lastYearJuly -> tapeM_price * $lastYearJuly -> tapeM_count+ $lastYearJuly -> tapeL_price * $lastYearJuly -> tapeL_count + 
                                            $lastYearJuly -> pantsM_price * $lastYearJuly -> pantsM_count + $lastYearJuly -> pantsL_price * $lastYearJuly -> pantsL_count +
                                            $lastYearJuly -> pad300_price  * $lastYearJuly -> pad300_count + $lastYearJuly -> pad400_price * $lastYearJuly -> pad400_count+ 
                                            $lastYearJuly -> pad600_price * $lastYearJuly -> pad600_count + $lastYearJuly -> pad800_price * $lastYearJuly -> pad800_count + 
                                            $lastYearJuly -> pad1000_price * $lastYearJuly -> pad1000_count + $lastYearJuly -> pad1200_price * $lastYearJuly -> pad1200_count;
             
                    }else{
                        $lastYearJulyPrice = 0;
                    }
                    
                    //8月
                    $lastYearAugust = $lastYearResult ->where('month','8') ->first();
                    if(isset($lastYearAugust)){
                        $lastYearAugustPrice = $lastYearAugust -> tapeM_price * $lastYearAugust -> tapeM_count+ $lastYearAugust -> tapeL_price * $lastYearAugust -> tapeL_count + 
                                                $lastYearAugust -> pantsM_price * $lastYearAugust -> pantsM_count + $lastYearAugust -> pantsL_price * $lastYearAugust -> pantsL_count +
                                                $lastYearAugust -> pad300_price  * $lastYearAugust -> pad300_count + $lastYearAugust -> pad400_price * $lastYearAugust -> pad400_count+ 
                                                $lastYearAugust -> pad600_price * $lastYearAugust -> pad600_count + $lastYearAugust -> pad800_price * $lastYearAugust -> pad800_count + 
                                                $lastYearAugust -> pad1000_price * $lastYearAugust -> pad1000_count + $lastYearAugust -> pad1200_price * $lastYearAugust -> pad1200_count;
             
                    }else{
                        $lastYearAugustPrice = 0;
                    }
                    
                    //9月
                    $lastYearSeptember = $lastYearResult ->where('month','9') ->first();
                    if(isset($lastYearSeptember)){
                        $lastYearSeptemberPrice = $lastYearSeptember -> tapeM_price * $lastYearSeptember -> tapeM_count+ $lastYearSeptember -> tapeL_price * $lastYearSeptember -> tapeL_count + 
                                                $lastYearSeptember -> pantsM_price * $lastYearSeptember -> pantsM_count + $lastYearSeptember -> pantsL_price * $lastYearSeptember -> pantsL_count +
                                                $lastYearSeptember -> pad300_price  * $lastYearSeptember -> pad300_count + $lastYearSeptember -> pad400_price * $lastYearSeptember -> pad400_count+ 
                                                $lastYearSeptember -> pad600_price * $lastYearSeptember -> pad600_count + $lastYearSeptember -> pad800_price * $lastYearSeptember -> pad800_count + 
                                                $lastYearSeptember -> pad1000_price * $lastYearSeptember -> pad1000_count + $lastYearSeptember -> pad1200_price * $lastYearSeptember -> pad1200_count;
             
                    }else{
                        $lastYearSeptemberPrice = 0;
                    }
                    
                    //10月
                    $lastYearOctober = $lastYearResult ->where('month','10') ->first();
                    if(isset($lastYearOctober)){
                        $lastYearOctoberPrice = $lastYearOctober -> tapeM_price * $lastYearOctober -> tapeM_count+ $lastYearOctober -> tapeL_price * $lastYearOctober -> tapeL_count + 
                                                $lastYearOctober -> pantsM_price * $lastYearOctober -> pantsM_count + $lastYearOctober -> pantsL_price * $lastYearOctober -> pantsL_count +
                                                $lastYearOctober -> pad300_price  * $lastYearOctober -> pad300_count + $lastYearOctober -> pad400_price * $lastYearOctober -> pad400_count+ 
                                                $lastYearOctober -> pad600_price * $lastYearOctober -> pad600_count + $lastYearOctober -> pad800_price * $lastYearOctober -> pad800_count + 
                                                $lastYearOctober -> pad1000_price * $lastYearOctober -> pad1000_count + $lastYearOctober -> pad1200_price * $lastYearOctober -> pad1200_count;
                    }else{
                        $lastYearOctoberPrice = 0;
                    }
                    
                    //11月
                    $lastYearNovember = $lastYearResult ->where('month','11') ->first();
                    if(isset($lastYearNovember)){
                        $lastYearNovemberPrice =$lastYearNovember -> tapeM_price * $lastYearNovember -> tapeM_count+ $lastYearNovember -> tapeL_price * $lastYearNovember -> tapeL_count + 
                                                $lastYearNovember -> pantsM_price * $lastYearNovember -> pantsM_count + $lastYearNovember-> pantsL_price * $lastYearNovember -> pantsL_count +
                                                $lastYearNovember -> pad300_price  * $lastYearNovember -> pad300_count + $lastYearNovember -> pad400_price * $lastYearNovember -> pad400_count+ 
                                                $lastYearNovember -> pad600_price * $lastYearNovember -> pad600_count + $lastYearNovember -> pad800_price * $lastYearNovember -> pad800_count + 
                                                $lastYearNovember -> pad1000_price * $lastYearNovember -> pad1000_count + $lastYearNovember -> pad1200_price * $lastYearNovember -> pad1200_count;
             
                    }else{
                        $lastYearNovemberPrice = 0;
                    }
                    
                    //12月
                    $lastYearDecember = $lastYearResult ->where('month','12') ->first();
                    if(isset($lastYearDecember)){
                        $lastYearDecemberPrice = $lastYearDecember -> tapeM_price * $lastYearDecember -> tapeM_count+ $lastYearDecember -> tapeL_price * $lastYearDecember -> tapeL_count + 
                                                $lastYearDecember -> pantsM_price * $lastYearDecember -> pantsM_count + $lastYearDecember -> pantsL_price * $lastYearDecember -> pantsL_count +
                                                $lastYearDecember -> pad300_price  * $lastYearDecember -> pad300_count + $lastYearDecember -> pad400_price * $lastYearDecember -> pad400_count+ 
                                                $lastYearDecember -> pad600_price * $lastYearDecember -> pad600_count + $lastYearDecember -> pad800_price * $lastYearDecember -> pad800_count + 
                                                $lastYearDecember -> pad1000_price * $lastYearDecember -> pad1000_count + $lastYearDecember -> pad1200_price * $lastYearDecember -> pad1200_count;
             
                    }else{
                        $lastYearDecemberPrice = 0;
                    }
                    
                    
                    
                    
                    
                    
                    
                    return view('sales.result',['times'=> $times,'salesReport'=>$salesReport,'tape_group_count'=>$tape_group_count,'pants_group_count'=>$pants_group_count,'pad_group_count'=>$pad_group_count,'tape_group_price'=>$tape_group_price,
                                'pants_group_price'=>$pants_group_price,'pad_group_price'=>$pad_group_price,'years'=>$years,'pad300_count'=>$pad300_count,'pad400_count' => $pad400_count,'pad600_count' =>$pad600_count,
                                'pad800_count' =>$pad800_count,'pad1000_count' =>$pad1000_count,'pad1200_count' =>$pad1200_count,'pad300_price'=>$pad300_price,'pad400_price' => $pad400_price,'pad600_price' =>$pad600_price,
                                'pad800_price' =>$pad800_price,'pad1000_price' =>$pad1000_price,'pad1200_price' =>$pad1200_price,'client'=>$client,'TapeAutaPlans'=>$TapeAutaPlans,'TapeInnerPlans'=>$TapeInnerPlans,
                                'PantsAutaPlans'=>$PantsAutaPlans,'PantsInnerPlans'=>$PantsInnerPlans,'planTapeTotalCount' => $planTapeTotalCount,'planPantsTotalCount' => $planPantsTotalCount,'planTotalCount'=>$planTotalCount,'resultTotalCount'=>$resultTotalCount,
                                'planPadTotalCount' =>$planPadTotalCount,'planTotalPrice'=>$planTotalPrice,'resultTotalPrice' => $resultTotalPrice,'planTotalTapePrice' => $planTotalTapePrice,'planTotalPantsPrice' => $planTotalPantsPrice,'planTotalPadPrice'=>$planTotalPadPrice,
                                'thisYearJanuaryCount' => $thisYearJanuaryCount,'thisYearFebruaryCount' =>$thisYearFebruaryCount,'thisYearMarchCount' =>$thisYearMarchCount,'thisYearAprilCount'=>$thisYearAprilCount,'thisYearMayCount'=>$thisYearMayCount,
                                'thisYearJuneCount' => $thisYearJuneCount,'thisYearJulyCount' =>$thisYearJulyCount, 'thisYearAugustCount' => $thisYearAugustCount,'thisYearSeptemberCount' => $thisYearSeptemberCount,'thisYearOctoberCount' =>$thisYearOctoberCount,
                                'thisYearNovemberCount' => $thisYearNovemberCount,'thisYearDecemberCount' => $thisYearDecemberCount,
                                
                                'thisYearJanuaryPrice' => $thisYearJanuaryPrice,'thisYearFebruaryPrice' =>$thisYearFebruaryPrice,'thisYearMarchPrice' =>$thisYearMarchPrice,'thisYearAprilPrice'=>$thisYearAprilPrice,'thisYearMayPrice'=>$thisYearMayPrice,
                                'thisYearJunePrice' => $thisYearJunePrice,'thisYearJulyPrice' =>$thisYearJulyPrice, 'thisYearAugustPrice' => $thisYearAugustPrice,'thisYearSeptemberPrice' => $thisYearSeptemberPrice,'thisYearOctoberPrice' =>$thisYearOctoberPrice,
                                'thisYearNovemberPrice' => $thisYearNovemberPrice,'thisYearDecemberPrice' => $thisYearDecemberPrice,
                                
                                'lastYearJanuaryCount' => $lastYearJanuaryCount,'lastYearFebruaryCount' =>$lastYearFebruaryCount,'lastYearMarchCount' =>$lastYearMarchCount,'lastYearAprilCount'=>$lastYearAprilCount,'lastYearMayCount'=>$lastYearMayCount,
                                'lastYearJuneCount' => $lastYearJuneCount,'lastYearJulyCount' =>$lastYearJulyCount, 'lastYearAugustCount' => $lastYearAugustCount,'lastYearSeptemberCount' => $lastYearSeptemberCount,'lastYearOctoberCount' =>$lastYearOctoberCount,
                                'lastYearNovemberCount' => $lastYearNovemberCount,'lastYearDecemberCount' => $lastYearDecemberCount,
                                
                                'lastYearJanuaryPrice' => $lastYearJanuaryPrice,'lastYearFebruaryPrice' =>$lastYearFebruaryPrice,'lastYearMarchPrice' =>$lastYearMarchPrice,'lastYearAprilPrice'=>$lastYearAprilPrice,'lastYearMayPrice'=>$lastYearMayPrice,
                                'lastYearJunePrice' => $lastYearJunePrice,'lastYearJulyPrice' =>$lastYearJulyPrice, 'lastYearAugustPrice' => $lastYearAugustPrice,'lastYearSeptemberPrice' => $lastYearSeptemberPrice,'lastYearOctoberPrice' =>$lastYearOctoberPrice,
                                'lastYearNovemberPrice' => $lastYearNovemberPrice,'lastYearDecemberPrice' => $lastYearDecemberPrice,
                                
                                'lastYear' => $lastYear
                                ]);
                    
                
                    
                }    
            
            //年月の指定なし（初期アクセス）        
            }else{
                $salesReport = $salesReports->sortByDesc('year')->sortByDesc('month')->first();
  
                $client = Client::find($clientId);
                
                //実績登録なし
                if(empty($salesReport)){
                    
                     //年月選択用
                    $year_ = date('Y');
                    $years = array();
                    for($i=$year_ ;$i >= 2018;$i--){
                        array_push($years,$i);
                    }
                    
                    return view('sales.result',['client' => $client,'salesReport' => $salesReport,'years'=>$years]);
                
                //実績登録あり    
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
                    
                    //実績合計枚数
                    $resultTotalCount = $tape_group_count + $pants_group_count + $pad_group_count;
                    
                    //品群ごと金額
                    $tape_group_price = $salesReport->tapeM_count * $salesReport->tapeM_price + $salesReport->tapeL_count * $salesReport->tapeL_price;
                    $pants_group_price = $salesReport->pantsM_count * $salesReport->pantsM_price + $salesReport->pantsL_count * $salesReport->pantsL_price;
                    $pad_group_price = $salesReport->pad300_count * $salesReport->pad300_price + $salesReport->pad400_count * $salesReport->pad400_price 
                                       + $salesReport->pad600_count * $salesReport->pad600_price + $salesReport->pad800_count * $salesReport->pad800_price 
                                       + $salesReport->pad1000_count * $salesReport->pad1000_price + $salesReport->pad1200_count * $salesReport->pad1200_price;
                    
                    //実績合計金額
                    $resultTotalPrice = $tape_group_price + $pants_group_price + $pad_group_price;
                    
                    //パッドごと金額
                    $pad300_price = $salesReport->pad300_price * $salesReport ->pad300_count;
                    $pad400_price = $salesReport->pad400_price *$salesReport ->pad400_count;
                    $pad600_price = $salesReport->pad600_price *$salesReport ->pad600_count;
                    $pad800_price = $salesReport->pad800_price *$salesReport ->pad800_count;
                    $pad1000_price = $salesReport->pad1000_price *$salesReport ->pad1000_count;
                    $pad1200_price = $salesReport->pad1200_price *$salesReport ->pad1200_count;
                    
                    
                    //設定目標月間試算
                    //日数（月間）
                    $lastday = date('t',strtotime($salesReport -> month));
                    
                    //枚数
                    //テープ使用者
                     //一人あたりテープ使用枚数
                    $planTapeCount = $TapeAutaPlans->count();
                    //一人あたりテープ使用者パッド使用枚数
                    $planTapeInnerCount = $TapeInnerPlans->count();
                    //テープ使用者人数
                    $tapeUserCount = $salesReport -> tapem_user_count + $salesReport -> tapel_user_count;
                    //テープ使用者トータル使用枚数（月間）
                    $planTapeTotalCount = $planTapeCount * $tapeUserCount * $lastday;
                    
                    //パンツ使用者
                    //一人あたりパンツ使用枚数
                    $planPantsCount = $PantsAutaPlans -> count();
                    //一人あたりパンツ使用者パッド使用枚数
                    $planPantsInnerCount = $PantsInnerPlans ->count();
                    //パンツ使用者人数
                    $pantsUserCount = $salesReport -> pantsm_user_count + $salesReport ->pantsl_user_count;
                    //パンツ使用者トータル使用枚数（月間）
                    $planPantsTotalCount = $planPantsCount * $pantsUserCount *$lastday;
                    
                    //パッド合計
                    $planPadTotalCount = $planTapeInnerCount * $tapeUserCount * $lastday + $planPantsInnerCount * $pantsUserCount *$lastday;
                    
                    //テープ・パンツ・パッド合計使用枚数
                    $planTotalCount = $planTapeTotalCount + $planPantsTotalCount + $planPadTotalCount;
                    
                    //金額
                    //テープ
                    $planTapeMPrice = $planTapeCount * $salesReport -> tapem_user_count * $salesReport->tapeM_price * $lastday;
                    $planTapeLPrice = $planTapeCount * $salesReport -> tapel_user_count * $salesReport ->tapeL_price * $lastday;
                    $planTotalTapePrice = $planTapeMPrice + $planTapeLPrice;
                    
                    //パンツ
                    $planPantsMPrice = $planPantsCount * $salesReport -> pantsm_user_count * $salesReport -> pantsM_price * $lastday;
                    $planPantsLPrice = $planPantsCount * $salesReport -> pantsl_user_count * $salesReport -> pantsL_price * $lastday;
                    $planTotalPantsPrice = $planPantsMPrice + $planPantsLPrice;
                    
                    //テープ使用者パッド金額
                    $planTape300Count = $TapeInnerPlans -> where('t_pad_item','パッド300') ->count();
                    $planTape300Price = $planTape300Count * $tapeUserCount * $salesReport -> pad300_price * $lastday;
                    
                    $planTape400Count = $TapeInnerPlans -> where('t_pad_item','パッド400') -> count();
                    $planTape400Price = $planTape400Count * $tapeUserCount * $salesReport -> pad400_price * $lastday;
                    
                    $planTape600Count = $TapeInnerPlans -> where('t_pad_item','パッド600') -> count();
                    $planTape600Price = $planTape600Count * $tapeUserCount * $salesReport -> pad600_price * $lastday;
                    
                    $planTape800Count = $TapeInnerPlans -> where('t_pad_item','パッド800') ->count();
                    $planTape800Price = $planTape800Count * $tapeUserCount * $salesReport -> pad800_price * $lastday;
                    
                    $planTape1000Count = $TapeInnerPlans -> where('t_pad_item','パッド1000') -> count();
                    $planTape1000Price = $planTape1000Count * $tapeUserCount * $salesReport -> pad1000_price * $lastday;
                    
                    $planTape1200Count = $TapeInnerPlans -> where('t_pad_item','パッド1200') ->count();
                    $planTape1200Price = $planTape1200Count *$tapeUserCount *$salesReport -> pad1200_price *$lastday;
                    
                    //テープ使用者パッド金額合計
                    $planTotalTapePadPrice = $planTape300Price + $planTape400Price + $planTape600Price + $planTape800Price + $planTape1000Price + $planTape1200Price;
                    
                    //パンツ使用者パッド金額
                    $planPants300Count = $PantsInnerPlans -> where('p_pad_item','パッド300') ->count();
                    $planPants300Price = $planPants300Count * $pantsUserCount * $salesReport -> pad300_price * $lastday;
                    
                    $planPants400Count = $PantsAutaPlans -> where('p_pad_item','パッド400') -> count();
                    $planPants400Price = $planPants400Count * $pantsUserCount * $salesReport -> pad400_price * $lastday;
                    
                    $planPants600Count = $PantsInnerPlans -> where('p_pad_item','パッド600') -> count();
                    $planPants600Price = $planPants600Count * $pantsUserCount * $salesReport -> pad600_price * $lastday;
                    
                    $planPants800Count = $PantsInnerPlans -> where('p_pad_item','パッド800') ->count();
                    $planPants800Price = $planPants800Count * $pantsUserCount * $salesReport -> pad800_price * $lastday;
                    
                    $planPants1000Count = $PantsInnerPlans -> where('p_pad_item','パッド1000') -> count();
                    $planPants1000Price = $planPants1000Count * $pantsUserCount * $salesReport -> pad1000_price * $lastday;
                    
                    $planPants1200Count = $PantsInnerPlans -> where('p_pad_item','パッド1200') ->count();
                    $planPants1200Price = $planPants1200Count *$pantsUserCount *$salesReport -> pad1200_price *$lastday;
                    
                    //パンツ使用者パッド金額合計
                    $planTotalPantsPadPrice = $planPants300Price + $planPants400Price + $planPants600Price + $planPants800Price + $planPants1000Price + $planPants1200Price;
                    
                    //テープ・パンツ使用者パッド合計金額
                    $planTotalPadPrice = $planTotalTapePadPrice + $planTotalPantsPadPrice;
                    
                    //テープ・パンツ・パッド合計金額
                    $planTotalPrice = $planTotalTapePrice + $planTotalPantsPrice + $planTotalPadPrice;
                    
                    
                    //年間実績
                    //今年
                    $thisYearResult = SalesReport::where('client_id',$request->id) -> where('year',$year_) ->get();
                    
                    
                    //枚数
                    //1月
                    $thisYearJanuary = $thisYearResult ->where('month','1')->first();
                    if(isset($thisYearJanuary)){
                        $thisYearJanuaryCount = $thisYearJanuary -> tapeM_count + $thisYearJanuary -> tapeL_count + $thisYearJanuary -> pantsM_count + $thisYearJanuary -> pantsL_count +
                                                $thisYearJanuary -> pad300_count + $thisYearJanuary -> pad400_count + $thisYearJanuary -> pad600_count + $thisYearJanuary -> pad800_count + 
                                                $thisYearJanuary -> pad1000_count + $thisYearJanuary -> pad1200_count;
                        
                    }else{
                        $thisYearJanuaryCount = 0;
                    }
                    
                   
                    //2月
                    $thisYearFebruary = $thisYearResult ->where('month','2') ->first();
                    if(isset($thisYearFebruary)){
                        $thisYearFebruaryCount = $thisYearFebruary -> tapeM_count + $thisYearFebruary -> tapeL_count + $thisYearFebruary -> pantsM_count + $thisYearFebruary -> pantsL_count +
                                            $thisYearFebruary -> pad300_count + $thisYearFebruary -> pad400_count + $thisYearFebruary -> pad600_count + $thisYearFebruary -> pad800_count + 
                                            $thisYearFebruary -> pad1000_count + $thisYearFebruary -> pad1200_count;
             
                    }else{
                        $thisYearFebruaryCount = 0;
                    }
                    
                    
                    //3月
                    $thisYearMarch = $thisYearResult ->where('month','3') ->first();
                    if(isset($thisYearMarch)){
                        $thisYearMarchCount = $thisYearMarch -> tapeM_count + $thisYearMarch -> tapeL_count + $thisYearMarch -> pantsM_count + $thisYearMarch -> pantsL_count +
                                            $thisYearMarch -> pad300_count + $thisYearMarch -> pad400_count + $thisYearMarch -> pad600_count + $thisYearMarch -> pad800_count + 
                                            $thisYearMarch-> pad1000_count + $thisYearMarch -> pad1200_count;
             
                    }else{
                        $thisYearMarchCount = 0;
                    }
                    
                    
                    //4月
                    $thisYearApril = $thisYearResult ->where('month','4') ->first();
                    if(isset($thisYearApril)){
                        $thisYearAprilCount = $thisYearApril -> tapeM_count + $thisYearApril -> tapeL_count + $thisYearApril -> pantsM_count + $thisYearApril -> pantsL_count +
                                            $thisYearApril -> pad300_count + $thisYearApril -> pad400_count + $thisYearApril -> pad600_count + $thisYearApril -> pad800_count + 
                                            $thisYearApril-> pad1000_count + $thisYearApril -> pad1200_count;
             
                    }else{
                        $thisYearAprilCount = 0;
                    }
                    
                    //5月
                    $thisYearMay = $thisYearResult ->where('month','5') ->first();
                    if(isset($thisYearMay)){
                        $thisYearMayCount = $thisYearMay -> tapeM_count + $thisYearMay -> tapeL_count + $thisYearMay -> pantsM_count + $thisYearMay -> pantsL_count +
                                            $thisYearMay -> pad300_count + $thisYearMay -> pad400_count + $thisYearMay -> pad600_count + $thisYearMay -> pad800_count + 
                                            $thisYearMay-> pad1000_count + $thisYearMay -> pad1200_count;
             
                    }else{
                        $thisYearMayCount = 0;
                    }
                    
                    //6月
                    $thisYearJune = $thisYearResult ->where('month','6') ->first();
                    if(isset($thisYearJune)){
                        $thisYearJuneCount = $thisYearJune -> tapeM_count + $thisYearJune -> tapeL_count + $thisYearJune -> pantsM_count + $thisYearJune -> pantsL_count +
                                            $thisYearJune -> pad300_count + $thisYearJune -> pad400_count + $thisYearJune -> pad600_count + $thisYearJune -> pad800_count + 
                                            $thisYearJune-> pad1000_count + $thisYearJune -> pad1200_count;
             
                    }else{
                        $thisYearJuneCount = 0;
                    }
                    
                    //7月
                    $thisYearJuly = $thisYearResult ->where('month','7') ->first();
                    if(isset($thisYearJuly)){
                        $thisYearJulyCount = $thisYearJuly -> tapeM_count + $thisYearJuly -> tapeL_count + $thisYearJuly -> pantsM_count + $thisYearJuly -> pantsL_count +
                                            $thisYearJuly -> pad300_count + $thisYearJuly -> pad400_count + $thisYearJuly -> pad600_count + $thisYearJuly -> pad800_count + 
                                            $thisYearJuly-> pad1000_count + $thisYearJuly -> pad1200_count;
             
                    }else{
                        $thisYearJulyCount = 0;
                    }
                    
                    //8月
                    $thisYearAugust = $thisYearResult ->where('month','8') ->first();
                    if(isset($thisYearAugust)){
                        $thisYearAugustCount = $thisYearAugust -> tapeM_count + $thisYearAugust -> tapeL_count + $thisYearAugust -> pantsM_count + $thisYearAugust -> pantsL_count +
                                            $thisYearAugust -> pad300_count + $thisYearAugust -> pad400_count + $thisYearAugust -> pad600_count + $thisYearAugust -> pad800_count + 
                                            $thisYearAugust-> pad1000_count + $thisYearAugust -> pad1200_count;
             
                    }else{
                        $thisYearAugustCount = 0;
                    }
                    
                    //9月
                    $thisYearSeptember = $thisYearResult ->where('month','9') ->first();
                    if(isset($thisYearSeptember)){
                        $thisYearSeptemberCount = $thisYearSeptember -> tapeM_count + $thisYearSeptember -> tapeL_count + $thisYearSeptember -> pantsM_count + $thisYearSeptember -> pantsL_count +
                                            $thisYearSeptember -> pad300_count + $thisYearSeptember -> pad400_count + $thisYearSeptember -> pad600_count + $thisYearSeptember -> pad800_count + 
                                            $thisYearSeptember-> pad1000_count + $thisYearSeptember -> pad1200_count;
             
                    }else{
                        $thisYearSeptemberCount = 0;
                    }
                    
                    //10月
                    $thisYearOctober = $thisYearResult ->where('month','10') ->first();
                    if(isset($thisYearOctober)){
                        $thisYearOctoberCount = $thisYearOctober -> tapeM_count + $thisYearOctober -> tapeL_count + $thisYearOctober -> pantsM_count + $thisYearOctober -> pantsL_count +
                                            $thisYearOctober -> pad300_count + $thisYearOctober -> pad400_count + $thisYearOctober -> pad600_count + $thisYearOctober -> pad800_count + 
                                            $thisYearOctober-> pad1000_count + $thisYearOctober -> pad1200_count;
             
                    }else{
                        $thisYearOctoberCount = 0;
                    }
                    
                    //11月
                    $thisYearNovember = $thisYearResult ->where('month','11') ->first();
                    if(isset($thisYearNovember)){
                        $thisYearNovemberCount = $thisYearNovember -> tapeM_count + $thisYearNovember -> tapeL_count + $thisYearNovember -> pantsM_count + $thisYearNovember -> pantsL_count +
                                            $thisYearNovember -> pad300_count + $thisYearNovember -> pad400_count + $thisYearNovember -> pad600_count + $thisYearNovember -> pad800_count + 
                                            $thisYearNovember -> pad1000_count + $thisYearNovember -> pad1200_count;
             
                    }else{
                        $thisYearNovemberCount = 0;
                    }
                    
                    //12月
                    $thisYearDecember = $thisYearResult ->where('month','12') ->first();
                    if(isset($thisYearDecember)){
                        $thisYearDecemberCount = $thisYearDecember -> tapeM_count + $thisYearDecember -> tapeL_count + $thisYearDecember -> pantsM_count + $thisYearDecember -> pantsL_count +
                                            $thisYearDecember -> pad300_count + $thisYearDecember -> pad400_count + $thisYearDecember -> pad600_count + $thisYearDecember -> pad800_count + 
                                            $thisYearDecember -> pad1000_count + $thisYearDecember -> pad1200_count;
             
                    }else{
                        $thisYearDecemberCount = 0;
                    }
                    
                    //金額
                    //1月
                    $thisYearJanuary = $thisYearResult ->where('month','1')->first();
                    if(isset($thisYearJanuary)){
                        $thisYearJanuaryPrice = $thisYearJanuary -> tapeM_price * $thisYearJanuary -> tapeM_count+ $thisYearJanuary -> tapeL_price * $thisYearJanuary -> tapeL_count + 
                                                $thisYearJanuary -> pantsM_price * $thisYearJanuary -> pantsM_count + $thisYearJanuary -> pantsL_price * $thisYearJanuary -> pantsL_count +
                                                $thisYearJanuary -> pad300_price  * $thisYearJanuary -> pad300_count + $thisYearJanuary -> pad400_price * $thisYearJanuary -> pad400_count+ 
                                                $thisYearJanuary -> pad600_price * $thisYearJanuary -> pad600_count + $thisYearJanuary -> pad800_price * $thisYearJanuary -> pad800_count + 
                                                $thisYearJanuary -> pad1000_price * $thisYearJanuary -> pad1000_count + $thisYearJanuary -> pad1200_price * $thisYearJanuary -> pad1200_count;
                        
                    }else{
                        $thisYearJanuaryPrice = 0;
                    }
                    
                   
                    //2月
                    $thisYearFebruary = $thisYearResult ->where('month','2') ->first();
                    if(isset($thisYearFebruary)){
                        $thisYearFebruaryPrice = $thisYearFebruary -> tapeM_price * $thisYearFebruary -> tapeM_count+ $thisYearFebruary -> tapeL_price * $thisYearFebruary -> tapeL_count + 
                                                $thisYearFebruary -> pantsM_price * $thisYearFebruary -> pantsM_count + $thisYearFebruary -> pantsL_price * $thisYearFebruary -> pantsL_count +
                                                $thisYearFebruary -> pad300_price  * $thisYearFebruary -> pad300_count + $thisYearFebruary -> pad400_price * $thisYearFebruary -> pad400_count+ 
                                                $thisYearFebruary -> pad600_price * $thisYearFebruary -> pad600_count + $thisYearFebruary -> pad800_price * $thisYearv -> pad800_count + 
                                                $thisYearFebruary -> pad1000_price * $thisYearFebruary -> pad1000_count + $thisYearFebruary -> pad1200_price * $thisYearFebruary -> pad1200_count;
             
                    }else{
                        $thisYearFebruaryPrice = 0;
                    }
                    
                    
                    //3月
                    $thisYearMarch = $thisYearResult ->where('month','3') ->first();
                    if(isset($thisYearMarch)){
                        $thisYearMarchPrice =$thisYearMarch -> tapeM_price * $thisYearMarch -> tapeM_count+ $thisYearMarch -> tapeL_price * $thisYearMarch -> tapeL_count + 
                                                $thisYearMarch -> pantsM_price * $thisYearMarch -> pantsM_count + $thisYearMarch -> pantsL_price * $thisYearMarch -> pantsL_count +
                                                $thisYearMarch -> pad300_price  * $thisYearMarch -> pad300_count + $thisYearMarch -> pad400_price * $thisYearMarch -> pad400_count+ 
                                                $thisYearMarch -> pad600_price * $thisYearMarch -> pad600_count + $thisYearMarch -> pad800_price * $thisYearMarch -> pad800_count + 
                                                $thisYearMarch -> pad1000_price * $thisYearMarch -> pad1000_count + $thisYearMarch -> pad1200_price * $thisYearMarch -> pad1200_count;
             
                    }else{
                        $thisYearMarchPrice = 0;
                    }
                    
                    
                    //4月
                    $thisYearApril = $thisYearResult ->where('month','4') ->first();
                    if(isset($thisYearApril)){
                        $thisYearAprilPrice = $thisYearApril -> tapeM_price * $thisYearApril -> tapeM_count+ $thisYearApril -> tapeL_price * $thisYearApril -> tapeL_count + 
                                                $thisYearApril -> pantsM_price * $thisYearApril -> pantsM_count + $thisYearApril -> pantsL_price * $thisYearApril -> pantsL_count +
                                                $thisYearApril -> pad300_price  * $thisYearApril -> pad300_count + $thisYearApril -> pad400_price * $thisYearApril -> pad400_count+ 
                                                $thisYearApril -> pad600_price * $thisYearApril -> pad600_count + $thisYearApril -> pad800_price * $thisYearApril -> pad800_count + 
                                                $thisYearApril -> pad1000_price * $thisYearApril -> pad1000_count + $thisYearApril -> pad1200_price * $thisYearApril -> pad1200_count;
                    }else{
                        $thisYearAprilPrice = 0;
                    }
                    
                    //5月
                    $thisYearMay = $thisYearResult ->where('month','5') ->first();
                    if(isset($thisYearMay)){
                        $thisYearMayPrice = $thisYearMay -> tapeM_price * $thisYearMay -> tapeM_count+ $thisYearMay -> tapeL_price * $thisYearMay -> tapeL_count + 
                                            $thisYearMay -> pantsM_price * $thisYearMay -> pantsM_count + $thisYearMay -> pantsL_price * $thisYearMay -> pantsL_count +
                                            $thisYearMay -> pad300_price  * $thisYearMay -> pad300_count + $thisYearMay -> pad400_price * $thisYearMay -> pad400_count+ 
                                            $thisYearMay -> pad600_price * $thisYearMay -> pad600_count + $thisYearMay -> pad800_price * $thisYearMay -> pad800_count + 
                                            $thisYearMay -> pad1000_price * $thisYearMay -> pad1000_count + $thisYearMay -> pad1200_price * $thisYearMay -> pad1200_count;
             
                    }else{
                        $thisYearMayPrice = 0;
                    }
                    
                    //6月
                    $thisYearJune = $thisYearResult ->where('month','6') ->first();
                    if(isset($thisYearJune)){
                        $thisYearJunePrice = $thisYearJune -> tapeM_price * $thisYearJune -> tapeM_count+ $thisYearJune -> tapeL_price * $thisYearJune -> tapeL_count + 
                                                $thisYearJune -> pantsM_price * $thisYearJune -> pantsM_count + $thisYearJune -> pantsL_price * $thisYearJune -> pantsL_count +
                                                $thisYearJune -> pad300_price  * $thisYearJune -> pad300_count + $thisYearJune -> pad400_price * $thisYearJune -> pad400_count+ 
                                                $thisYearJune -> pad600_price * $thisYearJune -> pad600_count + $thisYearJune -> pad800_price * $thisYearJune -> pad800_count + 
                                                $thisYearJune -> pad1000_price * $thisYearJune -> pad1000_count + $thisYearJune -> pad1200_price * $thisYearJune -> pad1200_count;
             
                    }else{
                        $thisYearJunePrice = 0;
                    }
                    
                    //7月
                    $thisYearJuly = $thisYearResult ->where('month','7') ->first();
                    if(isset($thisYearJuly)){
                        $thisYearJulyPrice = $thisYearJuly -> tapeM_price * $thisYearJuly -> tapeM_count+ $thisYearJuly -> tapeL_price * $thisYearJuly -> tapeL_count + 
                                            $thisYearJuly -> pantsM_price * $thisYearJuly -> pantsM_count + $thisYearJuly -> pantsL_price * $thisYearJuly -> pantsL_count +
                                            $thisYearJuly -> pad300_price  * $thisYearJuly -> pad300_count + $thisYearJuly -> pad400_price * $thisYearJuly -> pad400_count+ 
                                            $thisYearJuly -> pad600_price * $thisYearJuly -> pad600_count + $thisYearJuly -> pad800_price * $thisYearJuly -> pad800_count + 
                                            $thisYearJuly -> pad1000_price * $thisYearJuly -> pad1000_count + $thisYearJuly -> pad1200_price * $thisYearJuly -> pad1200_count;
             
                    }else{
                        $thisYearJulyPrice = 0;
                    }
                    
                    //8月
                    $thisYearAugust = $thisYearResult ->where('month','8') ->first();
                    if(isset($thisYearAugust)){
                        $thisYearAugustPrice = $thisYearAugust -> tapeM_price * $thisYearAugust -> tapeM_count+ $thisYearAugust -> tapeL_price * $thisYearAugust -> tapeL_count + 
                                                $thisYearAugust -> pantsM_price * $thisYearAugust -> pantsM_count + $thisYearAugust -> pantsL_price * $thisYearAugust -> pantsL_count +
                                                $thisYearAugust -> pad300_price  * $thisYearAugust -> pad300_count + $thisYearAugust -> pad400_price * $thisYearAugust -> pad400_count+ 
                                                $thisYearAugust -> pad600_price * $thisYearAugust -> pad600_count + $thisYearAugust -> pad800_price * $thisYearAugust -> pad800_count + 
                                                $thisYearAugust -> pad1000_price * $thisYearAugust -> pad1000_count + $thisYearAugust -> pad1200_price * $thisYearAugust -> pad1200_count;
             
                    }else{
                        $thisYearAugustPrice = 0;
                    }
                    
                    //9月
                    $thisYearSeptember = $thisYearResult ->where('month','9') ->first();
                    if(isset($thisYearSeptember)){
                        $thisYearSeptemberPrice = $thisYearSeptember -> tapeM_price * $thisYearSeptember -> tapeM_count+ $thisYearSeptember -> tapeL_price * $thisYearSeptember -> tapeL_count + 
                                                $thisYearSeptember -> pantsM_price * $thisYearSeptember -> pantsM_count + $thisYearSeptember -> pantsL_price * $thisYearSeptember -> pantsL_count +
                                                $thisYearSeptember -> pad300_price  * $thisYearSeptember -> pad300_count + $thisYearSeptember -> pad400_price * $thisYearSeptember -> pad400_count+ 
                                                $thisYearSeptember -> pad600_price * $thisYearSeptember -> pad600_count + $thisYearSeptember -> pad800_price * $thisYearSeptember -> pad800_count + 
                                                $thisYearSeptember -> pad1000_price * $thisYearSeptember -> pad1000_count + $thisYearSeptember -> pad1200_price * $thisYearSeptember -> pad1200_count;
             
                    }else{
                        $thisYearSeptemberPrice = 0;
                    }
                    
                    //10月
                    $thisYearOctober = $thisYearResult ->where('month','10') ->first();
                    if(isset($thisYearOctober)){
                        $thisYearOctoberPrice = $thisYearOctober -> tapeM_price * $thisYearOctober -> tapeM_count+ $thisYearOctober -> tapeL_price * $thisYearOctober -> tapeL_count + 
                                                $thisYearOctober -> pantsM_price * $thisYearOctober -> pantsM_count + $thisYearOctober -> pantsL_price * $thisYearOctober -> pantsL_count +
                                                $thisYearOctober -> pad300_price  * $thisYearOctober -> pad300_count + $thisYearOctober -> pad400_price * $thisYearOctober -> pad400_count+ 
                                                $thisYearOctober -> pad600_price * $thisYearOctober -> pad600_count + $thisYearOctober -> pad800_price * $thisYearOctober -> pad800_count + 
                                                $thisYearOctober -> pad1000_price * $thisYearOctober -> pad1000_count + $thisYearOctober -> pad1200_price * $thisYearOctober -> pad1200_count;
             
                    }else{
                        $thisYearOctoberPrice = 0;
                    }
                    
                    //11月
                    $thisYearNovember = $thisYearResult ->where('month','11') ->first();
                    if(isset($thisYearNovember)){
                        $thisYearNovemberPrice = $thisYearNovember -> tapeM_price * $thisYearNovember -> tapeM_count+ $thisYearNovember -> tapeL_price * $thisYearNovember -> tapeL_count + 
                                                $thisYearNovember -> pantsM_price * $thisYearNovember -> pantsM_count + $thisYearNovember -> pantsL_price * $thisYearNovember -> pantsL_count +
                                                $thisYearNovember -> pad300_price  * $thisYearNovember -> pad300_count + $thisYearNovember -> pad400_price * $thisYearNovember -> pad400_count+ 
                                                $thisYearNovember -> pad600_price * $thisYearNovember -> pad600_count + $thisYearNovember -> pad800_price * $thisYearNovember -> pad800_count + 
                                                $thisYearNovember -> pad1000_price * $thisYearNovember -> pad1000_count + $thisYearNovember -> pad1200_price * $thisYearNovember -> pad1200_count;
             
                    }else{
                        $thisYearNovemberPrice = 0;
                    }
                    
                    //12月
                    $thisYearDecember = $thisYearResult ->where('month','12') ->first();
                    if(isset($thisYearDecember)){
                        $thisYearDecemberPrice = $thisYearDecember -> tapeM_price * $thisYearDecember -> tapeM_count+ $thisYearDecember -> tapeL_price * $thisYearDecember -> tapeL_count + 
                                                $thisYearDecember -> pantsM_price * $thisYearDecember -> pantsM_count + $thisYearDecember -> pantsL_price * $thisYearDecember -> pantsL_count +
                                                $thisYearDecember -> pad300_price  * $thisYearDecember -> pad300_count + $thisYearDecember -> pad400_price * $thisYearDecember -> pad400_count+ 
                                                $thisYearDecember -> pad600_price * $thisYearDecember -> pad600_count + $thisYearDecember -> pad800_price * $thisYearDecember -> pad800_count + 
                                                $thisYearDecember -> pad1000_price * $thisYearDecember -> pad1000_count + $thisYearDecember -> pad1200_price * $thisYearDecember -> pad1200_count;
             
                    }else{
                        $thisYearDecemberPrice = 0;
                    }
                    
                    
                    //昨年
                    $lastYear = $year_ - 1;
                    $lastYearResult = SalesReport::where('client_id',$request->id) -> where('year',$lastYear) ->get();
                    
                    //枚数
                    //1月
                    $lastYearJanuary = $lastYearResult ->where('month','1')->first();
                    if(isset($lastYearJanuary)){
                        $lastYearJanuaryCount = $lastYearJanuary -> tapeM_count + $lastYearJanuary -> tapeL_count + $lastYearJanuary -> pantsM_count + $lastYearJanuary -> pantsL_count +
                                                $lastYearJanuary -> pad300_count + $lastYearJanuary -> pad400_count + $lastYearJanuary -> pad600_count + $lastYearJanuary -> pad800_count + 
                                                $lastYearJanuary -> pad1000_count + $lastYearJanuary -> pad1200_count;
                        
                    }else{
                        $lastYearJanuaryCount = 0;
                    }
                    
                   
                    //2月
                    $lastYearFebruary = $lastYearResult ->where('month','2') ->first();
                    if(isset($lastYearFebruary)){
                        $lastYearFebruaryCount = $lastYearFebruary -> tapeM_count + $lastYearFebruary -> tapeL_count + $lastYearFebruary -> pantsM_count + $lastYearFebruary -> pantsL_count +
                                            $lastYearFebruary -> pad300_count + $lastYearFebruary -> pad400_count + $lastYearFebruary -> pad600_count + $lastYearFebruary -> pad800_count + 
                                            $lastYearFebruary -> pad1000_count + $lastYearFebruary -> pad1200_count;
             
                    }else{
                        $lastYearFebruaryCount = 0;
                    }
                    
                    
                    //3月
                    $lastYearMarch = $lastYearResult ->where('month','3') ->first();
                    if(isset($lastYearMarch)){
                        $lastYearMarchCount = $lastYearMarch -> tapeM_count + $lastYearMarch -> tapeL_count + $lastYearMarch -> pantsM_count + $lastYearMarch -> pantsL_count +
                                            $lastYearMarch -> pad300_count + $lastYearMarch -> pad400_count + $lastYearMarch -> pad600_count + $lastYearMarch -> pad800_count + 
                                            $lastYearMarch-> pad1000_count + $lastYearMarch -> pad1200_count;
             
                    }else{
                        $lastYearMarchCount = 0;
                    }
                    
                    
                    //4月
                    $lastYearApril = $lastYearResult ->where('month','4') ->first();
                    if(isset($lastYearApril)){
                        $lastYearAprilCount = $lastYearApril -> tapeM_count + $lastYearApril -> tapeL_count + $lastYearApril -> pantsM_count + $lastYearApril -> pantsL_count +
                                            $lastYearApril -> pad300_count + $lastYearApril -> pad400_count + $lastYearApril -> pad600_count + $lastYearApril -> pad800_count + 
                                            $lastYearApril-> pad1000_count + $lastYearApril -> pad1200_count;
             
                    }else{
                        $lastYearAprilCount = 0;
                    }
                    
                    //5月
                    $lastYearMay = $lastYearResult ->where('month','5') ->first();
                    if(isset($lastYearMay)){
                        $lastYearMayCount = $lastYearMay -> tapeM_count + $lastYearMay -> tapeL_count + $lastYearMay -> pantsM_count + $lastYearMay -> pantsL_count +
                                            $lastYearMay -> pad300_count + $lastYearMay -> pad400_count + $lastYearMay -> pad600_count + $lastYearMay -> pad800_count + 
                                            $lastYearMay-> pad1000_count + $lastYearMay -> pad1200_count;
             
                    }else{
                        $lastYearMayCount = 0;
                    }
                    
                    //6月
                    $lastYearJune = $lastYearResult ->where('month','6') ->first();
                    if(isset($lastYearJune)){
                        $lastYearJuneCount = $lastYearJune -> tapeM_count + $lastYearJune -> tapeL_count + $lastYearJune -> pantsM_count + $lastYearJune -> pantsL_count +
                                            $lastYearJune -> pad300_count + $lastYearJune -> pad400_count + $lastYearJune -> pad600_count + $lastYearJune -> pad800_count + 
                                            $lastYearJune-> pad1000_count + $lastYearJune -> pad1200_count;
             
                    }else{
                        $lastYearJuneCount = 0;
                    }
                    
                    //7月
                    $lastYearJuly = $lastYearResult ->where('month','7') ->first();
                    if(isset($lastYearJuly)){
                        $lastYearJulyCount = $lastYearJuly -> tapeM_count + $lastYearJuly -> tapeL_count + $lastYearJuly -> pantsM_count + $lastYearJuly -> pantsL_count +
                                            $lastYearJuly -> pad300_count + $lastYearJuly -> pad400_count + $lastYearJuly -> pad600_count + $lastYearJuly -> pad800_count + 
                                            $lastYearJuly-> pad1000_count + $lastYearJuly -> pad1200_count;
             
                    }else{
                        $lastYearJulyCount = 0;
                    }
                    
                    //8月
                    $lastYearAugust = $lastYearResult ->where('month','8') ->first();
                    if(isset($lastYearAugust)){
                        $lastYearAugustCount = $lastYearAugust -> tapeM_count + $lastYearAugust -> tapeL_count + $lastYearAugust -> pantsM_count + $lastYearAugust -> pantsL_count +
                                            $lastYearAugust -> pad300_count + $lastYearAugust -> pad400_count + $lastYearAugust -> pad600_count + $lastYearAugust -> pad800_count + 
                                            $lastYearAugust-> pad1000_count + $lastYearAugust -> pad1200_count;
             
                    }else{
                        $lastYearAugustCount = 0;
                    }
                    
                    //9月
                    $lastYearSeptember = $lastYearResult ->where('month','9') ->first();
                    if(isset($lastYearSeptember)){
                        $lastYearSeptemberCount = $lastYearSeptember -> tapeM_count + $lastYearSeptember -> tapeL_count + $lastYearSeptember -> pantsM_count + $lastYearSeptember -> pantsL_count +
                                            $lastYearSeptember -> pad300_count + $lastYearSeptember -> pad400_count + $lastYearSeptember -> pad600_count + $lastYearSeptember -> pad800_count + 
                                            $lastYearSeptember-> pad1000_count + $lastYearSeptember -> pad1200_count;
             
                    }else{
                        $lastYearSeptemberCount = 0;
                    }
                    
                    //10月
                    $lastYearOctober = $lastYearResult ->where('month','10') ->first();
                    if(isset($lastYearOctober)){
                        $lastYearOctoberCount = $lastYearOctober -> tapeM_count + $lastYearOctober -> tapeL_count + $lastYearOctober -> pantsM_count + $lastYearOctober -> pantsL_count +
                                            $lastYearOctober -> pad300_count + $lastYearOctober -> pad400_count + $lastYearOctober -> pad600_count + $lastYearOctober -> pad800_count + 
                                            $lastYearOctober-> pad1000_count + $lastYearOctober -> pad1200_count;
             
                    }else{
                        $lastYearOctoberCount = 0;
                    }
                    
                    //11月
                    $lastYearNovember = $lastYearResult ->where('month','11') ->first();
                    if(isset($lastYearNovember)){
                        $lastYearNovemberCount = $lastYearNovember -> tapeM_count + $lastYearNovember -> tapeL_count + $lastYearNovember -> pantsM_count + $lastYearNovember -> pantsL_count +
                                            $lastYearNovember -> pad300_count + $lastYearNovember -> pad400_count + $lastYearNovember -> pad600_count + $lastYearNovember -> pad800_count + 
                                            $lastYearNovember -> pad1000_count + $lastYearNovember -> pad1200_count;
             
                    }else{
                        $lastYearNovemberCount = 0;
                    }
                    
                    //12月
                    $lastYearDecember = $lastYearResult ->where('month','12') ->first();
                    if(isset($lastYearDecember)){
                        $lastYearDecemberCount = $lastYearDecember -> tapeM_count + $lastYearDecember -> tapeL_count + $lastYearDecember -> pantsM_count + $lastYearDecember -> pantsL_count +
                                            $lastYearDecember -> pad300_count + $lastYearDecember -> pad400_count + $lastYearDecember -> pad600_count + $lastYearDecember -> pad800_count + 
                                            $lastYearDecember -> pad1000_count + $lastYearDecember -> pad1200_count;
             
                    }else{
                        $lastYearDecemberCount = 0;
                    }
                    
                    //金額
                    //1月
                    $lastYearJanuary = $lastYearResult ->where('month','1')->first();
                    if(isset($lastYearJanuary)){
                        $lastYearJanuaryPrice = $lastYearJanuary -> tapeM_price * $lastYearJanuary -> tapeM_count+ $lastYearJanuary -> tapeL_price * $lastYearJanuary -> tapeL_count + 
                                                $lastYearJanuary -> pantsM_price * $lastYearJanuary -> pantsM_count + $lastYearJanuary -> pantsL_price * $lastYearJanuary -> pantsL_count +
                                                $lastYearJanuary -> pad300_price  * $lastYearJanuary -> pad300_count + $lastYearJanuary -> pad400_price * $lastYearJanuary -> pad400_count+ 
                                                $lastYearJanuary -> pad600_price * $lastYearJanuary -> pad600_count + $lastYearJanuary -> pad800_price * $lastYearJanuary -> pad800_count + 
                                                $lastYearJanuary -> pad1000_price * $lastYearJanuary -> pad1000_count + $lastYearJanuary -> pad1200_price * $lastYearJanuary -> pad1200_count;
                        
                    }else{
                        $lastYearJanuaryPrice = 0;
                    }
                    
                   
                    //2月
                    $lastYearFebruary = $lastYearResult ->where('month','2') ->first();
                    if(isset($lastYearFebruary)){
                        $lastYearFebruaryPrice = $lastYearFebruary -> tapeM_price * $lastYearFebruary -> tapeM_count+ $lastYearFebruary -> tapeL_price * $lastYearFebruary -> tapeL_count + 
                                                $lastYearFebruary -> pantsM_price * $lastYearFebruary -> pantsM_count + $lastYearFebruary -> pantsL_price * $lastYearFebruary -> pantsL_count +
                                                $lastYearFebruary -> pad300_price  * $lastYearFebruary -> pad300_count + $lastYearFebruary -> pad400_price * $lastYearFebruary -> pad400_count+ 
                                                $lastYearFebruary -> pad600_price * $lastYearFebruary -> pad600_count + $lastYearFebruary -> pad800_price * $lastYearFebruary -> pad800_count + 
                                                $lastYearFebruary -> pad1000_price * $lastYearFebruary -> pad1000_count + $lastYearFebruary -> pad1200_price * $lastYearFebruary -> pad1200_count;
             
                    }else{
                        $lastYearFebruaryPrice = 0;
                    }
                    
                    
                    //3月
                    $lastYearMarch = $lastYearResult ->where('month','3') ->first();
                    if(isset($lastYearMarch)){
                        $lastYearMarchPrice = $lastYearMarch -> tapeM_price * $lastYearMarch -> tapeM_count+ $lastYearMarch -> tapeL_price * $lastYearMarch -> tapeL_count + 
                                                $lastYearMarch -> pantsM_price * $lastYearMarch -> pantsM_count + $lastYearMarch -> pantsL_price * $lastYearMarch -> pantsL_count +
                                                $lastYearMarch -> pad300_price  * $lastYearMarch -> pad300_count + $lastYearMarch -> pad400_price * $lastYearMarch -> pad400_count+ 
                                                $lastYearMarch -> pad600_price * $lastYearMarch -> pad600_count + $lastYearMarch -> pad800_price * $lastYearMarch -> pad800_count + 
                                                $lastYearMarch -> pad1000_price * $lastYearMarch -> pad1000_count + $lastYearMarch -> pad1200_price * $lastYearMarch -> pad1200_count;
             
                    }else{
                        $lastYearMarchPrice = 0;
                    }
                    
                    
                    //4月
                    $lastYearApril = $lastYearResult ->where('month','4') ->first();
                    if(isset($lastYearApril)){
                        $lastYearAprilPrice = $lastYearApril -> tapeM_price * $lastYearApril -> tapeM_count+ $lastYearApril -> tapeL_price * $lastYearApril -> tapeL_count + 
                                                $lastYearApril -> pantsM_price * $lastYearApril -> pantsM_count + $lastYearApril -> pantsL_price * $lastYearApril -> pantsL_count +
                                                $lastYearApril -> pad300_price  * $lastYearApril -> pad300_count + $lastYearApril -> pad400_price * $lastYearApril -> pad400_count+ 
                                                $lastYearApril -> pad600_price * $lastYearApril -> pad600_count + $lastYearApril -> pad800_price * $lastYearApril -> pad800_count + 
                                                $lastYearApril -> pad1000_price * $lastYearApril -> pad1000_count + $lastYearApril -> pad1200_price * $lastYearApril -> pad1200_count;
             
                    }else{
                        $lastYearAprilPrice = 0;
                    }
                    
                    //5月
                    $lastYearMay = $lastYearResult ->where('month','5') ->first();
                    if(isset($lastYearMay)){
                        $lastYearMayPrice = $lastYearMay -> tapeM_price * $lastYearMay -> tapeM_count+ $lastYearMay -> tapeL_price * $lastYearMay -> tapeL_count + 
                                            $lastYearMay -> pantsM_price * $lastYearMay -> pantsM_count + $lastYearMay -> pantsL_price * $lastYearMay -> pantsL_count +
                                            $lastYearMay -> pad300_price  * $lastYearMay -> pad300_count + $lastYearMay -> pad400_price * $lastYearMay -> pad400_count+ 
                                            $lastYearMay -> pad600_price * $lastYearMay -> pad600_count + $lastYearMay -> pad800_price * $lastYearMay -> pad800_count + 
                                            $lastYearMay -> pad1000_price * $lastYearMay -> pad1000_count + $lastYearMay -> pad1200_price * $lastYearMay -> pad1200_count;
             
                    }else{
                        $lastYearMayPrice = 0;
                    }
                    
                    //6月
                    $lastYearJune = $lastYearResult ->where('month','6') ->first();
                    if(isset($lastYearJune)){
                        $lastYearJunePrice =$lastYearJune -> tapeM_price * $lastYearJune -> tapeM_count+ $lastYearJune -> tapeL_price * $lastYearJune -> tapeL_count + 
                                            $lastYearJune -> pantsM_price * $lastYearJune -> pantsM_count + $lastYearJune -> pantsL_price * $lastYearJune -> pantsL_count +
                                            $lastYearJune -> pad300_price  * $lastYearJune -> pad300_count + $lastYearJune -> pad400_price * $lastYearJune -> pad400_count+ 
                                            $lastYearJune -> pad600_price * $lastYearJune -> pad600_count + $lastYearJune -> pad800_price * $lastYearJune -> pad800_count + 
                                            $lastYearJune -> pad1000_price * $lastYearJune -> pad1000_count + $lastYearJune -> pad1200_price * $lastYearJune -> pad1200_count;
                    }else{
                        $lastYearJunePrice = 0;
                    }
                    
                    //7月
                    $lastYearJuly = $lastYearResult ->where('month','7') ->first();
                    if(isset($lastYearJuly)){
                        $lastYearJulyPrice = $lastYearJuly -> tapeM_price * $lastYearJuly -> tapeM_count+ $lastYearJuly -> tapeL_price * $lastYearJuly -> tapeL_count + 
                                            $lastYearJuly -> pantsM_price * $lastYearJuly -> pantsM_count + $lastYearJuly -> pantsL_price * $lastYearJuly -> pantsL_count +
                                            $lastYearJuly -> pad300_price  * $lastYearJuly -> pad300_count + $lastYearJuly -> pad400_price * $lastYearJuly -> pad400_count+ 
                                            $lastYearJuly -> pad600_price * $lastYearJuly -> pad600_count + $lastYearJuly -> pad800_price * $lastYearJuly -> pad800_count + 
                                            $lastYearJuly -> pad1000_price * $lastYearJuly -> pad1000_count + $lastYearJuly -> pad1200_price * $lastYearJuly -> pad1200_count;
             
                    }else{
                        $lastYearJulyPrice = 0;
                    }
                    
                    //8月
                    $lastYearAugust = $lastYearResult ->where('month','8') ->first();
                    if(isset($lastYearAugust)){
                        $lastYearAugustPrice = $lastYearAugust -> tapeM_price * $lastYearAugust -> tapeM_count+ $lastYearAugust -> tapeL_price * $lastYearAugust -> tapeL_count + 
                                                $lastYearAugust -> pantsM_price * $lastYearAugust -> pantsM_count + $lastYearAugust -> pantsL_price * $lastYearAugust -> pantsL_count +
                                                $lastYearAugust -> pad300_price  * $lastYearAugust -> pad300_count + $lastYearAugust -> pad400_price * $lastYearAugust -> pad400_count+ 
                                                $lastYearAugust -> pad600_price * $lastYearAugust -> pad600_count + $lastYearAugust -> pad800_price * $lastYearAugust -> pad800_count + 
                                                $lastYearAugust -> pad1000_price * $lastYearAugust -> pad1000_count + $lastYearAugust -> pad1200_price * $lastYearAugust -> pad1200_count;
             
                    }else{
                        $lastYearAugustPrice = 0;
                    }
                    
                    //9月
                    $lastYearSeptember = $lastYearResult ->where('month','9') ->first();
                    if(isset($lastYearSeptember)){
                        $lastYearSeptemberPrice = $lastYearSeptember -> tapeM_price * $lastYearSeptember -> tapeM_count+ $lastYearSeptember -> tapeL_price * $lastYearSeptember -> tapeL_count + 
                                                $lastYearSeptember -> pantsM_price * $lastYearSeptember -> pantsM_count + $lastYearSeptember -> pantsL_price * $lastYearSeptember -> pantsL_count +
                                                $lastYearSeptember -> pad300_price  * $lastYearSeptember -> pad300_count + $lastYearSeptember -> pad400_price * $lastYearSeptember -> pad400_count+ 
                                                $lastYearSeptember -> pad600_price * $lastYearSeptember -> pad600_count + $lastYearSeptember -> pad800_price * $lastYearSeptember -> pad800_count + 
                                                $lastYearSeptember -> pad1000_price * $lastYearSeptember -> pad1000_count + $lastYearSeptember -> pad1200_price * $lastYearSeptember -> pad1200_count;
             
                    }else{
                        $lastYearSeptemberPrice = 0;
                    }
                    
                    //10月
                    $lastYearOctober = $lastYearResult ->where('month','10') ->first();
                    if(isset($lastYearOctober)){
                        $lastYearOctoberPrice = $lastYearOctober -> tapeM_price * $lastYearOctober -> tapeM_count+ $lastYearOctober -> tapeL_price * $lastYearOctober -> tapeL_count + 
                                                $lastYearOctober -> pantsM_price * $lastYearOctober -> pantsM_count + $lastYearOctober -> pantsL_price * $lastYearOctober -> pantsL_count +
                                                $lastYearOctober -> pad300_price  * $lastYearOctober -> pad300_count + $lastYearOctober -> pad400_price * $lastYearOctober -> pad400_count+ 
                                                $lastYearOctober -> pad600_price * $lastYearOctober -> pad600_count + $lastYearOctober -> pad800_price * $lastYearOctober -> pad800_count + 
                                                $lastYearOctober -> pad1000_price * $lastYearOctober -> pad1000_count + $lastYearOctober -> pad1200_price * $lastYearOctober -> pad1200_count;
                    }else{
                        $lastYearOctoberPrice = 0;
                    }
                    
                    //11月
                    $lastYearNovember = $lastYearResult ->where('month','11') ->first();
                    if(isset($lastYearNovember)){
                        $lastYearNovemberPrice =$lastYearNovember -> tapeM_price * $lastYearNovember -> tapeM_count+ $lastYearNovember -> tapeL_price * $lastYearNovember -> tapeL_count + 
                                                $lastYearNovember -> pantsM_price * $lastYearNovember -> pantsM_count + $lastYearNovember-> pantsL_price * $lastYearNovember -> pantsL_count +
                                                $lastYearNovember -> pad300_price  * $lastYearNovember -> pad300_count + $lastYearNovember -> pad400_price * $lastYearNovember -> pad400_count+ 
                                                $lastYearNovember -> pad600_price * $lastYearNovember -> pad600_count + $lastYearNovember -> pad800_price * $lastYearNovember -> pad800_count + 
                                                $lastYearNovember -> pad1000_price * $lastYearNovember -> pad1000_count + $lastYearNovember -> pad1200_price * $lastYearNovember -> pad1200_count;
             
                    }else{
                        $lastYearNovemberPrice = 0;
                    }
                    
                    //12月
                    $lastYearDecember = $lastYearResult ->where('month','12') ->first();
                    if(isset($lastYearDecember)){
                        $lastYearDecemberPrice = $lastYearDecember -> tapeM_price * $lastYearDecember -> tapeM_count+ $lastYearDecember -> tapeL_price * $lastYearDecember -> tapeL_count + 
                                                $lastYearDecember -> pantsM_price * $lastYearDecember -> pantsM_count + $lastYearDecember -> pantsL_price * $lastYearDecember -> pantsL_count +
                                                $lastYearDecember -> pad300_price  * $lastYearDecember -> pad300_count + $lastYearDecember -> pad400_price * $lastYearDecember -> pad400_count+ 
                                                $lastYearDecember -> pad600_price * $lastYearDecember -> pad600_count + $lastYearDecember -> pad800_price * $lastYearDecember -> pad800_count + 
                                                $lastYearDecember -> pad1000_price * $lastYearDecember -> pad1000_count + $lastYearDecember -> pad1200_price * $lastYearDecember -> pad1200_count;
             
                    }else{
                        $lastYearDecemberPrice = 0;
                    }
                    
                    
                    
                    return view('sales.result',['times'=> $times,'salesReport'=>$salesReport,'tape_group_count'=>$tape_group_count,'pants_group_count'=>$pants_group_count,'pad_group_count'=>$pad_group_count,'tape_group_price'=>$tape_group_price,
                                'pants_group_price'=>$pants_group_price,'pad_group_price'=>$pad_group_price,'years'=>$years,'pad300_count'=>$pad300_count,'pad400_count' => $pad400_count,'pad600_count' =>$pad600_count,
                                'pad800_count' =>$pad800_count,'pad1000_count' =>$pad1000_count,'pad1200_count' =>$pad1200_count,'pad300_price'=>$pad300_price,'pad400_price' => $pad400_price,'pad600_price' =>$pad600_price,
                                'pad800_price' =>$pad800_price,'pad1000_price' =>$pad1000_price,'pad1200_price' =>$pad1200_price,'client'=>$client,'TapeAutaPlans'=>$TapeAutaPlans,'TapeInnerPlans'=>$TapeInnerPlans,
                                'PantsAutaPlans'=>$PantsAutaPlans,'PantsInnerPlans'=>$PantsInnerPlans,'planTapeTotalCount' => $planTapeTotalCount,'planPantsTotalCount' => $planPantsTotalCount,'planTotalCount'=>$planTotalCount,'resultTotalCount'=>$resultTotalCount,
                                'planPadTotalCount' =>$planPadTotalCount,'planTotalPrice'=>$planTotalPrice,'resultTotalPrice' => $resultTotalPrice,'planTotalTapePrice' => $planTotalTapePrice,'planTotalPantsPrice' => $planTotalPantsPrice,'planTotalPadPrice'=>$planTotalPadPrice,
                                'thisYearJanuaryCount' => $thisYearJanuaryCount,'thisYearFebruaryCount' =>$thisYearFebruaryCount,'thisYearMarchCount' =>$thisYearMarchCount,'thisYearAprilCount'=>$thisYearAprilCount,'thisYearMayCount'=>$thisYearMayCount,
                                'thisYearJuneCount' => $thisYearJuneCount,'thisYearJulyCount' =>$thisYearJulyCount, 'thisYearAugustCount' => $thisYearAugustCount,'thisYearSeptemberCount' => $thisYearSeptemberCount,'thisYearOctoberCount' =>$thisYearOctoberCount,
                                'thisYearNovemberCount' => $thisYearNovemberCount,'thisYearDecemberCount' => $thisYearDecemberCount,
                                
                                'thisYearJanuaryPrice' => $thisYearJanuaryPrice,'thisYearFebruaryPrice' =>$thisYearFebruaryPrice,'thisYearMarchPrice' =>$thisYearMarchPrice,'thisYearAprilPrice'=>$thisYearAprilPrice,'thisYearMayPrice'=>$thisYearMayPrice,
                                'thisYearJunePrice' => $thisYearJunePrice,'thisYearJulyPrice' =>$thisYearJulyPrice, 'thisYearAugustPrice' => $thisYearAugustPrice,'thisYearSeptemberPrice' => $thisYearSeptemberPrice,'thisYearOctoberPrice' =>$thisYearOctoberPrice,
                                'thisYearNovemberPrice' => $thisYearNovemberPrice,'thisYearDecemberPrice' => $thisYearDecemberPrice,
                                
                                'lastYearJanuaryCount' => $lastYearJanuaryCount,'lastYearFebruaryCount' =>$lastYearFebruaryCount,'lastYearMarchCount' =>$lastYearMarchCount,'lastYearAprilCount'=>$lastYearAprilCount,'lastYearMayCount'=>$lastYearMayCount,
                                'lastYearJuneCount' => $lastYearJuneCount,'lastYearJulyCount' =>$lastYearJulyCount, 'lastYearAugustCount' => $lastYearAugustCount,'lastYearSeptemberCount' => $lastYearSeptemberCount,'lastYearOctoberCount' =>$lastYearOctoberCount,
                                'lastYearNovemberCount' => $lastYearNovemberCount,'lastYearDecemberCount' => $lastYearDecemberCount,
                                
                                'lastYearJanuaryPrice' => $lastYearJanuaryPrice,'lastYearFebruaryPrice' =>$lastYearFebruaryPrice,'lastYearMarchPrice' =>$lastYearMarchPrice,'lastYearAprilPrice'=>$lastYearAprilPrice,'lastYearMayPrice'=>$lastYearMayPrice,
                                'lastYearJunePrice' => $lastYearJunePrice,'lastYearJulyPrice' =>$lastYearJulyPrice, 'lastYearAugustPrice' => $lastYearAugustPrice,'lastYearSeptemberPrice' => $lastYearSeptemberPrice,'lastYearOctoberPrice' =>$lastYearOctoberPrice,
                                'lastYearNovemberPrice' => $lastYearNovemberPrice,'lastYearDecemberPrice' => $lastYearDecemberPrice,
                                
                                'lastYear' => $lastYear
                                ]);
                    
                }
            }
        
        
       
    }
    
    
    public function plan(Request $request){
        
        $times = array();
        for($i = 1 ; $i <=24 ; $i++){
            array_push($times,$i);
        }
        
        $clientId = $request -> id;
        $client = Client::find($clientId);
        
        $TapeAutaPlans = TapeAutaPlan::where('client_id',$clientId )->orderby('t_tape_exchange_time')->get();
        $TapeInnerPlans = TapeInnerPlan::where('client_id',$clientId )->orderby('t_pad_exchange_time')->get();
        $PantsAutaPlans = PantsAutaPlan::where('client_id',$clientId )->orderby('p_pants_exchange_time')->get();
        $PantsInnerPlans = PantsInnerPlan::where('client_id',$clientId )->orderby('p_pad_exchange_time')->get();
     
        //$TapeMPlanCount = $TapeAutaPlans->where('t_tape_item','tapeM')->get()->count();
        //$latestTapeMPrice = SalesReport::where('client_id',$clientId)->sortByDesc('year')->sortByDesc('month')->select('tapeM_price')->first();
        //$TapeMPerParsonOneDayPrice = $TapeMPlanCount * $latestTapeMPrice ;
        
        return view('sales.plan',['times'=> $times,'client'=>$client,'TapeAutaPlans'=>$TapeAutaPlans,'TapeInnerPlans'=>$TapeInnerPlans,'PantsAutaPlans'=>$PantsAutaPlans,'PantsInnerPlans'=>$PantsInnerPlans]);
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
       
       
        if( $request-> p_pants_exchange_time1 != null && $request -> p_pants_item1!= null){
        $pantsAutaPlan1 -> client_id = $request -> client_id;
        $pantsAutaPlan1 -> p_pants_exchange_time = $request -> p_pants_exchange_time1;
        $pantsAutaPlan1 -> p_pants_item = $request -> p_pants_item1;
        $pantsAutaPlan1 ->save();
        }
        if( $request-> p_pants_exchange_time2 != null && $request -> p_pants_item2 != null){
        $pantsAutaPlan2 -> client_id = $request -> client_id;
        $pantsAutaPlan2 -> p_pants_exchange_time = $request -> p_pants_exchange_time2;
        $pantsAutaPlan2 -> p_pants_item = $request -> p_pants_item2;
        $pantsAutaPlan2 ->save();
        }
        if( $request-> p_pants_exchange_time3 != null && $request -> p_pants_item3 != null){
        $pantsAutaPlan3 -> client_id = $request -> client_id;
        $pantsAutaPlan3 -> p_pants_exchange_time = $request -> p_pants_exchange_time3;
        $pantsAutaPlan3 -> p_pants_item = $request -> p_pants_item3;
        $pantsAutaPlan3 ->save();
        }
        if( $request-> p_pants_exchange_time4 != null && $request -> p_pants_item4 != null){
        $pantsAutaPlan4 -> client_id = $request -> client_id;
        $pantsAutaPlan4 -> p_pants_exchange_time = $request -> p_pants_exchange_time4;
        $pantsAutaPlan4 -> p_pants_item = $request -> p_pants_item4;
        $pantsAutaPlan4 ->save();
        }
        if( $request-> p_pants_exchange_time5 != null && $request -> p_pants_item5 != null){
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
        
        $times = array();
        for($i = 1 ; $i <=24 ; $i++){
            array_push($times,$i);
        }
        
        $clientId = $request -> client_id;
        $client = Client::find($clientId);
        
        $TapeAutaPlans = TapeAutaPlan::where('client_id',$clientId )->orderby('t_tape_exchange_time')->get();
        $TapeInnerPlans = TapeInnerPlan::where('client_id',$clientId )->orderby('t_pad_exchange_time')->get();
        $PantsAutaPlans = PantsAutaPlan::where('client_id',$clientId )->orderby('p_pants_exchange_time')->get();
        $PantsInnerPlans = PantsInnerPlan::where('client_id',$clientId )->orderby('p_pad_exchange_time')->get();
        
        return view('sales.plan',['times'=> $times,'client'=>$client,'TapeAutaPlans'=>$TapeAutaPlans,'TapeInnerPlans'=>$TapeInnerPlans,'PantsAutaPlans'=>$PantsAutaPlans,'PantsInnerPlans'=>$PantsInnerPlans]);
        
    }
    
    public function planDelete(Request $request){
        
        $times = array();
        for($i = 1 ; $i <=24 ; $i++){
            array_push($times,$i);
        }
        
        $clientId = $request -> id;
        $client = Client::find($clientId);
        if (empty($client)){
            abort(404);
        }
        
        $tapeAutaPlans = TapeAutaPlan::where('client_id',$clientId);
        $tapeInnerPlans = TapeInnerPlan::where('client_id',$clientId);
        $pantsAutaPlans = PantsAutaPlan::where('client_id',$clientId);
        $pantsInnerPlans = PantsInnerPlan::where('client_id',$clientId);
        
        //トランザクション処理
        DB::transaction( function() use($tapeAutaPlans,$tapeInnerPlans,$pantsAutaPlans,$pantsInnerPlans){
        $tapeAutaPlans->delete();
        $tapeInnerPlans ->delete();
        $pantsAutaPlans ->delete();
        $pantsInnerPlans ->delete();
        });
        
        $times = array();
        for($i = 1 ; $i <=24 ; $i++){
            array_push($times,$i);
        }
        
        $clientId = $request -> id;
        $client = Client::find($clientId);
        
        $TapeAutaPlans = TapeAutaPlan::where('client_id',$clientId )->orderby('t_tape_exchange_time')->get();
        $TapeInnerPlans = TapeInnerPlan::where('client_id',$clientId )->orderby('t_pad_exchange_time')->get();
        $PantsAutaPlans = PantsAutaPlan::where('client_id',$clientId )->orderby('p_pants_exchange_time')->get();
        $PantsInnerPlans = PantsInnerPlan::where('client_id',$clientId )->orderby('p_pad_exchange_time')->get();
     
        return view('sales.plan',['times'=> $times,'id'=>$clientId,'client'=>$client,'TapeAutaPlans'=>$TapeAutaPlans,'TapeInnerPlans'=>$TapeInnerPlans,'PantsAutaPlans'=>$PantsAutaPlans,'PantsInnerPlans'=>$PantsInnerPlans]);
        
        
        //return view('sales.',['client' => $client]);
    }
    
    public function clientTop(Request $request){
        
        $clientId = $request -> id;
        $client = Client::find($clientId);
        
        return view('sales.clientTop',['client'=>$client]);
        
    }
    
}
