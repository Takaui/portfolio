{{-- layouts/admin.blade.phpを読み込む --}}
@extends('layouts.admin')
        
        {{-- admin.blade.phpの@yield('title')に'ニュースの新規作成'を埋め込む --}}
        @section('title','目標設定')
        
        {{-- admin.blade.phpの@yield('content')に以下のタグを埋め込む --}}
        @section('content')
            <div class= "container">
                <div class= "row">
                    <div class="col-md-8 mx-auto">
                        <h2>目標設定</h2>
                        </br>
                        <form action="{{action('SalesController@planSave')}}" method="post" >
                        <div>
                            <p>テープタイプ使用者</p>
                            <div class="float-left">
                                <p>アウター</p>
                                <table>
                                    <tr>
                                        <th>回数</th>
                                        <th>交換時間</th>
                                        <th>商品名</th>
                                    </tr>
                                    <tr>
                                        <th>1回目</th>
                                        <th>
                                            <select name="t_tape_exchange_time1">
                                                <option value=""></option>
                                                @foreach($times as $time)
                                                <option value="{{ $time}}">{{$time}}時</option>
                                                @endforeach
                                            </select>
                                        </th>
                                        <th>
                                            <select name="t_tape_item1">
                                                <option value=""></option>
                                                <option value="tapeM">テープM</option>
                                                <option value="tapeL">テープL</option>
                                            </select>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>2回目</th>
                                        <th>
                                            <select name="t_tape_exchange_time2">
                                            <option value=""></option>
                                            @foreach($times as $time)
                                            <option value="{{ $time}}">{{$time}}時</option>
                                            @endforeach
                                            </select>
                                        </th>
                                        <th>
                                            <select name="t_tape_item2">
                                            <option value=""></option>
                                                <option value="tapeM">テープM</option>
                                                <option value="tapeL">テープL</option>
                                            </select>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>3回目</th>
                                        <th>
                                            <select name="t_tape_exchange_time3">
                                                <option value=""></option>
                                                @foreach($times as $time)
                                                <option value="{{ $time}}">{{$time}}時</option>
                                                @endforeach
                                            </select>
                                        </th>
                                        <th>
                                            <select name="t_tape_item3">
                                                <option value=""></option>
                                                <option value="tapeM">テープM</option>
                                                <option value="tapeL">テープL</option>
                                            </select>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>4回目</th>
                                        <th>
                                            <select name="t_tape_exchange_time4">
                                                <option value=""></option>
                                                @foreach($times as $time)
                                                <option value="{{ $time}}">{{$time}}時</option>
                                                @endforeach
                                            </select>
                                        </th>
                                        <th>
                                            <select name="t_tape_item4">
                                                <option value=""></option>
                                                <option value="tapeM">テープM</option>
                                                <option value="tapeL">テープL</option>
                                            </select>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>5回目</th>
                                        <th>
                                            <select name="t_tape_exchange_time5">
                                                <option value=""></option>
                                                @foreach($times as $time)
                                                <option value="{{ $time}}">{{$time}}時</option>
                                                @endforeach
                                            </select>
                                        </th>
                                        <th>
                                            <select name="t_tape_item5">
                                                <option value=""></option>
                                                <option value="tapeM">テープM</option>
                                                <option value="tapeL">テープL</option>
                                            </select>
                                        </th>
                                    </tr>
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
                                    <tr>
                                        <th>1回目</th>
                                        <th>
                                            <select name="t_pad_exchange_time1">
                                                <option value=""></option>
                                                @foreach($times as $time)
                                                <option value="{{ $time}}">{{$time}}時</option>
                                                @endforeach
                                            </select>
                                        </th>
                                        <th>
                                            <select name="t_pad_item1">
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
                                    <tr>
                                        <th>2回目</th>
                                        <th>
                                            <select name="t_pad_exchange_time2">
                                                <option value=""></option>
                                                @foreach($times as $time)
                                                <option value="{{ $time}}">{{$time}}時</option>
                                                @endforeach
                                            </select>
                                        </th>
                                        <th>
                                            <select name="t_pad_item2">
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
                                    <tr>
                                        <th>3回目</th>
                                        <th>
                                            <select name="t_pad_exchange_time3">
                                                <option value=""></option>
                                                @foreach($times as $time)
                                                <option value="{{ $time}}">{{$time}}時</option>
                                                @endforeach
                                            </select>
                                        </th>
                                        <th>
                                            <select name="t_pad_item3">
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
                                    <tr>
                                        <th>4回目</th>
                                        <th>
                                            <select name="t_pad_exchange_time4">
                                                <option value=""></option>
                                                @foreach($times as $time)
                                                <option value="{{ $time}}">{{$time}}時</option>
                                                @endforeach
                                            </select>
                                        </th>
                                        <th>
                                            <select name="t_pad_item4">
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
                                    <tr>
                                        <th>5回目</th>
                                        <th>
                                            <select name="t_pad_exchange_time5">
                                                <option value=""></option>
                                                @foreach($times as $time)
                                                <option value="{{ $time}}">{{$time}}時</option>
                                                @endforeach
                                            </select>
                                        </th>
                                        <th>
                                            <select name="t_pad_item5">
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
                                </table>
                            </div>
                            <div style="clear:both"></div>
                        </div>
                       
                        </br>
                        <div>
                            <div>
                            <p>パンツタイプ使用者</p>
                            <div class="float-left">
                                <p>アウター</p>
                                <table>
                                    <tr>
                                        <th>回数</th>
                                        <th>交換時間</th>
                                        <th>商品名</th>
                                    </tr>
                                    <tr>
                                        <th>1回目</th>
                                        <th>
                                            <select name="p_pants_exchange_time1">
                                                <option value=""></option>
                                                @foreach($times as $time)
                                                <option value="{{ $time}}">{{$time}}時</option>
                                                @endforeach
                                                </select>
                                        </th>
                                        <th>
                                            <select name="p_pants_item1">
                                                <option value=""></option>
                                                <option value="pantsM">パンツM</option>M</option>
                                                <option value="pantsL">パンツL</option>
                                            </select>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>2回目</th>
                                        <th>
                                            <select name="p_pants_exchange_time2">
                                                <option value=""></option>
                                                @foreach($times as $time)
                                                <option value="{{ $time}}">{{$time}}時</option>
                                                @endforeach
                                                </select>
                                        </th>
                                        <th>
                                            <select name="p_pants_item2">
                                                <option value=""></option>
                                                <option value="pantsM">パンツM</option>M</option>
                                                <option value="pantsL">パンツL</option>
                                            </select>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>3回目</th>
                                        <th>
                                            <select name="p_pants_exchange_time3">
                                                <option value=""></option>
                                                @foreach($times as $time)
                                                <option value="{{ $time}}">{{$time}}時</option>
                                                @endforeach
                                                </select>
                                        </th>
                                        <th>
                                            <select name="p_pants_item3">
                                                <option value=""></option>
                                                <option value="pantsM">パンツM</option>M</option>
                                                <option value="pantsL">パンツL</option>
                                            </select>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>4回目</th>
                                        <th>
                                            <select name="p_pants_exchange_time4">
                                                <option value=""></option>
                                                @foreach($times as $time)
                                                <option value="{{ $time}}">{{$time}}時</option>
                                                @endforeach
                                                </select>
                                        </th>
                                        <th>
                                            <select name="p_pants_item4">
                                                <option value=""></option>
                                                <option value="pantsM">パンツM</option>M</option>
                                                <option value="pantsL">パンツL</option>
                                            </select>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>5回目</th>
                                        <th>
                                            <select name="p_pants_exchange_time5">
                                                <option value=""></option>
                                                @foreach($times as $time)
                                                <option value="{{ $time}}">{{$time}}時</option>
                                                @endforeach
                                                </select>
                                        </th>
                                        <th>
                                            <select name="p_pants_item5">
                                                <option value=""></option>
                                                <option value="pantsM">パンツM</option>
                                                <option value="pantsL">パンツL</option>
                                            </select>
                                        </th>
                                    </tr>
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
                                    <tr>
                                        <th>1回目</th>
                                        <th>
                                            <select name="p_pad_exchange_time1">
                                                <option value=""></option>
                                                @foreach($times as $time)
                                                <option value="{{ $time}}">{{$time}}時</option>
                                                @endforeach
                                            </select>
                                        </th>
                                        <th>
                                            <select name="p_pad_item1">
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
                                     <tr>
                                        <th>2回目</th>
                                        <th>
                                            <select name="p_pad_exchange_time2">
                                                <option value=""></option>
                                                @foreach($times as $time)
                                                <option value="{{ $time}}">{{$time}}時</option>
                                                @endforeach
                                            </select>
                                        </th>
                                        <th>
                                            <select name="p_pad_item2">
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
                                     <tr>
                                        <th>3回目</th>
                                        <th>
                                            <select name="p_pad_exchange_time3">
                                                <option value=""></option>
                                                @foreach($times as $time)
                                                <option value="{{ $time}}">{{$time}}時</option>
                                                @endforeach
                                            </select>
                                        </th>
                                        <th>
                                            <select name="p_pad_item3">
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
                                     <tr>
                                        <th>4回目</th>
                                        <th>
                                            <select name="p_pad_exchange_time4">
                                                <option value=""></option>
                                                @foreach($times as $time)
                                                <option value="{{ $time}}">{{$time}}時</option>
                                                @endforeach
                                            </select>
                                        </th>
                                        <th>
                                            <select name="p_pad_item4">
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
                                     <tr>
                                        <th>5回目</th>
                                        <th>
                                            <select name="p_pad_exchange_time5">
                                                <option value=""></option>
                                                @foreach($times as $time)
                                                <option value="{{ $time}}">{{$time}}時</option>
                                                @endforeach
                                            </select>
                                        </th>
                                        <th>
                                            <select name="p_pad_item5">
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
                                </table>
                            </div>
                            <div style="clear:both"></div>
                        </div>
                        <input type="hidden" name="client_id" value="{{ $client -> id }}">
                        @csrf
                        <input type="submit" class="btn btn-primary" value="登録">
                        </div>
                    </div>
                </div>
            </div>    
 @endsection