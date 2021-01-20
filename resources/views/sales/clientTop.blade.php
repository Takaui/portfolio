{{-- layouts/admin.blade.phpを読み込む --}}
@extends('layouts.client')
        
        {{-- admin.blade.phpの@yield('title')に'ニュースの新規作成'を埋め込む --}}
        @section('title','施設TOP画面')
        
        {{-- admin.blade.phpの@yield('content')に以下のタグを埋め込む --}}
        @section('content')
                    <div class= "container">
                        <div class="row">
                            <div class="col-md-8 mx-auto text-center">
                                <h3　class="client-top">納品先名：　{{ $client ->user_name }} ({{$client -> number_of_bed }}床)</h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8 mx-auto">
                                <a href="{{ action('SalesController@result',['id'=> $client->id ]) }}" role="button" class="btn btn-primary">実績確認</a>
                            </div>
                        </div>
                    </div>
 @endsection