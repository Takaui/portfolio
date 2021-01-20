{{-- layouts/admin.blade.phpを読み込む --}}
@extends('layouts.admin')
        
        {{-- admin.blade.phpの@yield('title')に'ニュースの新規作成'を埋め込む --}}
        @section('title','施設登録')
        
        {{-- admin.blade.phpの@yield('content')に以下のタグを埋め込む --}}
        @section('content')
            <div class= "container">
                <div class= "row">
                    <div class="col-md-8 mx-auto">
                        <h1>施設登録</h1>
                        <form action="{{ action('Admin\ClientsController@create') }}" method="post" >
                             @csrf
                            <div class="form-group row">
                                <label class="col-md-2">業態</label>
                                    <div class="col-md-8">
                                        <select name="facility_type">
                                            <option value="病院">病院</option>
                                            <option value="特養">特養</option>
                                            <option value="老健">老健</option>
                                        </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2">施設名</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="user_name" >
                                    </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2">床数</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="number_of_bed" >
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <input type="submit" class="btn btn-primary" value="作成">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
 @endsection