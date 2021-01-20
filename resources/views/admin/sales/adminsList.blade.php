{{-- layouts/admin.blade.phpを読み込む --}}
@extends('layouts.admin')
        
        {{-- admin.blade.phpの@yield('title')に'ニュースの新規作成'を埋め込む --}}
        @section('title','登録者一覧（営業）')
        
        {{-- admin.blade.phpの@yield('content')に以下のタグを埋め込む --}}
        @section('content')
    <div class= "container">
                <div class="row">
                    <div class="col-md-8 mx-auto">
                        <a href="{{ route('admin.register')}}" role="button" class="btn btn-primary">新規利用者登録（営業）</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 mx-auto">
                         <h1>登録者一覧 （営業）</h1>
                        @if(isset($admins) && count($admins) >=1)
                        <div class="row">
                            <table class="col-md-10 mx-auto table-hover">
                                <thead>
                                    <tr>
                                        <th  width="15%">登録者ID</th>
                                        <th  width="40%">名前</th>
                                        <th  width="20%">メールアドレス</th>
                                        <th  width="15%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($admins as $admin)
                                        <tr>
                                            <td>{{ $admin->id}}</td>
                                            <td>{{ $admin->name }}</td>
                                            <td>{{ $admin->email}}</td>
                                            <td>
                                                <a href="{{ action('Admin\SalesController@adminDelete',['id' => $admin->id]) }}" role="button" class="btn btn-primary">削除</a>
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