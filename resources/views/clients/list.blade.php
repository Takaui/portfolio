{{-- layouts/admin.blade.phpを読み込む --}}
@extends('layouts.admin')
        
        {{-- admin.blade.phpの@yield('title')に'ニュースの新規作成'を埋め込む --}}
        @section('title','施設一覧')
        
        {{-- admin.blade.phpの@yield('content')に以下のタグを埋め込む --}}
        @section('content')
    <div class= "container">
                <div class= "row">
                    <h2>施設一覧 </h2>        
                </div>
                <div class="row">
                    <div class="list-news col-md-12 mx-auto">
                        <div class="row">
                            <table class="table table-dark">
                                <thead>
                                    <tr>
                                        <th width="10%">業態</th>
                                        <th width="20%">施設名</th>
                                        <th width="10%">床数</th>
                                        <th width="10%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($clients as $client)
                                        <tr>
                                            <td>{{ $client->facility_type}}</td>
                                            <td>{{ $client->user_name }}</td>
                                            <td>{{ $client->number_of_bed }}</td>
                                            <td>
                                                <a href="{{ action('SalesController@add',['id' => $client->id])}}" role="button" class="btn btn-primary">実績入力</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
 @endsection