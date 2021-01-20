<?php

namespace App\Http\Controllers\Admin;

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
use App\Admin;

class SalesController extends Controller
{
    
    public function top(){
        return view('admin.top');
    }
    //
    public function add(Request $request){
        
        $client = Client::find($request->id);

        $year = date('Y');
        $years = array();
        for($i=$year ;$i >= 2018;$i--){
            array_push($years,$i);
        }
        return view('admin.sales.create',[ 'years' => $years,'client'=>$client]);
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
        
        return view('admin.sales.clientTop',['client' => $client]);
        
    }
        
    
    public function edit(Request $request){
        $clients = Client::find($request->id);
        if (empty($clients)){
            abort(404);
        }
        return view('admin.sales.create',['clients' =>$client]);
    }
    
    public function list(Request $request){
        
        //施設検索あり
        $user_name = $request -> user_name;
        if($user_name != ''){
            $searchClients = Client::where('user_name','like','%'.$user_name.'%')->get();
            $searchClientsCount = $searchClients -> count();
            return view('admin.clients.list',['searchClients' => $searchClients,'searchClientsCount' => $searchClientsCount]);
        
        //施設検索なし（初期アクセス）    
        }else{
            $clients = Client::all();
            return view('admin.clients.list',['clients' => $clients]);
        }
       
    }
    
