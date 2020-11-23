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
                        <div class="row">
                            <div class="col-md-8 mx-auto">
                                <a href="{{ action('SalesController@result',['id'=> $client->id ]) }}" role="button" class="btn btn-primary">実績確認</a>
                                <a href="{{ action('SalesController@plan',['id'=> $client->id ]) }}" role="button" class="btn btn-primary">目標設定</a>
                                <a href="{{ action('SalesController@create',['id'=> $client->id ])}}" role="button" class="btn btn-primary">実績入力</a>
                                <a href="{{ action('ClientsController@add') }}" role="button" class="btn btn-primary">施設情報変更</a>
                            </div>
                        </div>
                    </div>
 @endsection