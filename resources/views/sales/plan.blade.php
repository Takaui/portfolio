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
                            <h2>テープタイプ使用者</h2>
                            <div class="float-left">
                                <p>アウター</p>
                                <table>
                                    <tr>
                                        <th>回数</th>
                                        <th>交換時間</th>
                                        <th>商品名</th>
                                    </tr>
                                    @if(isset($TapeAutaPlans) && count($TapeAutaPlans) >= 1)
                                        @foreach($TapeAutaPlans as $TapeAutaPlan)
                                                <tr>
                                                    <td>{{ $loop ->iteration }}回目</td>
                                                    <td>
                                                        <select name="t_tape_exchange_time{{$loop ->iteration}}">
                                                            <option value=""></option>
                                                            @foreach($times as $time)
                                                            <option value="{{ $time }}" @if($TapeAutaPlan->t_tape_exchange_time===$time) selected @endif>{{$time}}時</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="t_tape_item{{ $loop->iteration }}">
                                                            <option value=""></option>
                                                            <option value="テープ" @if($TapeAutaPlan->t_tape_item==="テープ") selected @endif>テープ</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                        @endforeach
                                    @else 
                                        @for ($i = 1; $i <= 5; $i++)
                                            <tr>
                                                <td>{{ $i}}回目</td>
                                                <td>
                                                    <select name="t_tape_exchange_time{{ $i}}">
                                                        <option value=""></option>
                                                        @foreach($times as $time)
                                                        <option value="{{ $time}}">{{$time}}時</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <select name="t_tape_item{{ $i}}">
                                                        <option value=""></option>
                                                        <option value="テープ" >テープ</option>
                                                    </select>
                                                </td>
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
                                        @if(isset($TapeInnerPlans) && count($TapeInnerPlans) >=1)
                                            @foreach($TapeInnerPlans as $TapeInnerPlan)
                                                    <tr>
                                                        <td>{{ $loop ->iteration }}回目</td>
                                                        <td>
                                                            <select name="t_pad_exchange_time{{$loop ->iteration}}">
                                                                <option value=""></option>
                                                                @foreach($times as $time)
                                                                <option value="{{ $time }}" @if($TapeInnerPlan->t_pad_exchange_time===$time) selected @endif>{{$time}}時</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select name="t_pad_item{{ $loop->iteration }}">
                                                                <option value=""></option>
                                                                <option value="パッド300" @if($TapeInnerPlan->t_pad_item==="パッド300") selected @endif>パッド300</option>
                                                                <option value="パッド400" @if($TapeInnerPlan->t_pad_item==="パッド400") selected @endif>パッド400</option>
                                                                <option value="パッド600" @if($TapeInnerPlan->t_pad_item==="パッド600") selected @endif>パッド600</option>
                                                                <option value="パッド800" @if($TapeInnerPlan->t_pad_item==="パッド800") selected @endif>パッド800</option>
                                                                <option value="パッド1000" @if($TapeInnerPlan->t_pad_item==="パッド1000") selected @endif>パッド1000</option>
                                                                <option value="パッド1200" @if($TapeInnerPlan->t_pad_item==="パッド1200") selected @endif>パッド1200</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                            @endforeach
                                        @else
                                            @for ($i = 1; $i <= 5; $i++)
                                                <tr>
                                                    <td>{{ $i}}回目</td>
                                                    <td>
                                                        <select name="t_pad_exchange_time{{ $i}}">
                                                            <option value=""></option>
                                                            @foreach($times as $time)
                                                            <option value="{{ $time}}">{{$time}}時</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="t_pad_item{{ $i}}">
                                                            <option value=""></option>
                                                            <option value="パッド300">パッド300</option>
                                                            <option value="パッド400">パッド400</option>
                                                            <option value="パッド600">パッド600</option>
                                                            <option value="パッド800">パッド800</option>
                                                            <option value="パッド1000">パッド1000</option>
                                                            <option value="パッド1200">パッド1200</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            @endfor
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
                                    @if(isset($PantsAutaPlans) &&count($PantsAutaPlans) >= 1 )
                                        @foreach($PantsAutaPlans as $PantsAutaPlan)
                                                <tr>
                                                    <td>{{ $loop ->iteration }}回目</td>
                                                    <td>
                                                        <select name="p_pants_exchange_time{{$loop ->iteration}}">
                                                            <option value=""></option>
                                                            @foreach($times as $time)
                                                            <option value="{{ $time }}" @if($PantsAutaPlan->p_pants_exchange_time===$time) selected @endif>{{$time}}時</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="p_pants_item{{ $loop->iteration }}">
                                                            <option value=""></option>
                                                            <option value="パンツ" @if($PantsAutaPlan->p_pants_item==="パンツ") selected @endif>パンツ</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                            </tr>
                                        @endforeach
                                    @else
                                        @for ($i = 1; $i <= 5; $i++)
                                            <tr>
                                                <td>{{ $i}}回目</td>
                                                <td>
                                                    <select name="p_pants_exchange_time{{ $i}}">
                                                        <option value=""></option>
                                                        @foreach($times as $time)
                                                        <option value="{{ $time}}">{{$time}}時</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <select name="p_pants_item{{ $i}}">
                                                        <option value=""></option>
                                                        <option value="パンツ" >パンツ</option>
                                                    </select>
                                                </td>
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
                                    @if(isset($PantsInnerPlans) && count($PantsInnerPlans) >= 1)
                                            @foreach($PantsInnerPlans as $PantsInnerPlan)
                                                    <tr>
                                                        <td>{{ $loop ->iteration }}回目</td>
                                                        <td>
                                                            <select name="p_pad_exchange_time{{$loop ->iteration}}">
                                                                <option value=""></option>
                                                                @foreach($times as $time)
                                                                <option value="{{ $time }}" @if($PantsInnerPlan->p_pad_exchange_time===$time) selected @endif>{{$time}}時</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select name="p_pad_item{{ $loop->iteration }}">
                                                                <option value=""></option>
                                                                <option value="パッド300" @if($PantsInnerPlan->p_pad_item==="パッド300") selected @endif>パッド300</option>
                                                                <option value="パッド400" @if($PantsInnerPlan->p_pad_item==="パッド400") selected @endif>パッド400</option>
                                                                <option value="パッド600" @if($PantsInnerPlan->p_pad_item==="パッド600") selected @endif>パッド600</option>
                                                                <option value="パッド800" @if($PantsInnerPlan->p_pad_item==="パッド800") selected @endif>パッド800</option>
                                                                <option value="パッド1000" @if($PantsInnerPlan->p_pad_item==="パッド1000") selected @endif>パッド1000</option>
                                                                <option value="パッド1200" @if($PantsInnerPlan->p_pad_item==="パッド1200") selected @endif>パッド1200</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                </tr>
                                            @endforeach
                                        @else
                                            @for ($i = 1; $i <= 5; $i++)
                                                <tr>
                                                    <td>{{ $i}}回目</td>
                                                    <td>
                                                        <select name="p_pad_exchange_time{{ $i}}">
                                                            <option value=""></option>
                                                            @foreach($times as $time)
                                                            <option value="{{ $time}}">{{$time}}時</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="p_pad_item{{ $i}}">
                                                            <option value=""></option>
                                                            <option value="パッド300">パッド300</option>
                                                            <option value="パッド400">パッド400</option>
                                                            <option value="パッド600">パッド600</option>
                                                            <option value="パッド800">パッド800</option>
                                                            <option value="パッド1000">パッド1000</option>
                                                            <option value="パッド1200">パッド1200</option>
                                                        </select>
                                                    </td>
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