    public function result(Request $request){
        
        $salesReports = SalesReport::where('client_id',$request->id)->get();
        
        //年月選択用（プルダウン）
        $thisYear= date('Y');
        $years = array();
        for($i=$thisYear ;$i >= 2018;$i--){
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
                    //年間実績
                    //今年
                    $thisYearResults = SalesReport::where('client_id',$request->id) -> where('year',$thisYear) ->get();
                    
                    //枚数・金額
                    $thisYearCount = array();
                    $thisYearPrice = array();
                    for ($i = 1 ; $i<=12 ; $i++){
                        $thisYearResult = $thisYearResults -> where('month',$i)->first();
                        if(isset($thisYearResult)){
                        $monthCount = $thisYearResult -> tapeM_count + $thisYearResult ->tapeL_count + $thisYearResult -> pantsM_count + $thisYearResult -> pantsL_count +
                                        $thisYearResult -> pad300_count + $thisYearResult -> pad400_count + $thisYearResult -> pad600_count + $thisYearResult -> pad800_count + 
                                        $thisYearResult -> pad1000_count + $thisYearResult -> pad1200_count;
                        $monthPrice = $thisYearResult -> tapeM_count * $thisYearResult -> tapeM_price + $thisYearResult ->tapeL_count * $thisYearResult ->tapeL_price +
                                      $thisYearResult -> pantsM_count *$thisYearResult -> pantsM_price+ $thisYearResult -> pantsL_count *$thisYearResult -> pantsL_price+
                                      $thisYearResult -> pad300_count * $thisYearResult -> pad300_price + $thisYearResult -> pad400_count * $thisYearResult -> pad400_price+ 
                                      $thisYearResult -> pad600_count * $thisYearResult -> pad600_price + $thisYearResult -> pad800_count * $thisYearResult -> pad800_price+ 
                                      $thisYearResult -> pad1000_count *$thisYearResult -> pad1000_price + $thisYearResult -> pad1200_count *$thisYearResult -> pad1200_price;             
                        
                        }else{
                            $monthCount = 0;
                            $monthPrice = 0;
                        }
                        
                        array_push($thisYearCount,$monthCount);
                        array_push($thisYearPrice,$monthPrice);
                    }
                    
                    
                    //昨年
                    $lastYear = $thisYear - 1;
                    $lastYearResults = SalesReport::where('client_id',$request->id) -> where('year',$lastYear) ->get();
                    
                    $lastYearCount = array();
                    $lastYearPrice = array();
                    for ($i = 1 ; $i<=12 ; $i++){
                        $lastYearResult = $lastYearResults -> where('month',$i)->first();
                        if(isset($lastYearResult)){
                        $monthCount = $lastYearResult -> tapeM_count + $lastYearResult ->tapeL_count + $lastYearResult -> pantsM_count + $lastYearResult -> pantsL_count +
                                        $lastYearResult -> pad300_count + $lastYearResult -> pad400_count + $lastYearResult -> pad600_count + $lastYearResult -> pad800_count + 
                                        $lastYearResult -> pad1000_count + $lastYearResult -> pad1200_count;
                        $monthPrice = $lastYearResult -> tapeM_count * $lastYearResult -> tapeM_price + $lastYearResult ->tapeL_count * $lastYearResult ->tapeL_price +
                                      $lastYearResult -> pantsM_count *$lastYearResult -> pantsM_price+ $lastYearResult -> pantsL_count *$lastYearResult -> pantsL_price+
                                      $lastYearResult -> pad300_count * $lastYearResult -> pad300_price + $lastYearResult -> pad400_count * $lastYearResult -> pad400_price+ 
                                      $lastYearResult -> pad600_count * $lastYearResult -> pad600_price + $lastYearResult -> pad800_count * $lastYearResult -> pad800_price+ 
                                      $lastYearResult -> pad1000_count *$lastYearResult -> pad1000_price + $lastYearResult -> pad1200_count *$lastYearResult -> pad1200_price;             
                        
                        }else{
                            $monthCount = 0;
                            $monthPrice = 0;
                        }
                        
                        array_push($lastYearCount,$monthCount);
                        array_push($lastYearPrice,$monthPrice);
                    }
                    
                    return view('admin.sales.result',['client' => $client,'salesReport' => $salesReport,'years'=>$years,'selectYear' => $selectYear,'month' => $month,
                               'lastYear' => $lastYear,  'thisYearCount'=> $thisYearCount,'thisYearPrice' => $thisYearPrice,'lastYearCount'=> $lastYearCount,'lastYearPrice' => $lastYearPrice,'thisYear' => $thisYear
                               ]);
                
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
                    $thisYearResults = SalesReport::where('client_id',$request->id) -> where('year',$thisYear) ->get();
                    
                    //枚数・金額
                    $thisYearCount = array();
                    $thisYearPrice = array();
                    for ($i = 1 ; $i<=12 ; $i++){
                        $thisYearResult = $thisYearResults -> where('month',$i)->first();
                        if(isset($thisYearResult)){
                        $monthCount = $thisYearResult -> tapeM_count + $thisYearResult ->tapeL_count + $thisYearResult -> pantsM_count + $thisYearResult -> pantsL_count +
                                        $thisYearResult -> pad300_count + $thisYearResult -> pad400_count + $thisYearResult -> pad600_count + $thisYearResult -> pad800_count + 
                                        $thisYearResult -> pad1000_count + $thisYearResult -> pad1200_count;
                        $monthPrice = $thisYearResult -> tapeM_count * $thisYearResult -> tapeM_price + $thisYearResult ->tapeL_count * $thisYearResult ->tapeL_price +
                                      $thisYearResult -> pantsM_count *$thisYearResult -> pantsM_price+ $thisYearResult -> pantsL_count *$thisYearResult -> pantsL_price+
                                      $thisYearResult -> pad300_count * $thisYearResult -> pad300_price + $thisYearResult -> pad400_count * $thisYearResult -> pad400_price+ 
                                      $thisYearResult -> pad600_count * $thisYearResult -> pad600_price + $thisYearResult -> pad800_count * $thisYearResult -> pad800_price+ 
                                      $thisYearResult -> pad1000_count *$thisYearResult -> pad1000_price + $thisYearResult -> pad1200_count *$thisYearResult -> pad1200_price;             
                        
                        }else{
                            $monthCount = 0;
                            $monthPrice = 0;
                        }
                        
                        array_push($thisYearCount,$monthCount);
                        array_push($thisYearPrice,$monthPrice);
                    }
                    
                    
                    //昨年
                    $lastYear = $thisYear - 1;
                    $lastYearResults = SalesReport::where('client_id',$request->id) -> where('year',$lastYear) ->get();
                    
                    $lastYearCount = array();
                    $lastYearPrice = array();
                    for ($i = 1 ; $i<=12 ; $i++){
                        $lastYearResult = $lastYearResults -> where('month',$i)->first();
                        if(isset($lastYearResult)){
                        $monthCount = $lastYearResult -> tapeM_count + $lastYearResult ->tapeL_count + $lastYearResult -> pantsM_count + $lastYearResult -> pantsL_count +
                                        $lastYearResult -> pad300_count + $lastYearResult -> pad400_count + $lastYearResult -> pad600_count + $lastYearResult -> pad800_count + 
                                        $lastYearResult -> pad1000_count + $lastYearResult -> pad1200_count;
                        $monthPrice = $lastYearResult -> tapeM_count * $lastYearResult -> tapeM_price + $lastYearResult ->tapeL_count * $lastYearResult ->tapeL_price +
                                      $lastYearResult -> pantsM_count *$lastYearResult -> pantsM_price+ $lastYearResult -> pantsL_count *$lastYearResult -> pantsL_price+
                                      $lastYearResult -> pad300_count * $lastYearResult -> pad300_price + $lastYearResult -> pad400_count * $lastYearResult -> pad400_price+ 
                                      $lastYearResult -> pad600_count * $lastYearResult -> pad600_price + $lastYearResult -> pad800_count * $lastYearResult -> pad800_price+ 
                                      $lastYearResult -> pad1000_count *$lastYearResult -> pad1000_price + $lastYearResult -> pad1200_count *$lastYearResult -> pad1200_price;             
                        
                        }else{
                            $monthCount = 0;
                            $monthPrice = 0;
                        }
                        
                        array_push($lastYearCount,$monthCount);
                        array_push($lastYearPrice,$monthPrice);
                    }
                    
                }    
            
            //年月の指定なし（初期アクセス）        
            }else{
                $salesReport = $salesReports->sortByDesc('year')->sortByDesc('month')->first();
  
                $client = Client::find($clientId);
                
                //実績登録なし
                if(empty($salesReport)){
                    
                     //年月選択用
                    $thisYear = date('Y');
                    $lastYear = $thisYear -1;
                    $thisYearCount = null;
                    $years = array();
                    for($i=$thisYear ;$i >= 2018;$i--){
                        array_push($years,$i);
                    }
                    
                    return view('admin.sales.result',['client' => $client,'salesReport' => $salesReport,'years'=>$years,'thisYear' => $thisYear,'lastYear' => $lastYear,'thisYearCount' => $thisYearCount]);
                
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
                    $thisYearResults = SalesReport::where('client_id',$request->id) -> where('year',$thisYear) ->get();
                    
                    //枚数・金額
                    $thisYearCount = array();
                    $thisYearPrice = array();
                    for ($i = 1 ; $i<=12 ; $i++){
                        $thisYearResult = $thisYearResults -> where('month',$i)->first();
                        if(isset($thisYearResult)){
                        $monthCount = $thisYearResult -> tapeM_count + $thisYearResult ->tapeL_count + $thisYearResult -> pantsM_count + $thisYearResult -> pantsL_count +
                                        $thisYearResult -> pad300_count + $thisYearResult -> pad400_count + $thisYearResult -> pad600_count + $thisYearResult -> pad800_count + 
                                        $thisYearResult -> pad1000_count + $thisYearResult -> pad1200_count;
                        $monthPrice = $thisYearResult -> tapeM_count * $thisYearResult -> tapeM_price + $thisYearResult ->tapeL_count * $thisYearResult ->tapeL_price +
                                      $thisYearResult -> pantsM_count *$thisYearResult -> pantsM_price+ $thisYearResult -> pantsL_count *$thisYearResult -> pantsL_price+
                                      $thisYearResult -> pad300_count * $thisYearResult -> pad300_price + $thisYearResult -> pad400_count * $thisYearResult -> pad400_price+ 
                                      $thisYearResult -> pad600_count * $thisYearResult -> pad600_price + $thisYearResult -> pad800_count * $thisYearResult -> pad800_price+ 
                                      $thisYearResult -> pad1000_count *$thisYearResult -> pad1000_price + $thisYearResult -> pad1200_count *$thisYearResult -> pad1200_price;             
                        
                        }else{
                            $monthCount = 0;
                            $monthPrice = 0;
                        }
                        
                        array_push($thisYearCount,$monthCount);
                        array_push($thisYearPrice,$monthPrice);
                    }
                    
                    
                    //昨年
                    $lastYear = $thisYear - 1;
                    $lastYearResults = SalesReport::where('client_id',$request->id) -> where('year',$lastYear) ->get();
                    
                    $lastYearCount = array();
                    $lastYearPrice = array();
                    for ($i = 1 ; $i<=12 ; $i++){
                        $lastYearResult = $lastYearResults -> where('month',$i)->first();
                        if(isset($lastYearResult)){
                        $monthCount = $lastYearResult -> tapeM_count + $lastYearResult ->tapeL_count + $lastYearResult -> pantsM_count + $lastYearResult -> pantsL_count +
                                        $lastYearResult -> pad300_count + $lastYearResult -> pad400_count + $lastYearResult -> pad600_count + $lastYearResult -> pad800_count + 
                                        $lastYearResult -> pad1000_count + $lastYearResult -> pad1200_count;
                        $monthPrice = $lastYearResult -> tapeM_count * $lastYearResult -> tapeM_price + $lastYearResult ->tapeL_count * $lastYearResult ->tapeL_price +
                                      $lastYearResult -> pantsM_count *$lastYearResult -> pantsM_price+ $lastYearResult -> pantsL_count *$lastYearResult -> pantsL_price+
                                      $lastYearResult -> pad300_count * $lastYearResult -> pad300_price + $lastYearResult -> pad400_count * $lastYearResult -> pad400_price+ 
                                      $lastYearResult -> pad600_count * $lastYearResult -> pad600_price + $lastYearResult -> pad800_count * $lastYearResult -> pad800_price+ 
                                      $lastYearResult -> pad1000_count *$lastYearResult -> pad1000_price + $lastYearResult -> pad1200_count *$lastYearResult -> pad1200_price;             
                        
                        }else{
                            $monthCount = 0;
                            $monthPrice = 0;
                        }
                        
                        array_push($lastYearCount,$monthCount);
                        array_push($lastYearPrice,$monthPrice);
                    }
                   
                }
                
            }
            
