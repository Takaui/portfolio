{{-- layouts/admin.blade.phpを読み込む --}}
@extends('layouts.admin')
        
        {{-- admin.blade.phpの@yield('title')に'ニュースの新規作成'を埋め込む --}}
        @section('title','施設一覧')
        
        {{-- admin.blade.phpの@yield('content')に以下のタグを埋め込む --}}
        @section('content')
    <div class= "container">
            <div class="col-mod-8">
                <form action="{{ action('SalesController@list') }}" method="get">
                <div class="form-group row ">
                    <div class="col-md-4 ml-md-auto">
                        <input type="text" class="form-control" name="user_name" value="" placeholder="施設検索：施設名を入力">
                    </div>
                    <div class="col-md-2">
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-primary" value="検索">
                    </div>
                </div>
            </div>
                <div class="row">
                    <div class="list-news col-md-10 mx-auto">
                         <h1>施設一覧 </h1>        
                        <div class="row">
                            <table class="clients-table col-md-10 mx-auto table-hover">
                                <thead>
                                    <tr>
                                        <td  width="15%"></td>
                                        <td  width="30%">施設名</td>
                                        <td  width="20%">業態</td>
                                        <td  width="20%">床数</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($clients as $client)
                                        <tr>
                                            <td>
                                                <a href="{{ action('SalesController@clientTop',['id' => $client->id]) }}" role="button" class="btn btn-primary">選択</a>
                                            </td>
                                            <td>{{ $client->user_name }}</td>
                                            <td>{{ $client->facility_type}}</td>
                                            <td>{{ $client->number_of_bed }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
 @endsection