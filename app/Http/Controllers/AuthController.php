<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;


class AuthController extends Controller
{
    function signup(){
        return view ("signup") ;
    }
    function register(Request $request){
        $fields = $request -> validate([

            "name" => ['required','max:50','min:3','alpha_num'],
            "email" => ['required','email','unique:users,email'],
            "password" => ['required','max:50','min:8'],
            "phone_number" => ['required','regex:/^\+?[0-9]{8,15}$/'],
            "date_of_birth" => ['required'],
            "image" => ['image','mimes:jpg,jpeg,png,','max:2048']
        ]);

        $fields['role_id'] = 14;
        //Check if an image is uploaded or not
        if ($request->hasFile('image')) {
            $fields['image'] = $request->file('image')->store('profile_images', 'public');//storage/app/public/profile_images/
        } else {
            $fields['image'] = 'images/default.png';
        }
       //After validating the data ,we insert them to the db.
       $user= User::create($fields);
       //When the user is registered he is totally logged in
        Auth::login($user);
        return redirect('/index');

    }
    function indexPage(){
        return view("index");
    }
    function loginPage(){
        return view("login");
    }
    function login(Request $request){
        $fields = $request -> validate([
            "email" => ['required','email'],
            "password" => ['required'],
        ]);
    if(Auth::attempt($fields)){
        return redirect('/index');
    }else{
        return redirect('/login')->with('error','Wrong Credentials,try again!');
    }
    }
    function logout(){
        Auth::logout();
        return redirect('/login');
    }
    function roles(){
        $roles= DB::select('SELECT * FROM role WHERE status=1');
        return view('signup',compact('roles'));
    }
}  