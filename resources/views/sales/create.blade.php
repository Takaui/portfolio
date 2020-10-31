{{-- layouts/admin.blade.phpを読み込む --}}
@extends('layouts.admin')
        
        {{-- admin.blade.phpの@yield('title')に'ニュースの新規作成'を埋め込む --}}
        @section('title','月間実績入力')
        
        {{-- admin.blade.phpの@yield('content')に以下のタグを埋め込む --}}
        @section('content')
            <div class= "container">
                <div class= "row">
                    <div class="col-md-8 mx-auto">
                        <h2>{{ $client -> user_name}}</h2>
                        <h2>月間実績入力</h2>
                        <form action="{{action('SalesController@create')}}" method="post" >
                            <div class="form-group row">
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
                            </div>
                            <div class="form-group row">
                                <table>
                                    <tr>
                                        <th>商品名</th>
                                        <th>1枚単価</th>
                                        <th>数量（枚）</th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label>テープM</label>
                                        </th>
                                        <td>
                                            <input type="text" class="form-control" name="tapeM_price" >
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="tapeM_count" >
                                        </td>
                                    </tr>
                                     <tr>
                                        <th>
                                            <label>テープL</label>
                                        </th>
                                        <td>
                                            <input type="text" class="form-control" name="tapeL_price" >
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="tapeL_count" >
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label>パンツM</label>
                                        </th>
                                        <td>
                                            <input type="text" class="form-control" name="pantsM_price" >
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="pantsM_count" >
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label>パンツL</label>
                                        </th>
                                        <td>
                                            <input type="text" class="form-control" name="pantsL_price" >
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="pantsL_count" >
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label>パッド300</label>
                                        </th>
                                        <td>
                                            <input type="text" class="form-control" name="pad300_price" >
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="pad300_count" >
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label>パッド400</label>
                                        </th>
                                        <td>
                                            <input type="text" class="form-control" name="pad400_price" >
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="pad400_count" >
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label>パッド600</label>
                                        </th>
                                        <td>
                                            <input type="text" class="form-control" name="pad600_price" >
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="pad600_count" >
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label>パッド800</label>
                                        </th>
                                        <td>
                                            <input type="text" class="form-control" name="pad800_price" >
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="pad800_count" >
                                        </td>
                                    </tr>
                                     <tr>
                                        <th>
                                            <label>パッド1000</label>
                                        </th>
                                        <td>
                                            <input type="text" class="form-control" name="pad1000_price" >
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="pad1000_count" >
                                        </td>
                                    </tr>
                                     <tr>
                                        <th>
                                            <label>パッド1200</label>
                                        </th>
                                        <td>
                                            <input type="text" class="form-control" name="pad1200_price" >
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="pad1200_count" >
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        <input type="hidden" name="client_id" value="{{ $client -> id }}">
                        @csrf
                        <input type="submit" class="btn btn-primary" value="送信">
                    </div>
                </div>
            </div>
 @endsection