             return view('admin.sales.result',['times'=> $times,'salesReport'=>$salesReport,'tape_group_count'=>$tape_group_count,'pants_group_count'=>$pants_group_count,'pad_group_count'=>$pad_group_count,'tape_group_price'=>$tape_group_price,
                                'pants_group_price'=>$pants_group_price,'pad_group_price'=>$pad_group_price,'years'=>$years,'pad300_count'=>$pad300_count,'pad400_count' => $pad400_count,'pad600_count' =>$pad600_count,
                                'pad800_count' =>$pad800_count,'pad1000_count' =>$pad1000_count,'pad1200_count' =>$pad1200_count,'pad300_price'=>$pad300_price,'pad400_price' => $pad400_price,'pad600_price' =>$pad600_price,
                                'pad800_price' =>$pad800_price,'pad1000_price' =>$pad1000_price,'pad1200_price' =>$pad1200_price,'client'=>$client,'TapeAutaPlans'=>$TapeAutaPlans,'TapeInnerPlans'=>$TapeInnerPlans,
                                'PantsAutaPlans'=>$PantsAutaPlans,'PantsInnerPlans'=>$PantsInnerPlans,'planTapeTotalCount' => $planTapeTotalCount,'planPantsTotalCount' => $planPantsTotalCount,'planTotalCount'=>$planTotalCount,'resultTotalCount'=>$resultTotalCount,
                                'planPadTotalCount' =>$planPadTotalCount,'planTotalPrice'=>$planTotalPrice,'resultTotalPrice' => $resultTotalPrice,'planTotalTapePrice' => $planTotalTapePrice,'planTotalPantsPrice' => $planTotalPantsPrice,'planTotalPadPrice'=>$planTotalPadPrice,
                                
                                'lastYear' => $lastYear,  'thisYearCount'=> $thisYearCount,'thisYearPrice' => $thisYearPrice,'lastYearCount'=> $lastYearCount,'lastYearPrice' => $lastYearPrice,'thisYear' => $thisYear
                                ]);
                    
        
        
       
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
        
