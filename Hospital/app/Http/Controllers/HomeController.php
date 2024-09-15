<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class HomeController extends Controller
{
   public function  redirect(){
    if (Auth::id()) {
        if (Auth::user()->usertype =='0') {
            return view('user.home');
        }else{
            return view('admin.home');
        }
    }else{
        return redirect()->back();
    }
   }
}
