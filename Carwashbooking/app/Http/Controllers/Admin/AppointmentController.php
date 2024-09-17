<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Appointment;
use App\Category;
use App\Coworkers;
use App\Notification;
use App\PaymentSetting;
use App\Service;
use App\Setting;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(Gate::denies('admin_appointment_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $appoinments = Appointment::orderBy('id','DESC')->get();
        $categories = Category::where('status',1)->get();
        $coworkers = Coworkers::where('status',1)->get();
        $users = User::doesntHave('roles')->get();
        $currency = Setting::first()->currency_symbol;
        return view('admin.appointment.appointment',compact('appoinments','categories','coworkers','users','currency'));
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
            'user_id' => 'required',
            'category_id' => 'required',
            'coworker_id' => 'required',
            'service_id' => 'required',
            'date' => 'required|date_format:Y-m-d',
            'start_time' => 'required|date_format:h:i a',
        ]);
        $data = $request->all();
        $duration = Service::whereIn('id',$request->service_id)->sum('duration');
        $data['amount'] = Service::whereIn('id',$request->service_id)->sum('price');
        $data['appointment_id'] = '#' . rand(100000, 999999);
        $data['service_id'] = implode(',',$request->service_id);
        $start_time = Carbon::parse($data['start_time']);
        $data['end_time'] = $start_time->addMinutes($duration)->format('h:i a');
        $data['duration'] = $duration;
        $data['user_id'] = $request->user_id;
        $data['appointment_status'] = 'PENDING';
        $data['payment_status'] = 0;
        $data['service_type'] = 'SHOP';
        $data['payment_type'] = 'COD';
        Appointment::create($data);
        return redirect('admin/appointment')->with('msg','appointment booked');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($appointment)
    {
        abort_if(Gate::denies('admin_appointment_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $data = Appointment::find($appointment)->makeHidden('coworker');
        $data['co_worker'] = Coworkers::find($data->coworker_id)->makeHidden('service');
        return response(['success' => true , 'data' => $data]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort_if(Gate::denies('admin_appointment_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $data = Appointment::find($id);
        return response(['success' => true , 'data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($appointment)
    {
        $notifications = Notification::where('order_id',$appointment)->get();
        foreach ($notifications as $value) {
            $value->delete();
        }
        $id = Appointment::find($appointment);
        $id->delete();
        return response(['success' => true]);
    }

    public function appointment_invoice($id)
    {
        abort_if(Gate::denies('appointment_invoice'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $data = Appointment::find($id);
        $company_data = Setting::first();
        return view('admin.appointment.invoice',compact('data','company_data'));
    }

    public function invoice_print($id)
    {
        $data = Appointment::find($id);
        $company_data = Setting::first();
        return view('admin.appointment.invoice_print',compact('data','company_data'));
    }

    public function timeslots(Request $request)
    {
        $worker_time = Coworkers::where([['id',$request->coworker_id],['status',1]])->first();
        $master = [];

        $start_time = new Carbon($request['date'] . ' ' . $worker_time->start_time);
        if ($request->date == Carbon::now()->format('Y-m-d'))
        {
            $t = Carbon::now('Asia/Kolkata');
            $minutes = date('i', strtotime($t));
            if ($minutes <= 30) {
                $add = 30 - $minutes;
            } else {
                $add = 60 - $minutes;
            }
            $add += 60;
            $d = $t->addMinutes($add)->format('h:i a');
            $start_time = new Carbon($request['date'] . ' ' . $d);
        }

        $end_time = new Carbon($request['date'] . ' ' . $worker_time->end_time);
        $diff_in_minutes = $start_time->diffInMinutes($end_time);
        for ($i = 0; $i <= $diff_in_minutes; $i += 30)
        {
            if ($start_time >= $end_time)
            {
                break;
            }
            else
            {
                $temp['start_time'] = $start_time->format('h:i a');
                $temp['end_time'] = $start_time->addMinutes('30')->format('h:i a');
                $time = strval($temp['start_time']);
                $appointment = Appointment::where([['coworker_id',$request->coworker_id],['start_time',$time],['date',$request->date]])->first();
                if ($appointment)
                {
                    $st = new Carbon($request['date'] . ' ' . $appointment->start_time);
                    $et = $st->addMinutes($appointment->duration)->format('h:i a');
                    if ($temp['start_time'] < $st && $temp['start_time'] > $et || $temp['end_time'] < $et)
                    {
                        $start_time = new Carbon($request['date'] . ' ' . $et);
                        $minutes = date('i', strtotime($start_time));
                        if ($minutes <= 30)
                        {
                            $add = 30 - $minutes;
                        }
                        else
                        {
                            $add = 60 - $minutes;
                        }
                        $d = $start_time->addMinutes($add)->format('h:i a');
                        $start_time = new Carbon($request['date'] . ' ' . $d);
                    }
                }
                else
                {
                    array_push($master, $temp);
                }
            }
        }
        return response(['success' => true , 'data' => $master]);
    }

    public function appointment_status(Request $request)
    {
        $data = $request->all();
        $appointment = Appointment::find($data['id']);
        $appointment->appointment_status = strtoupper($data['appointment_status']);
        $appointment->save();
        return response(['success' => true]);
    }
}
