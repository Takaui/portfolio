{{-- layouts/admin.blade.phpを読み込む --}}
@extends('layouts.client')
        
        {{-- admin.blade.phpの@yield('title')に'ニュースの新規作成'を埋め込む --}}
        @section('title','施設選択')
        
        {{-- admin.blade.phpの@yield('content')に以下のタグを埋め込む --}}
        @section('content')
    <div class= "container">
            <div class="col-mod-8 mx-auto">
                <form action="{{ action('SalesController@list') }}" method="get">
                        {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-8 mx-auto">
                         <h1>施設選択</h1>
                        @if(isset($clients) && count($clients) >=1)
                        <div class="row">
                            <table class="col-md-10 mx-auto table-hover">
                                <thead>
                                    <tr>
                                        <th  width="15%"></th>
                                        <th  width="40%">施設名</th>
                                        <th  width="20%">業態</th>
                                        <th  width="20%">床数</th>
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
                        @elseif(isset($clients) && count($clients) < 1)
                            <p>施設登録がありません。</p>
                            <p>新規施設を登録してください。</p>
                        @elseif(isset($searchClients) && count($searchClients) >=1)
                            <P>検索結果：{{$searchClientsCount}}件</P>
                            <div class="row">
                            <table class="col-md-10 mx-auto table-hover">
                                <thead>
                                    <tr>
                                        <th  width="15%"></th>
                                        <th  width="40%">施設名</th>
                                        <th  width="20%">業態</th>
                                        <th  width="20%">床数</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($searchClients as $searchClient)
                                        <tr>
                                            <td>
                                                <a href="{{ action('SalesController@clientTop',['id' => $searchClient->id]) }}" role="button" class="btn btn-primary">選択</a>
                                            </td>
                                            <td>{{ $searchClient->user_name }}</td>
                                            <td>{{ $searchClient->facility_type}}</td>
                                            <td>{{ $searchClient->number_of_bed }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                            <p>該当施設はありません。</p>
                            <p><a href="{{ action('SalesController@list') }}">施設一覧を表示する</a></p>
                        @endif
                    </div>
                </div>
            </div>
 @endsection