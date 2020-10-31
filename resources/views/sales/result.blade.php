{{-- layouts/admin.blade.phpを読み込む --}}
@extends('layouts.admin')
        
        {{-- admin.blade.phpの@yield('title')に'ニュースの新規作成'を埋め込む --}}
        @section('title','月間実績入力')
        
        {{-- admin.blade.phpの@yield('content')に以下のタグを埋め込む --}}
        @section('content')
            <div class= "container">
                <div class= "row">
                    <div class="col-md-8 mx-auto">
                        <h2>実績確認画面</h2>
                        <h2>{{ $salesReport->user_name}}</h2>
                        <form action="{{action('SalesController@result')}}" method="post" >
                        <div class="form-group row">
                            <h3>実績年月選択</h3>
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
                        <p>{{ $salesReport->year}}年{{$salesReport->month}}月度実績</p>
                            <div class="float-left" sytle="width: 50% ; height:400px" >
                                <canvas id="Chart1" ></canvas>
                                <script>
                                    var ctx = document.getElementById('Chart1').getContext('2d');
                                    var chart = new Chart(ctx, {
                                    	type: 'pie',
                                    	data: {
                                        labels: ['テープ', 'パンツ', 'パッド'],
                                        datasets: [{
                                        label: '品群別枚数',
                                        data: [ {{$tape_group_count}},{{$pants_group_count}},{{$pad_group_count}}],
                                        backgroundColor: 'rgb(255, 99, 132)',
                                        borderColor: 'rgb(255, 99, 132)'
                                        }]
                                    },
                                    options: {}
                                    });
                                </script>
                            </div>
                            <div class="float-right" sytle="width: 50% ; height:400px">
                                <canvas id="Chart2" ></canvas>
                                <script>
                                    var ctx = document.getElementById('Chart2').getContext('2d');
                                    var chart = new Chart(ctx, {
                                    	type: 'pie',
                                    	data: {
                                    	labels: ['テープ', 'パンツ', 'パッド'],
                                    	datasets: [{
                                    	label: 'パッド内訳',
                                    	data: [ {{$tape_group_count}},{{$pants_group_count}},{{$pad_group_count}}],
                                    	backgroundColor: 'rgb(0, 99, 132)',
                                    	borderColor: 'rgb(0, 99, 132)'
                                    	    }]
                                    	},
                                    options: {}
                                    	});
                                </script>
                            </div>
                            <div style="clear:both"></div>
                            <div class="float-left" sytle="width: 50% ; height:400px" >
                                <canvas id="Chart3" ></canvas>
                                <script>
                                    var ctx = document.getElementById('Chart3').getContext('2d');
                                    var chart = new Chart(ctx, {
                                    	type: 'pie',
                                    	data: {
                                        labels: ['テープ', 'パンツ', 'パッド'],
                                        datasets: [{
                                        label: '品群別枚数',
                                        data: [ {{$tape_group_count}},{{$pants_group_count}},{{$pad_group_count}}],
                                        backgroundColor: 'rgb(255, 99, 132)',
                                        borderColor: 'rgb(255, 99, 132)'
                                        }]
                                    },
                                    options: {}
                                    });
                                </script>
                            </div>
                            <div class="float-right" sytle="width: 50% ; height:400px">
                                <canvas id="Chart4" ></canvas>
                                <script>
                                    var ctx = document.getElementById('Chart4').getContext('2d');
                                    var chart = new Chart(ctx, {
                                    	type: 'pie',
                                    	data: {
                                    	labels: ['テープ', 'パンツ', 'パッド'],
                                    	datasets: [{
                                    	label: 'パッド内訳',
                                    	data: [ {{$tape_group_count}},{{$pants_group_count}},{{$pad_group_count}}],
                                    	backgroundColor: 'rgb(0, 99, 132)',
                                    	borderColor: 'rgb(0, 99, 132)'
                                    	    }]
                                    	},
                                    options: {}
                                    	});
                                </script>
                            </div>
                            <div style="clear:both"></div>
                    </div>
                </div>
            </div>    
 @endsection