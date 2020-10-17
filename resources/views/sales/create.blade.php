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
                                    <option value="apr">4月</option>
                                    <option value="may">5月</option>
                                    <option value="jun">6月</option>
                                    <option value="jul">7月</option>
                                    <option value="aug">8月</option>
                                    <option value="sep">9月</option>
                                    <option value="oct">10月</option>
                                    <option value="nov">11月</option>
                                    <option value="dec">12月</option>
                                    <option value="jan">1月</option>
                                    <option value="feb">2月</option>
                                    <option value="mar">3月</option>
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
                                            <input type="text" class="form-control" name="tepuM-price" >
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="tepuM-count" >
                                        </td>
                                    </tr>
                                     <tr>
                                        <th>
                                            <label>テープL</label>
                                        </th>
                                        <td>
                                            <input type="text" class="form-control" name="tepuL-price" >
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="tepuL-count" >
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label>パンツM</label>
                                        </th>
                                        <td>
                                            <input type="text" class="form-control" name="pantsM-price" >
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="pantsM-count" >
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label>パンツL</label>
                                        </th>
                                        <td>
                                            <input type="text" class="form-control" name="pantsL-price" >
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="pantsL-count" >
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label>パッド300</label>
                                        </th>
                                        <td>
                                            <input type="text" class="form-control" name="pad300-price" >
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="pad300-count" >
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label>パッド400</label>
                                        </th>
                                        <td>
                                            <input type="text" class="form-control" name="pad400-price" >
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="pad400-count" >
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label>パッド600</label>
                                        </th>
                                        <td>
                                            <input type="text" class="form-control" name="pad600-price" >
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="pad600-count" >
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label>パッド800</label>
                                        </th>
                                        <td>
                                            <input type="text" class="form-control" name="pad800-price" >
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="pad800-count" >
                                        </td>
                                    </tr>
                                     <tr>
                                        <th>
                                            <label>パッド1000</label>
                                        </th>
                                        <td>
                                            <input type="text" class="form-control" name="pad1000-price" >
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="pad1000-count" >
                                        </td>
                                    </tr>
                                     <tr>
                                        <th>
                                            <label>パッド1200</label>
                                        </th>
                                        <td>
                                            <input type="text" class="form-control" name="pad1200-price" >
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="pad1200-count" >
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        <input type="hidden" name="client_id" value="{{ $client -> id }}">
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-primary" value="送信">
                    </div>
                </div>
            </div>
 @endsection