<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Profile;
use App\Record;
use Carbon\Carbon;

class ProfileController extends Controller
{
    public function add()
    {
        return view('admin.profile.create');
    }

    public function create(Request $request)
    {
        //varidationを行う
        $this->validate($request, Profile::$rules);
        
        $profile = new Profile;
        $form = $request->all();
        
        unset ($form['_token']);
        
        $profile->fill($form);
        $profile->save();
        
        return redirect('admin/profile/create');    
    }
    
    public function edit(Request $request)
    {
        $profile = Profile::find($request->id);
        
        return view('admin.profile.edit',['profile_form' => $profile]);    
    }
    
    public function update(Request $request)
    {
        //validationをかける
        $this->validate($request,Profile::$rules);
        //Profile Modelからデータを取得する
        $profile = Profile::find($request->id);
        //送信されてきたフォームデータを格納する
        $profile_form = $request->all();
        unset($profile_form['_token']);
        //該当するデータを上書きして保存する
        $profile->fill($profile_form)->save();
        
        $record = new Record;
        $record -> profile_id = $profile -> id;
        $record -> edited_at = Carbon::now();
        $record -> save();
        
        return redirect()->action('Admin\ProfileController@edit',['id' => $profile->id]);
    }
    
}




