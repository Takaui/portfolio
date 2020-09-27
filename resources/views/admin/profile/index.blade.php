{{-- layouts/admin.blade.phpを読み込む --}}
@extends('layouts.admin')
        
        {{-- admin.blade.phpの@yield('title')に'ニュースの新規作成'を埋め込む --}}
        @section('title','登録済みのプロフィール')
        
        {{-- admin.blade.phpの@yield('content')に以下のタグを埋め込む --}}
        @section('content')
            <div class= "container">
                <div class= "row">
                    <h2>登録済みのプロフィール</h2>        
                </div>
                <div class="row">
                    <div class="list-news col-md-12 mx-auto">
                        <div class="row">
                            <table class="table table-dark">
                                <thead>
                                    <tr>
                                        <th width="10%">ID</th>
                                        <th width="20%">名前</th>
                                        <th width="20%">性別</th>
                                        <th width="20%">趣味</th>
                                        <th width="50%">自己紹介欄</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach( $profile_form as $profile)
                                        <tr>
                                            <th>{{ $profile->id }}</th>
                                            <td>{{ $profile->name }}</td>
                                            <td>{{ $profile->gender }}</td>
                                            <td>{{ \Str::limit($profile->hobby,100) }}</td>
                                            <td>{{ \Str::limit($profile->introduction,100) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
 @endsection