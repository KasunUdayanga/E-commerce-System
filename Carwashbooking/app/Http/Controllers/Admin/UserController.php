<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Appointment;
use App\Http\Controllers\CustomController;
use App\Setting;
use App\User;
use Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users =  User::doesntHave('roles')->get();
        return view('admin.user.user',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
            'phone' => ['required','numeric','digits:10'],
        ]);
        $data = $request->all();
        if ($file = $request->hasfile('image'))
        {
            $request->validate(
            ['image' => 'max:1000'],
            [
                'image.max' => 'The Image May Not Be Greater Than 1 MegaBytes.',
            ]);
            $request->validate([
                'image' => 'bail|mimes:jpeg,jpg,png',
            ]);
            $data['image'] = (new CustomController)->uploadImage($request->image);
        }
        else
        {
            $data['image'] = 'noimage.jpg';
        }
        $data['status'] = 1;
        $data['is_verified'] = 1;
        User::create($data);
        return redirect('admin/user')->with('msg','User created successfully..!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($user)
    {
        $user = User::find($user);
        $appointments = Appointment::where('user_id',$user)->get();
        $appointments_approve = Appointment::where([['user_id',$user],['appointment_status','APPROVE']])->get();
        $appointments_pending = Appointment::where([['user_id',$user],['appointment_status','PENDING']])->get();
        $appointments_complete = Appointment::where([['user_id',$user],['appointment_status','COMPLETE']])->get();
        $appointments_cancel = Appointment::where([['user_id',$user],['appointment_status','CANCEL']])->get();
        $appointments_reject = Appointment::where([['user_id',$user],['appointment_status','REJECT']])->get();
        $currency = Setting::first()->currency_symbol;
        return view('admin.user.user_profile',compact('user','appointments','currency','appointments_approve','appointments_pending','appointments_complete','appointments_cancel','appointments_reject'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($user)
    {
        $data = User::find($user);
        return response(['success' => true , 'data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['required','numeric','digits:10'],
        ]);
        $data = $request->all();
        $id = User::find($user);
        if ($file = $request->hasfile('image'))
        {
            $request->validate(
            ['image' => 'max:1000'],
            [
                'image.max' => 'The Image May Not Be Greater Than 1 MegaBytes.',
            ]);
            (new CustomController)->deleteImage($id->image);
            $data['image'] = (new CustomController)->uploadImage($request->image);
        }
        if($request->password == null)
        {
            $data['password'] = $id['password'];
        }
        else
        {
            $request->validate([
                'password' => ['required', 'string', 'min:6']
            ]);
            $data['password'] = Hash::make($request->password);
        }
        $id->update($data);
        return redirect('admin/user')->with('msg','User updated successfully..!!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($user)
    {
        $appointment = Appointment::all();
        foreach ($appointment as $value)
        {
            $user_id = explode(',',$value['user_id']);
            if (($key = array_search($user, $user_id)) !== false)
            {
                return response(['success' => false , 'data' => 'This user connected with Appointment first delete appointment']);
            }
        }
        $id = User::find($user);
        (new CustomController)->deleteImage($id->image);
        $id->delete();
        return response(['success' => true]);
    }
}
