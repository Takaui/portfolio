{{-- layouts/admin.blade.phpを読み込む --}}
@extends('layouts.top')
        
        {{-- admin.blade.phpの@yield('title')に'ニュースの新規作成'を埋め込む --}}
        @section('title','TOP画面（営業用）')
        
        {{-- admin.blade.phpの@yield('content')に以下のタグを埋め込む --}}
        @section('content')
                    <div class= "container">
                        <div class="row">
                            <div class="col-md-8 mx-auto text-center">
                                <P>ログインしてください。(営業用）</P>
                                <a href="{{ secure_asset('admin/login')}}" role="button" class="btn btn-primary">ログイン画面</a>
                            </div>
                        </div>
                    </div>
 @endsection