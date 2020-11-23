{{-- layouts/admin.blade.phpを読み込む --}}
@extends('layouts.admin')
        
        {{-- admin.blade.phpの@yield('title')に'ニュースの新規作成'を埋め込む --}}
        @section('title','月間実績入力')
        
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
                                <a href="{{ action('SalesController@plan',['id'=> $client->id ]) }}" role="button" class="btn btn-primary">目標設定</a>
                                <a href="{{ action('SalesController@create',['id'=> $client->id ])}}" role="button" class="btn btn-primary">実績入力</a>
                                <a href="{{ action('ClientsController@add') }}" role="button" class="btn btn-primary">施設情報変更</a>
                            </div>
                        </div>
                        <div class="col-md-8 mx-auto">
                        <h1>設定目標</h1>
                        <h2>テープタイプ使用者</h2>
                            <div class="float-left">
                                <p>アウター</p>
                                <table>
                                    <tr>
                                        <th>回数</th>
                                        <th>交換時間</th>
                                        <th>商品名</th>
                                    </tr>
                                    @if(isset($TapeAutaPlans))
                                        @foreach($TapeAutaPlans as $TapeAutaPlan)
                                                <tr>
                                                    <td>{{ $loop ->iteration }}回目</td>
                                                    <td>{{$TapeAutaPlan->t_tape_exchange_time}}時</td>
                                                    <td>{{$TapeAutaPlan->t_tape_item}}</td>
                                                </tr>
                                        @endforeach
                                    @endif
                                </table>
                            </div>
                            <div class="float-right">
                                <p>インナー</p>
                                <table>
                                    <tr>
                                        <th>回数</th>
                                        <th>交換時間</th>
                                        <th>商品名</th>
                                    </tr>
                                        @if(isset($TapeInnerPlans))
                                            @foreach($TapeInnerPlans as $TapeInnerPlan)
                                                    <tr>
                                                        <th>{{ $loop ->iteration }}回目</th>
                                                        <th>
                                                            <select name="t_pad_exchange_time{{$loop ->iteration}}">
                                                                <option value=""></option>
                                                                @foreach($times as $time)
                                                                <option value="{{ $time }}" @if($TapeInnerPlan->t_pad_exchange_time===$time) selected @endif>{{$time}}時</option>
                                                                @endforeach
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <select name="t_pad_item{{ $loop->iteration }}">
                                                                <option value=""></option>
                                                                <option value="pad300" @if($TapeInnerPlan->t_pad_item==="pad300") selected @endif>パッド300</option>
                                                                <option value="pad400" @if($TapeInnerPlan->t_pad_item==="pad400") selected @endif>パッド400</option>
                                                                <option value="pad600" @if($TapeInnerPlan->t_pad_item==="pad600") selected @endif>パッド600</option>
                                                                <option value="pad800" @if($TapeInnerPlan->t_pad_item==="pad800") selected @endif>パッド800</option>
                                                                <option value="pad1000" @if($TapeInnerPlan->t_pad_item==="pad1000") selected @endif>パッド1000</option>
                                                                <option value="pad1200" @if($TapeInnerPlan->t_pad_item==="pad1200") selected @endif>パッド1200</option>
                                                            </select>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </table>
                                </div>
                                <div style="clear:both"></div>
                                <h2>パンツタイプ使用者</h2>
                                <div class="float-left">
                                    <p>アウター</p>
                                    <table>
                                        <tr>
                                            <th>回数</th>
                                            <th>交換時間</th>
                                            <th>商品名</th>
                                        </tr>
                                        @if(isset($PantsAutaPlans))
                                            @foreach($PantsAutaPlans as $PantsAutaPlan)
                                                    <tr>
                                                        <th>{{ $loop ->iteration }}回目</th>
                                                        <th>
                                                            <select name="p_pants_exchange_time{{$loop ->iteration}}">
                                                                <option value=""></option>
                                                                @foreach($times as $time)
                                                                <option value="{{ $time }}" @if($PantsAutaPlan->p_pants_exchange_time===$time) selected @endif>{{$time}}時</option>
                                                                @endforeach
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <select name="p_pants_item{{ $loop->iteration }}">
                                                                <option value=""></option>
                                                                <option value="pants" @if($PantsAutaPlan->p_pants_item==="pants") selected @endif>パンツ</option>
                                                            </select>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </table>
                                </div>
                                <div class="float-right">
                                    <p>インナー</p>
                                    <table>
                                        <tr>
                                            <th>回数</th>
                                            <th>交換時間</th>
                                            <th>商品名</th>
                                        </tr>
                                        @if(isset($PantsInnerPlans))
                                                @foreach($PantsInnerPlans as $PantsInnerPlan)
                                                        <tr>
                                                            <th>{{ $loop ->iteration }}回目</th>
                                                            <th>
                                                                <select name="p_pad_exchange_time{{$loop ->iteration}}">
                                                                    <option value=""></option>
                                                                    @foreach($times as $time)
                                                                    <option value="{{ $time }}" @if($PantsInnerPlan->p_pad_exchange_time===$time) selected @endif>{{$time}}時</option>
                                                                    @endforeach
                                                                </select>
                                                            </th>
                                                            <th>
                                                                <select name="p_pad_item{{ $loop->iteration }}">
                                                                    <option value=""></option>
                                                                    <option value="pad300" @if($PantsInnerPlan->p_pad_item==="pad300") selected @endif>パッド300</option>
                                                                    <option value="pad400" @if($PantsInnerPlan->p_pad_item==="pad400") selected @endif>パッド400</option>
                                                                    <option value="pad600" @if($PantsInnerPlan->p_pad_item==="pad600") selected @endif>パッド600</option>
                                                                    <option value="pad800" @if($PantsInnerPlan->p_pad_item==="pad800") selected @endif>パッド800</option>
                                                                    <option value="pad1000" @if($PantsInnerPlan->p_pad_item==="pad1000") selected @endif>パッド1000</option>
                                                                    <option value="pad1200" @if($PantsInnerPlan->p_pad_item==="pad1200") selected @endif>パッド1200</option>
                                                                </select>
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                    </tr>
                                                @endforeach
                                            @endif
                                    </table>
                                </div>
                                </div>
                            <div style="clear:both"></div>
                    <div class= "row">
                        <div class="col-md-8 mx-auto">
                            <h1>実績</h1>
                            <div class="d-flex justify-content-end">
                                <form action="{{action('SalesController@result')}}" method="post" >
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
                                <input type="hidden" name="id" value="{{ $salesReport -> client_id }}">
                                @csrf
                                <input type="submit" class="btn btn-primary" value="確認">
                            </div>
                        </div>
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
                            <script>
                                window.onload = function(){
                                    var ctx1 = document.getElementById('chart1').getContext('2d');
                                    var ctx2 = document.getElementById('chart2').getContext('2d');
                                    var ctx3 = document.getElementById('chart3').getContext('2d');
                                    var ctx4 = document.getElementById('chart4').getContext('2d');
                                
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
                                    
                                }
                                
                            </script>
                       
                        </div>
                     </div>
                    <div class="col-md-8">
                        <h1>比較</h1>
                        <h2>枚数</h2>
                        <table>
                            <tr>
                                <td></td>
                                <td>合計</td>
                                <td>テープタイプ</td>
                                <td>パンツタイプ</td>
                                <td>パッドタイプ</td>
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
                        <h2>金額</h2>
                        <table>
                            <tr>
                                <td></td>
                                <td>合計</td>
                                <td>テープタイプ</td>
                                <td>パンツタイプ</td>
                                <td>パッドタイプ</td>
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
                    </div>
                </div>
            </div>    
 @endsection