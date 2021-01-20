{{-- layouts/admin.blade.phpを読み込む --}}
@extends('layouts.client')
        
        {{-- admin.blade.phpの@yield('title')に'ニュースの新規作成'を埋め込む --}}
        @section('title','実績')
        
        {{-- admin.blade.phpの@yield('content')に以下のタグを埋め込む --}}
        @section('content')
                    <div class= "container">
                        <div class="row">
                            <div class="col-md-8 mx-auto text-center">
                                <h3　class="client-top">納品先名　：　{{ $client ->user_name }} ({{$client -> number_of_bed }}床)</h3>
                            </div>
                        </div>
                        <div class = "row">
                            <div class="col-md-8 mx-auto">
                                <a href="{{ action('Admin\SalesController@plan',['id'=> $client->id ]) }}" role="button" class="btn btn-primary">目標設定</a>
                                <a href="{{ action('Admin\SalesController@create',['id'=> $client->id ])}}" role="button" class="btn btn-primary">実績入力</a>
                                <a href="{{ action('Admin\ClientsController@add2',['id' => $client ->id ]) }}" role="button" class="btn btn-primary">施設情報変更</a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8 mx-auto">
                                <h1>設定目標</h1>
                                <h2>テープタイプ使用者</h2>
                                <div class="float-left">
                                    <p>アウター</p>
                                    @if(isset($TapeAutaPlans) && count($TapeAutaPlans) >= 1)
                                    <table>
                                        <tr>
                                            <th>回数</th>
                                            <th>交換時間</th>
                                            <th>商品名</th>
                                        </tr>
                                        @foreach($TapeAutaPlans as $TapeAutaPlan)
                                        <tr>
                                            <td>{{ $loop ->iteration }}回目</td>
                                            <td>{{$TapeAutaPlan->t_tape_exchange_time}}時</td>
                                            <td>{{$TapeAutaPlan->t_tape_item}}</td>
                                        </tr>
                                    </table>
                                        @endforeach
                                    @else
                                        <p>未設定</p>
                                    @endif
                                </div>
                                <div class="float-right">
                                    <p>インナー</p>
                                    @if(isset($TapeInnerPlans) && count($TapeInnerPlans) >= 1)
                                    <table>
                                        <tr>
                                            <th>回数</th>
                                            <th>交換時間</th>
                                            <th>商品名</th>
                                        </tr>
                                        @foreach($TapeInnerPlans as $TapeInnerPlan)
                                        <tr>
                                            <td>{{ $loop ->iteration }}回目</td>
                                            <td>{{$TapeInnerPlan->t_pad_exchange_time}}時</td>
                                            <td>{{$TapeInnerPlan->t_pad_item}}</td>
                                        </tr>
                                    </table>
                                        @endforeach
                                    @else
                                        <p>未設定　　　　　　　　　　　　　　　　</p>
                                    @endif
                                </div>
                                <div style="clear:both"></div>
                            </div>
                        </div>
                            <div class="row">
                                <div class="col-md-8 mx-auto">
                                    <h2>パンツタイプ使用者</h2>
                                    <div class="float-left">
                                        <p>アウター</p>
                                        @if(isset($PantsAutaPlans) && count($PantsAutaPlans) >= 1)
                                        <table>
                                            <tr>
                                                <th>回数</th>
                                                <th>交換時間</th>
                                                <th>商品名</th>
                                            </tr>
                                            @foreach($PantsAutaPlans as $PantsAutaPlan)
                                            <tr>
                                                <td>{{ $loop ->iteration }}回目</td>
                                                <td>{{$PantsAutaPlan->p_pants_exchange_time}}時</td>
                                                <td>{{$PantsAutaPlan->p_pants_item}}</td>
                                            </tr>
                                        </table>
                                            @endforeach
                                        @else
                                            <p>未設定</p>
                                        @endif
                                    </div>
                                    <div class="float-right">
                                        <p>インナー</p>
                                        @if(isset($PantsInnerPlans) && count($PantsInnerPlans) >= 1)
                                        <table>
                                            <tr>
                                                <th>回数</th>
                                                <th>交換時間</th>
                                                <th>商品名</th>
                                            </tr>
                                            @foreach($PantsInnerPlans as $PantsInnerPlan)
                                            <tr>
                                                <td>{{ $loop ->iteration }}回目</td>
                                                <td>{{$PantsInnerPlan->p_pad_exchange_time}}時</td>
                                                <td>{{$PantsInnerPlan->p_pad_item}}</td>
                                            </tr>
                                        </table>
                                            @endforeach
                                        @else
                                            <p>未設定　　　　　　　　　　　　　　　　</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div style="clear:both"></div>
                        <div class= "row">
                            <div class="col-md-8 mx-auto">
                                <h1>実績</h1>
                                <div class="d-flex justify-content-end">
                                <form action="{{action('Admin\SalesController@result')}}" method="post" >
                                    <div class="form-group row">
                                        <p>実績年月選択</p>
                                        <select name="year">
                                            @foreach($years as $year)
                                                <option value="{{ $year }}">{{ $year }}年</option>
                                            @endforeach
                                        </select>
                                        <select name="month">
                                            <option value="4">4月</option>
                                            <option value="5">5月</option>
                                            <option value="6">6月</option>
                                            <option value="7">7月</option>
                                            <option value="8">8月</option>
                                            <option value="9">9月</option>
                                            <option value="10">10月</option>
                                            <option value="11">11月</option>
                                            <option value="12">12月</option>
                                            <option value="1">1月</option>
                                            <option value="2">2月</option>
                                            <option value="3">3月</option>
                                        </select>
                                        <input type="hidden" name="id" value="{{ $client -> id }}">
                                        @csrf
                                        <input type="submit" class="btn btn-primary" value="確認">
                                    </div>
                                </div>
                                @if( isset($salesReport) )
                                <h2>{{ $salesReport->year }}年{{ $salesReport->month}}月度実績</h2>
                                <p>使用枚数</p>
                                <div class="float-left" sytle="width: 50% ; height:400px" >
                                    <p>品群</p>
                                    <canvas id="chart1" ></canvas>
                                </div>
                                <div class="float-right" sytle="width: 50% ; height:400px">
                                    <p>パッド内訳</p>
                                    <canvas id="chart2" ></canvas>
                                </div>
                                <div style="clear:both"></div>
                                <p>販売金額</p>
                                <div class="float-left" sytle="width: 50% ; height:400px">
                                    <p>品群</p>
                                    <canvas id="chart3" ></canvas>
                                </div>
                                <div class="float-right" sytle="width: 50% ; height:400px">
                                    <p>パッド内訳</p>
                                    <canvas id="chart4" ></canvas>
                                </div>
                                <div style="clear:both"></div>
                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8 mx-auto">
                                <h1>比較</h1>
                                <h2>枚数</h2>
                                <table>
                                    <tr>
                                        <th width="15%"></th>
                                        <th width="20%">合計</th>
                                        <th width="20%">テープタイプ</th>
                                        <th width="20%">パンツタイプ</th>
                                        <th width="20%">パッドタイプ</th>
                                    </tr>
                                    <tr>
                                        <td>目標枚数</td>
                                        <td>{{ $planTotalCount}}</td>
                                        <td>{{ $planTapeTotalCount}}</td>
                                        <td>{{ $planPantsTotalCount}}</td>
                                        <td>{{ $planPadTotalCount}}</td>
                                    </tr>
                                    <tr>
                                        <td>実績枚数</td>
                                        <td>{{$resultTotalCount}}</td>
                                        <td>{{ $tape_group_count}}</td>
                                        <td>{{ $pants_group_count}}</td>
                                        <td>{{ $pad_group_count}}</td>
                                    </tr>
                                    <tr>
                                        <td>差額</td>
                                        <td>{{ $resultTotalCount - $planTotalCount}}</td>
                                        <td>{{ $tape_group_count - $planTapeTotalCount}}</td>
                                        <td>{{ $pants_group_count - $planPantsTotalCount}}</td>
                                        <td>{{ $pad_group_count - $planPadTotalCount}}</td>
                                    </tr>
                                </table>
                                <br>
                                <h2>金額</h2>
                                <table>
                                    <tr>
                                        <th width="15%"></th>
                                        <th width="20%">合計</th>
                                        <th width="20%">テープタイプ</th>
                                        <th width="20%">パンツタイプ</th>
                                        <th width="20%">パッドタイプ</th>
                                    </tr>
                                    <tr>
                                        <td>目標金額</td>
                                        <td>{{ $planTotalPrice}}</td>
                                        <td>{{ $planTotalTapePrice}}</td>
                                        <td>{{ $planTotalPantsPrice}}</td>
                                        <td>{{ $planTotalPadPrice}}</td>
                                    </tr>
                                    <tr>
                                        <td>実績金額</td>
                                        <td>{{ $resultTotalPrice}}</td>
                                        <td>{{ $tape_group_price}}</td>
                                        <td>{{ $pants_group_price}}</td>
                                        <td>{{ $pad_group_price}}</td>
                                    </tr>
                                    <tr>
                                        <td>差額</td>
                                        <td>{{ $resultTotalPrice - $planTotalPrice}}</td>
                                        <td>{{ $tape_group_price - $planTotalTapePrice}}</td>
                                        <td>{{ $pants_group_price - $planTotalPantsPrice}}</td>
                                        <td>{{ $pad_group_price - $planTotalPadPrice}}</td>
                                    </tr>
                                </table>
                                <br>
                                @elseif(empty($salesReport) && isset($selectYear) && isset($month))
                                    <P>{{$selectYear}}年{{$month}}月の実績登録はありません。</P>
                                @else
                                    <p>実績登録がありません。</p>
                                @endif
                            </div>
                        </div>
                            <div class="row">
                            <div class="col-md-8 mx-auto">
                                <h1>年間実績</h1>
                                @if(isset($thisYearCount))
                                <p>使用枚数</p>
                                	<div style="width:75%;">
		                                <canvas id="chart5"></canvas>
	                                </div>
                            	<br>
                            	<p>販売金額</p>
                                	<div style="width:75%;">
		                                <canvas id="chart6"></canvas>
	                                </div>
                            	<br>
                            	@else
                            	    <p>実績登録がありません。</p>
                            	@endif
	                            <script>
                                    window.onload = function(){
                                        var ctx1 = document.getElementById('chart1').getContext('2d');
                                        var ctx2 = document.getElementById('chart2').getContext('2d');
                                        var ctx3 = document.getElementById('chart3').getContext('2d');
                                        var ctx4 = document.getElementById('chart4').getContext('2d');
                                        var ctx5 = document.getElementById('chart5').getContext('2d');
                                        var ctx6 = document.getElementById('chart6').getContext('2d');
                                        
                                    @if( isset($salesReport) )
                                        var chart1 = new Chart(ctx1, {
                                        	type: 'pie',
                                        	data: {
                                            labels: ['テープ', 'パンツ', 'パッド'],
                                            datasets: [{
                                            label: '品群別（枚数）',
                                            data: [ {{$tape_group_count}},{{$pants_group_count}},{{$pad_group_count}}],
                                            backgroundColor:    ['#a0c238','#f2cf01','#65ace4'],
                                            borderColor: ['#a0c238','#f2cf01','#65ace4']
                                            }]
                                        },
                                        options: {}
                                        });
                                     
                                        var chart2 = new Chart(ctx2, {
                                        	type: 'pie',
                                        	data: {
                                        	labels: ['パッド300', 'パッド400', 'パッド600','パッド800','パッド1000','パッド1200'],
                                        	datasets: [{
                                        	label: 'パッド内訳（枚数）',
                                        	data: [ {{$pad300_count}},{{$pad400_count}},{{$pad600_count}},{{$pad800_count}},{{$pad1000_count}},{{$pad1200_count}}],
                                        	backgroundColor: ['#0074bf','#56a764','#d06d8c','#9460a0','#fff001','#65ace4'],
                                        	borderColor: ['#0074bf','#56a764','#d06d8c','#9460a0','#fff001','#65ace4']
                                        	    }]
                                        	},
                                        options: {}
                                        	});
                                        
                                        var chart3 = new Chart(ctx3, {
                                        	type: 'pie',
                                        	data: {
                                            labels: ['テープ', 'パンツ', 'パッド'],
                                            datasets: [{
                                            label: '品群別（金額）',
                                            data: [ {{$tape_group_price}},{{$pants_group_price}},{{$pad_group_price}}],
                                            backgroundColor: ['#a0c238','#f2cf01','#65ace4'],
                                            borderColor: ['#a0c238','#f2cf01','#65ace4']
                                            }]
                                        },
                                        options: {}
                                        });
                                        
                                        var chart4 = new Chart(ctx4, {
                                        	type: 'pie',
                                        	data: {
                                        	labels: ['パッド300', 'パッド400', 'パッド600','パッド800','パッド1000','パッド1200'],
                                        	datasets: [{
                                        	label: 'パッド内訳（金額）',
                                        	data: [ {{$pad300_price}},{{$pad400_price}},{{$pad600_price}},{{$pad800_price}},{{$pad1000_price}},{{$pad1200_price}}],
                                        	backgroundColor: ['#0074bf','#56a764','#d06d8c','#9460a0','#fff001','#65ace4'],
                                        	borderColor: ['#0074bf','#56a764','#d06d8c','#9460a0','#fff001','#65ace4']
                                        	    }]
                                        	},
                                        options: {}
                                        	});
                                     @endif       
                                      
                                     @if(isset($thiYearCount)) 
                                        var chart5 = new Chart(ctx5,{
                                    			type: 'line',
                                    			data: {
                                    				labels: ['1月', '2月', '3月', '4月', '5月', '6月', '7月','8月','9月','10月','11月','12月'],
                                    				datasets: [{
                                    					label: '{{ $thisYear}}年',
                                    					
                                    					borderColor: '#65ace4',
                                    					data: [
                                    					    @for($i= 0 ;$i<= 11 ; $i++ )
                                    						    {{$thisYearCount[$i]}},
                                                            @endfor
                                    					],
                                    				}, {
                                    					label: '{{ $lastYear }}年',
                                    					
                                    					borderColor: '#f2cf01',
                                    					data: [
                                    					     @for($i= 0 ;$i<= 11 ; $i++ )
                                    						    {{$lastYearCount[$i]}},
                                                            @endfor
                                    					],
                                    				}]
                                    			},
                                    			options: {}
                                    		});
                                    		
                                    		var chart6 = new Chart(ctx6,{
                                    			type: 'line',
                                    			data: {
                                    				labels: ['1月', '2月', '3月', '4月', '5月', '6月', '7月','8月','9月','10月','11月','12月'],
                                    				datasets: [{
                                    					label: '{{ $thisYear}}年',
                                    					
                                    					borderColor: '#65ace4',
                                    					data: [
                                    						@for($i= 0 ;$i<= 11 ; $i++ )
                                    						    {{$thisYearPrice[$i]}},
                                                            @endfor
                    
                                    					],
                                    				}, {
                                    					label: '{{ $lastYear }}年',
                                    					
                                    					borderColor: '#f2cf01',
                                    					data: [
                                    						@for($i= 0 ;$i<= 11 ; $i++ )
                                    						    {{$lastYearPrice[$i]}},
                                                            @endfor
                                    					],
                                    				}]
                                    			},
                                    			options: {}
                                    		});
                                    	@endif
                                    }
                                
                                </script>
                            </div>
                        </div>
                    </div>    
 @endsection