        return view('admin.sales.plan',['times'=> $times,'client'=>$client,'TapeAutaPlans'=>$TapeAutaPlans,'TapeInnerPlans'=>$TapeInnerPlans,'PantsAutaPlans'=>$PantsAutaPlans,'PantsInnerPlans'=>$PantsInnerPlans]);
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
        
        return view('admin.sales.plan',['times'=> $times,'client'=>$client,'TapeAutaPlans'=>$TapeAutaPlans,'TapeInnerPlans'=>$TapeInnerPlans,'PantsAutaPlans'=>$PantsAutaPlans,'PantsInnerPlans'=>$PantsInnerPlans]);
        
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
     
        return view('admin.sales.plan',['times'=> $times,'id'=>$clientId,'client'=>$client,'TapeAutaPlans'=>$TapeAutaPlans,'TapeInnerPlans'=>$TapeInnerPlans,'PantsAutaPlans'=>$PantsAutaPlans,'PantsInnerPlans'=>$PantsInnerPlans]);
        
        
        //return view('sales.',['client' => $client]);
    }
    
    public function clientTop(Request $request){
        
        $clientId = $request -> id;
        $client = Client::find($clientId);
        
        return view('admin.sales.clientTop',['client'=>$client]);
        
    }
    
    public function adminsList(){
        
        $admins = Admin::all();
        
        return view('admin.sales.adminsList',['admins' => $admins]);
        
    }
    
    public function adminDelete(Request $request){
        
        $adminId = $request -> id;
        $admin = Admin::find($adminId);
        
        DB::transaction( function() use($admin){
        $admin->delete();
        });
        
        $admins = Admin::all();
        
        return view('admin.sales.adminsList',['admins' => $admins]);
        
    }
    
}
