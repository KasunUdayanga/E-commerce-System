<?php

namespace App\Http\Controllers\Employee;

use App\Appointment;
use App\CoworkerPortfolio;
use App\Coworkers;
use App\Faq;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomController;
use App\Mail\user_verification;
use App\Notification;
use App\NotificationTemplate;
use App\Review;
use App\Setting;
use App\TimeSlot;
use App\User;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Twilio\Rest\Client;
use Stripe\Order;

class EmployeeController extends Controller
{
    public function employee_login()
    {
        return view('coworker.coworker.coworker_login');
    }

    public function employee_confirm_login(Request $request)
    {
        $request->validate([
            'email' => 'bail|required|email',
            'password' => 'bail|min:6',
        ]);

        if(Auth::attempt(['email' => request('email'), 'password' => request('password')]))
        {
            $user = Auth::user()->load('roles');
            if ($user->roles->contains('name', 'employee'))
            {
                $coworker = Coworkers::where('user_id',auth()->user()->id)->first();

                if($coworker->status == 1)
                {
                    if ($user->roles->contains('name', 'employee'))
                    {
                        return redirect('coworker/coworker_home');
                    }
                    else
                    {
                        return redirect()->back()->withErrors('this credential does not match our record')->withInput();
                    }
                }
                else
                {
                    return redirect()->back()->withErrors('You disable by admin please contact admin')->withInput();
                }
            }
            else
            {
                return redirect()->back()->withErrors('Only coworker can login')->withInput();
            }
        }
        else
        {
            return redirect()->back()->withErrors('this credential does not match our record')->withInput();
        }
    }

    public function coworker_register()
    {
        return view('coworker.coworker.coworker_register');
    }

