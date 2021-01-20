{{-- layouts/admin.blade.phpを読み込む --}}
@extends('layouts.admin')
        
        {{-- admin.blade.phpの@yield('title')に'ニュースの新規作成'を埋め込む --}}
        @section('title','月間実績入力')
        
        {{-- admin.blade.phpの@yield('content')に以下のタグを埋め込む --}}
        @section('content')
            <div class= "container">
                <div class= "row">
                    <div class="col-md-8 mx-auto text-center">
                        <h3　class="client-top">納品先名　：　{{ $client ->user_name }} ({{$client -> number_of_bed }}床)</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 mx-auto">
                        <h1>月間実績入力</h1>
                    </div>
                </div>
                        <form action="{{action('Admin\SalesController@create')}}" method="post" >
                            <div class="form-group row">
                                <div class="col-md-8 mx-auto">
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
                            </div>
                            <div class="form-group row">
                                <div class="col-md-8 mx-auto">
                                    <h2>紙おむつ使用実績</h2>
                                    <table>
                                        <tr>
                                            <th>商品名</th>
                                            <th>1枚単価</th>
                                            <th>数量（枚）</th>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label>テープM</label>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="tapeM_price" >
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="tapeM_count" >
                                            </td>
                                        </tr>
                                         <tr>
                                            <td>
                                                <label>テープL</label>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="tapeL_price" >
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="tapeL_count" >
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label>パンツM</label>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="pantsM_price" >
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="pantsM_count" >
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label>パンツL</label>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="pantsL_price" >
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="pantsL_count" >
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label>パッド300</label>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="pad300_price" >
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="pad300_count" >
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label>パッド400</label>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="pad400_price" >
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="pad400_count" >
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label>パッド600</label>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="pad600_price" >
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="pad600_count" >
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label>パッド800</label>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="pad800_price" >
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="pad800_count" >
                                            </td>
                                        </tr>
                                         <tr>
                                            <td>
                                                <label>パッド1000</label>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="pad1000_price" >
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="pad1000_count" >
                                            </td>
                                        </tr>
                                         <tr>
                                            <td>
                                                <label>パッド1200</label>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="pad1200_price" >
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="pad1200_count" >
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        <div class="form-group row">
                                <div class="col-md-8 mx-auto">
                                    <h2>紙おむつ使用人数</h2>
                                    <p>テープ使用人数</p>
                                    <table>
                                        <tr>
                                            <td class="mx-auto">テープM</td>
                                            <td><input type="text" class="form-control" name="tapem_user_count"></td>
                                            <td>テープL</td>
                                            <td><input type="text" class="form-control" name="tapel_user_count"></td>
                                        </tr>
                                    </table>
                                    <h2>パンツ使用人数</h2>
                                    <table>
                                        <tr>
                                            <td>パンツM</td>
                                            <td><input type="text" class="form-control" name="pantsm_user_count"></td>
                                            <td>パンツL</td>
                                            <td><input type="text" class="form-control" name="pantsl_user_count"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <input type="hidden" name="client_id" value="{{ $client -> id }}">
                            @csrf
                            <input type="submit" class="btn btn-primary" value="送信">
                        
                    </div>
                </div>
 @endsection