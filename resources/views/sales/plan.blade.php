{{-- layouts/admin.blade.phpを読み込む --}}
@extends('layouts.admin')
        
        {{-- admin.blade.phpの@yield('title')に'ニュースの新規作成'を埋め込む --}}
        @section('title','目標設定')
        
        {{-- admin.blade.phpの@yield('content')に以下のタグを埋め込む --}}
        @section('content')
            <div class= "container">
                <div class= "row">
                    <div class="col-md-8 mx-auto">
                        <h1>目標設定</h1>
                        </br>
                        <form action="{{action('SalesController@planSave')}}" method="post" >
                        <div>
                            <p class="plan-user">テープタイプ使用者</p>
                            <div class="float-left">
                                <p>アウター</p>
                                <table>
                                    <tr>
                                        <th>回数</th>
                                        <th>交換時間</th>
                                        <th>商品名</th>
                                    </tr>
                                    @if(!empty($TapeAutaPlans))
                                    aaaaaaaaaaaaaaaaaaaaaaaaaa
                                        @foreach($TapeAutaPlans as $TapeAutaPlan)
                                                <tr>
                                                    <th>{{ $loop ->iteration }}回目</th>
                                                    <th>
                                                        <select name="t_tape_exchange_time{{$loop ->iteration}}">
                                                            <option value=""></option>
                                                            @foreach($times as $time)
                                                            <option value="{{ $time }}" @if($TapeAutaPlan->t_tape_exchange_time===$time) selected @endif>{{$time}}時</option>
                                                            @endforeach
                                                        </select>
                                                    </th>
                                                    <th>
                                                        <select name="t_tape_item{{ $loop->iteration }}">
                                                            <option value=""></option>
                                                            <option value="tape" @if($TapeAutaPlan->t_tape_item==="tape") selected @endif>テープ</option>
                                                        </select>
                                                    </th>
                                                </tr>
                                        @endforeach
                                    @else 
                                        @for ($i = 1; $i <= 5; $i++)
                                            <tr>
                                                <th>{{ $i}}回目</th>
                                                <th>
                                                    <select name="t_tape_exchange_time{{ $i}}">
                                                        <option value=""></option>
                                                        @foreach($times as $time)
                                                        <option value="{{ $time}}">{{$time}}時</option>
                                                        @endforeach
                                                    </select>
                                                </th>
                                                <th>
                                                    <select name="t_tape_item{{ $i}}">
                                                        <option value=""></option>
                                                        <option value="tape" >テープ</option>
                                                    </select>
                                                </th>
                                            </tr>
                                        @endfor
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
                                        @else
                                            @for ($i = 1; $i <= 5; $i++)
                                                <tr>
                                                    <th>{{ $i}}回目</th>
                                                    <th>
                                                        <select name="t_pad_exchange_time{{ $i}}">
                                                            <option value=""></option>
                                                            @foreach($times as $time)
                                                            <option value="{{ $time}}">{{$time}}時</option>
                                                            @endforeach
                                                        </select>
                                                    </th>
                                                    <th>
                                                        <select name="t_pad_item{{ $i}}">
                                                            <option value=""></option>
                                                            <option value="pad300">パッド300</option>
                                                            <option value="pad400">パッド400</option>
                                                            <option value="pad600">パッド600</option>
                                                            <option value="pad800">パッド800</option>
                                                            <option value="pad1000">パッド1000</option>
                                                            <option value="pad1200">パッド1200</option>
                                                        </select>
                                                    </th>
                                                </tr>
                                            @endfor
                                        @endif
                                    </table>
                                </div>
                            <div style="clear:both"></div>
                            <p class="plan-user ">パンツタイプ使用者</p>
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
                                    @else
                                        @for ($i = 1; $i <= 5; $i++)
                                            <tr>
                                                <th>{{ $i}}回目</th>
                                                <th>
                                                    <select name="p_pants_exchange_time{{ $i}}">
                                                        <option value=""></option>
                                                        @foreach($times as $time)
                                                        <option value="{{ $time}}">{{$time}}時</option>
                                                        @endforeach
                                                    </select>
                                                </th>
                                                <th>
                                                    <select name="p_pants_item{{ $i}}">
                                                        <option value=""></option>
                                                        <option value="pants" >パンツ</option>
                                                    </select>
                                                </th>
                                            </tr>
                                        @endfor
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
                                        @else
                                            @for ($i = 1; $i <= 5; $i++)
                                                <tr>
                                                    <th>{{ $i}}回目</th>
                                                    <th>
                                                        <select name="p_pad_exchange_time{{ $i}}">
                                                            <option value=""></option>
                                                            @foreach($times as $time)
                                                            <option value="{{ $time}}">{{$time}}時</option>
                                                            @endforeach
                                                        </select>
                                                    </th>
                                                    <th>
                                                        <select name="p_pad_item{{ $i}}">
                                                            <option value=""></option>
                                                            <option value="pad300">パッド300</option>
                                                            <option value="pad400">パッド400</option>
                                                            <option value="pad600">パッド600</option>
                                                            <option value="pad800">パッド800</option>
                                                            <option value="pad1000">パッド1000</option>
                                                            <option value="pad1200">パッド1200</option>
                                                        </select>
                                                    </th>
                                                </tr>
                                            @endfor
                                        @endif
                                </table>
                            </div>
                            <div style="clear:both"></div>
                        </div>
                        <input type="hidden" name="client_id" value="{{ $client -> id }}">
                        @csrf
                        <input type="submit" class="btn btn-primary" value="登録">
                        <a href="{{ action('SalesController@planDelete',['id' => $client->id]) }}" role="button" class="btn btn-primary">設定解除</a>
                        </div>
                    </div>
                </div>
            </div>    
 @endsection