    public function coworker_confirm_register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'bail|required|email|unique:users',
            'password' => 'bail|required|min:6',
            'phone' => 'bail|required|digits:10|numeric',
        ]);
        $data = $request->all();
        $password = Hash::make($request->password);
        $user = User::create([
            'name' => $request->name,
            'image' => 'noimage.jpg',
            'phone' => $request->phone,
            'password' => $password,
            'status' => 1,
            'email' => $request->email,
        ]);
        $role_id = Role::where('name','employee')->orWhere('name','Employee')->first();
        $user->roles()->sync($role_id);

        $worker = Coworkers::create([
            'name' => $request->name,
            'user_id' => $user->id,
            'image' => 'noimage.jpg',
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => 1,
            'password' => $password,
            'start_time' => '08:00 AM',
            'end_time' => '08:00 PM',
        ]);

        if(Auth::attempt(['email' => request('email'), 'password' => request('password')]))
        {
            return redirect('coworker/coworker_home');
        }
    }

    public function coworker_home()
    {
        $coworker = Coworkers::where('user_id',auth()->user()->id)->first();
        $appointments = Appointment::where('coworker_id',$coworker->id)->count();
        $reviews = Review::where('coworker_id',$coworker->id)->count();
        $today_appointments = Appointment::orderBy('id','DESC')->where([['coworker_id',$coworker->id],['date', date("Y-m-d")]])->get();
        $currency = Setting::first()->currency_symbol;
        return view('coworker.coworker.coworker_home',compact('appointments','currency','reviews','today_appointments'));
    }

    public function appointment()
    {
        $coworker = Coworkers::where('user_id',auth()->user()->id)->first();
        $ongoings = Appointment::orderBy('id','DESC')->where([['coworker_id',$coworker->id],['date', '>=', date("Y-m-d")],['appointment_status', '!=', 'COMPLETE'],['appointment_status', '!=', 'CANCEL']])->get();
        $pasts = Appointment::orderBy('id','DESC')->where([['coworker_id',$coworker->id],['date', '<', date("Y-m-d")]])->orWhere([['date', '>=', date("Y-m-d")],['appointment_status', 'COMPLETE']])->orWhere([['date', '>=', date("Y-m-d")],['appointment_status', 'CANCEL']])->get();
        $currency = Setting::first()->currency_symbol;

        return view('coworker.worker appointment.appointment',compact('ongoings','currency','pasts'));
    }

    public function worker_review()
    {
        $worker = Coworkers::where('user_id',auth()->user()->id)->first();
        $reviews = Review::where('coworker_id',$worker->id)->get();
        return view('coworker.coworker.review',compact('reviews'));
    }

    public function worker_profile()
    {
        $worker = Coworkers::where('user_id',auth()->user()->id)->first();
        $timeslots = TimeSlot::get();
        return view('coworker.coworker.coworker_profile',compact('worker','timeslots'));
    }

    public function update_employee(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'bail|required|email',
            'phone' => 'bail|required|digits:10|numeric',
            'start_time' => 'required',
            'end_time' => 'bail|required|after:start_time',
            'experience' => 'required|numeric',
            'description' => 'required',
        ]);

        $data = $request->all();
        $id = Coworkers::where('user_id',auth()->user()->id)->first();
        if($request->password == null)
        {
            $data['password'] = $id->password;
        }
        else
        {
            $request->validate([
                'password' => 'bail|min:6',
            ]);
            $data['password'] = Hash::make($request->password);
        }
        if ($file = $request->hasfile('image'))
        {
            $request->validate(
            ['image' => 'max:1000'],
            [
                'image.max' => 'The Image May Not Be Greater Than 1 MegaBytes.',
            ]);
            $data['image'] = (new CustomController)->uploadImage($request->image);
        }
        $id->update($data);
        return redirect('coworker/coworker_home');
    }

    public function apiNotification()
    {
        $worker = Coworkers::where('user_id',auth()->user()->id)->first();
        $notifications = Notification::where([['user_type','driver'],['user_id',$worker->id]])->get()->each->setAppends([]);
        foreach ($notifications as $notification)
        {
            $user = Appointment::find($notification->order_id);
            $notification['users'] = User::find($user->user_id,['name','image']);
            $notification['date'] = Carbon::parse($notification->created_at)->format('Y-m-d');
            $notification['time'] = Carbon::parse($notification->created_at)->format('h:i a');
        }
        return response(['success' => true , 'data' => $notifications]);
    }

    public function apiSingleAppointment($id)
    {
        $appointment = Appointment::find($id);
        return response(['success' => true , 'data' => $appointment]);
    }

    public function apiChangePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'bail|required|min:6',
            'password' => 'bail|required|min:6',
            'password_confirmation' => 'bail|required|min:6',
        ]);
        $data = $request->all();
        $id = auth()->user();
        if(Hash::check($data['old_password'], $id->password) == true)
        {
            if($data['password'] == $data['password_confirmation'])
            {
                $id->password = Hash::make($data['password']);
                $id->save();
                return response(['success' => true , 'data' => 'Password Update Successfully...!!']);
            }
            else
            {
                return response(['success' => false , 'data' => 'password and confirm password does not match']);
            }
        }
        else
        {
            return response(['success' => false , 'data' => 'Old password does not match']);
        }
    }

    public function apiEmployeeFaq()
    {
        $faqs = Faq::where('for','driver')->get();
        return response(['success' => true , 'data' => $faqs]);
    }

    public function apiAppointments()
    {
        $worker = Coworkers::where('user_id',auth()->user()->id)->first();
        $appointments = Appointment::where('coworker_id',$worker->id)->Where('appointment_status','!=','PENDING')->get();
        return response(['success' => true , 'data' => $appointments]);
    }

    public function apiChangeStatus(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'appointment_status' => 'required'
        ]);
        $id = Appointment::find($request->id);
        $id->appointment_status = strtoupper($request->appointment_status);
        $id->save();
        return response(['success' => true , 'data' => $id]);
    }

    public function apiTimeslots()
    {
        $data = TimeSlot::get(['time']);
        return response(['success' => true , 'data' => $data]);
    }
}
