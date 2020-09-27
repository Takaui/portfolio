{{-- layouts/admin.blade.phpを読み込む --}}
@extends('layouts.admin')
        
{{-- admin.blade.phpの@yield('title')に'ニュースの新規作成'を埋め込む --}}
@section('title','プロフィール編集')
        
{{-- admin.blade.phpの@yield('content')に以下のタグを埋め込む --}}
@section('content')
　<div class="container">
　    <div class="row">
　        <div class="col-md-8 mx-auto">
　            <h2>プロフィール編集</h2>
　            <form action="{{ action('Admin\ProfileController@update') }}" method="post" enctype="multipart/form-data">
　               @if (count($errors) > 0)
　                   <ul>
　                       @foreach($errors->all() as $e)
　                           <li>{{ $e }}</li>
　                       @endforeach
　                   </ul>
　               @endif
　               <div class="form-group row">
　                   <label class="col-md-2" for="name">氏名</label>
　                   <div class="col-md-10">
　                       <input type="text" class="form-control" name="name" value="{{ $profile_form->name }}">
　                   </div>
　               </div>
　               <div class="form-group row">
　                   <label class="col-md-2" for="gender">性別</label>
　                   <div>
                        <input type="radio"  name="gender" value="man" checked="{{ $profile_form->gender=="man"? 'checked':null}}">男
                        <input type="radio" name="gender" value="woman" checked="{{ $profile_form->gender=="woman"? 'checked':null}}">女
                    </div>
　               </div>
　               <div class="form-group row">
　                   <label class="col-md-2" for="hobby">趣味</label>
　                   <div class="col-md-10">
                        <input type="text" class="form-control" name="hobby" value="{{ $profile_form->hobby}}">
                    </div>
　               </div>
　                <div class="form-group row">
                    <label class="col-md-2">自己紹介欄</label>
                    <div class="col-md-10">
                        <textarea class="form-control" name="introduction" rows="10">{{ $profile_form->introduction}}</textarea>
                    </div>
　               <div class="form-group row">
　                   <div class="col-md-10">
　                       <input type="hidden" name="id" value="{{ $profile_form->id }}">
　                       {{ csrf_field() }}
　                       <input type="submit" class="btn btn-primary" value="更新">
　                       {{--更新履歴--}}
　                       <div class="row mt-5">
　                           <h2>編集履歴</h2>
　                           <ul class="list-group">
　                               @if ($profile_form->records != NULL)
　                                   @foreach ($profile_form->records as $record)
　                                       <li class="list-group-item">{{ $record->edited_at }}</li>
　                                   @endforeach
　                               @endif
　                           </ul>
　                       </div>
　                   </div>
　               </div>
　            </form>
　       </div>
　    </div>
　</div>
@endsection