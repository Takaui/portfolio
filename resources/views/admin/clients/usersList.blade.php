{{-- layouts/admin.blade.phpを読み込む --}}
@extends('layouts.admin')
        
        {{-- admin.blade.phpの@yield('title')に'ニュースの新規作成'を埋め込む --}}
        @section('title','登録者一覧（お客様）')
        
        {{-- admin.blade.phpの@yield('content')に以下のタグを埋め込む --}}
        @section('content')
    <div class= "container">
                <div class="row">
                    <div class="col-md-8 mx-auto">
                        <a href="{{ route('register')}}" role="button" class="btn btn-primary">新規利用者登録（お客様）</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 mx-auto">
                         <h1>登録者一覧 （お客様）</h1>
                        @if(isset($users) && count($users) >=1)
                        <div class="row">
                            <table class="col-md-10 mx-auto table-hover">
                                <thead>
                                    <tr>
                                        <th  width="15%">施設ID</th>
                                        <th  width="25%">名前</th>
                                        <th  width="20%">メールアドレス</th>
                                        <th  width="15%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>{{ $user->client_id}}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email}}</td>
                                            <td>
                                                <a href="{{ action('Admin\ClientsController@userDelete',['id' => $user->id]) }}" role="button" class="btn btn-primary">削除</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                            <p>登録情報はありません。</p>
                        @endif
                    </div>
                </div>
            </div>
 @endsection